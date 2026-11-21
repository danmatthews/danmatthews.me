<?php

use App\MiniPaintings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake("local");
    Storage::fake("public");
    config(["services.minipainting.password" => "secret"]);
});

it("keeps the manage page locked without the password", function () {
    $this->get("/minipainting/manage")->assertRedirect("/minipainting/login");
    $this->post("/minipainting/photos", [])->assertRedirect("/minipainting/login");

    $this->post("/minipainting/login", ["password" => "wrong"])->assertSessionHasErrors("password");
    $this->get("/minipainting/manage")->assertRedirect("/minipainting/login");

    $this->post("/minipainting/login", ["password" => "secret"])->assertRedirect("/minipainting/manage");
    $this->get("/minipainting/manage")->assertOk();
});

it("stays locked when no password is configured", function () {
    config(["services.minipainting.password" => null]);

    $this->post("/minipainting/login", ["password" => ""])->assertSessionHasErrors("password");
    $this->get("/minipainting/manage")->assertRedirect("/minipainting/login");
});

it("shows uploaded photos newest first on the gallery and in the JSON feed", function () {
    $this->withSession(["minipainting.unlocked" => true]);

    $this->post("/minipainting/photos", [
        "image" => UploadedFile::fake()->image("a.jpg", 300, 400),
        "caption" => "First",
        "date" => "2025-03-09",
    ])->assertRedirect();
    $this->post("/minipainting/photos", [
        "image" => UploadedFile::fake()->image("b.jpg", 500, 200),
        "caption" => "Second",
    ])->assertRedirect();

    $photos = MiniPaintings::all();
    expect($photos->pluck("caption")->all())->toBe(["Second", "First"])
        ->and($photos->first())->toMatchArray(["width" => 500, "height" => 200, "date" => now()->toDateString()])
        ->and($photos[1]["date"])->toBe("2025-03-09");
    Storage::disk("public")->assertExists($photos->pluck("file")->all());

    $this->get("/minipainting")->assertInertia(fn($page) => $page
        ->component("Gallery/Index")
        ->where("total", 2)
        ->where("photos.0.caption", "Second")
        ->where("photos.1.src", url("storage/" . $photos[1]["file"]))
        ->where("photos.1.date", "2025-03-09"));

    $this->getJson("/minipainting.json")
        ->assertJsonPath("photos.0.caption", "Second")
        ->assertJsonPath("photos.1.url", url("storage/" . $photos[1]["file"]));
});

it("files new and redated photos by date, newest first", function () {
    $add = fn($caption, $date) => MiniPaintings::add(UploadedFile::fake()->image("x.jpg"), $caption, $date)["id"];
    $add("Undated", null);
    $add("Nov 10", "2026-11-10");
    $add("Nov 3", "2026-11-03");
    $add("Nov 20", "2026-11-20");
    $add("Nov 10 again", "2026-11-10");
    $june = $add("June", "2026-06-01");

    expect(MiniPaintings::all()->pluck("caption")->all())
        ->toBe(["Nov 20", "Nov 10 again", "Nov 10", "Nov 3", "June", "Undated"]);

    MiniPaintings::update($june, "June", "2026-11-15");
    MiniPaintings::update($june, "June, recaptioned", "2026-11-15");

    expect(MiniPaintings::all()->pluck("caption")->all())
        ->toBe(["Nov 20", "June, recaptioned", "Nov 10 again", "Nov 10", "Nov 3", "Undated"]);

    // An undated photo moved to the top doesn't pull older photos above newer ones.
    $ids = MiniPaintings::all()->pluck("id");
    MiniPaintings::reorder([$ids->last(), ...$ids->slice(0, -1)]);
    $add("Nov 1", "2026-11-01");
    $add("Nov 25", "2026-11-25");

    expect(MiniPaintings::all()->pluck("caption")->all())
        ->toBe(["Nov 25", "Undated", "Nov 20", "June, recaptioned", "Nov 10 again", "Nov 10", "Nov 3", "Nov 1"]);
});

it("reorders, recaptions and deletes photos", function () {
    $this->withSession(["minipainting.unlocked" => true]);
    foreach (["A", "B", "C"] as $caption) {
        MiniPaintings::add(UploadedFile::fake()->image("x.jpg"), $caption);
    }
    [$c, $b, $a] = MiniPaintings::all()->pluck("id")->all();

    $this->put("/minipainting/photos/order", ["ids" => [$a, $c, $b]]);
    $this->patch("/minipainting/photos/{$c}", ["caption" => "C2"]);
    $this->patch("/minipainting/photos/{$a}", ["caption" => "A", "date" => "25/12/2024"])->assertSessionHasErrors("date");
    $deleted = MiniPaintings::find($b)["file"];
    $this->delete("/minipainting/photos/{$b}");

    expect(MiniPaintings::all()->pluck("caption")->all())->toBe(["A", "C2"]);
    Storage::disk("public")->assertMissing($deleted);
});

it("pulls photos from another site", function () {
    Storage::disk("public")->put("minipainting/old.jpg", "old");
    MiniPaintings::save(collect([
        ["id" => "old", "file" => "minipainting/old.jpg", "width" => 1, "height" => 1, "caption" => "Local only"],
    ]));

    Http::fake([
        "live.test/minipainting.json" => Http::response(["photos" => [
            ["id" => "abc", "file" => "minipainting/abc.jpg", "width" => 4, "height" => 5, "caption" => "Live", "url" => "https://live.test/storage/minipainting/abc.jpg"],
        ]]),
        "live.test/storage/minipainting/abc.jpg" => Http::response("jpeg-bytes"),
    ]);

    $this->artisan("minipainting:pull", ["--from" => "https://live.test"])->assertSuccessful();
    expect(MiniPaintings::all()->pluck("caption")->all())->toBe(["Live", "Local only"]);
    expect(Storage::disk("public")->get("minipainting/abc.jpg"))->toBe("jpeg-bytes");

    $this->artisan("minipainting:pull", ["--from" => "https://live.test", "--prune" => true])->assertSuccessful();
    expect(MiniPaintings::all()->pluck("id")->all())->toBe(["abc"]);
    Storage::disk("public")->assertMissing("minipainting/old.jpg");
});

it("refuses to write outside the minipainting folder when pulling", function () {
    Http::fake([
        "live.test/minipainting.json" => Http::response(["photos" => [
            ["id" => "x", "file" => "../../.env", "width" => 1, "height" => 1, "caption" => "", "url" => "https://live.test/x"],
        ]]),
    ]);

    $this->artisan("minipainting:pull", ["--from" => "https://live.test"])->assertFailed();
    expect(MiniPaintings::all())->toBeEmpty();
});
