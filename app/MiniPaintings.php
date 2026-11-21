<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Photos on /minipainting. Kept out of git: images live on the public disk
 * under minipainting/, and their order, captions and sizes live in
 * storage/app/minipainting.json.
 */
class MiniPaintings
{
    public const MANIFEST = "minipainting.json";

    public const FOLDER = "minipainting";

    /**
     * @return Collection<int, array{id: string, file: string, width: int, height: int, caption: string, date: ?string}>
     */
    public static function all(): Collection
    {
        $json = Storage::disk("local")->get(self::MANIFEST);

        return collect($json ? json_decode($json, true) : []);
    }

    public static function find(string $id): ?array
    {
        return self::all()->firstWhere("id", $id);
    }

    public static function url(array $photo): string
    {
        return asset("storage/" . $photo["file"]);
    }

    /** Adds a photo to the gallery in date order. */
    public static function add(UploadedFile $image, string $caption, ?string $date = null): array
    {
        $id = Str::lower((string) Str::ulid());
        $file = $image->storeAs(
            self::FOLDER,
            $id . "." . $image->extension(),
            "public",
        );
        [$width, $height] = getimagesize(Storage::disk("public")->path($file));

        $photo = compact("id", "file", "width", "height", "caption", "date");
        self::save(self::insertByDate(self::all(), $photo));

        return $photo;
    }

    /** @param ?string $date Y-m-d, the day the photo was taken. A new date moves the photo to match. */
    public static function update(string $id, string $caption, ?string $date): void
    {
        $all = self::all();
        $photo = $all->firstWhere("id", $id);
        if (! $photo) {
            return;
        }

        $updated = [...$photo, "caption" => $caption, "date" => $date];
        self::save(
            ($photo["date"] ?? null) === $date
                ? $all->map(fn($p) => $p["id"] === $id ? $updated : $p)
                : self::insertByDate($all->reject(fn($p) => $p["id"] === $id), $updated),
        );
    }

    /**
     * Newest first: the photo goes just below the last photo with a later
     * date, so of two photos from one day the later upload sits on top.
     * Undated photos keep wherever they were put, and undated new photos go
     * below every dated one.
     */
    private static function insertByDate(Collection $photos, array $photo): Collection
    {
        $photos = $photos->values();
        $newer = $photos->filter(fn($p) => ($p["date"] ?? "") > ($photo["date"] ?? ""))->keys()->last();
        $photos->splice($newer === null ? 0 : $newer + 1, 0, [$photo]);

        return $photos;
    }

    /** @param list<string> $ids */
    public static function reorder(array $ids): void
    {
        $position = array_flip(array_values($ids));

        self::save(
            self::all()->sortBy(
                fn($photo) => $position[$photo["id"]] ?? PHP_INT_MAX,
            ),
        );
    }

    public static function delete(string $id): void
    {
        if ($photo = self::find($id)) {
            Storage::disk("public")->delete($photo["file"]);
        }

        self::save(self::all()->reject(fn($photo) => $photo["id"] === $id));
    }

    public static function save(Collection $photos): void
    {
        Storage::disk("local")->put(
            self::MANIFEST,
            json_encode($photos->values()->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        );
    }
}
