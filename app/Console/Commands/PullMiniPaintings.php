<?php

namespace App\Console\Commands;

use App\MiniPaintings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PullMiniPaintings extends Command
{
    protected $signature = "minipainting:pull
        {--from= : Site to copy from (defaults to services.minipainting.source)}
        {--prune : Delete local photos that are no longer on that site}";

    protected $description = "Copy the /minipainting photos and captions from the live site to this one";

    public function handle(): int
    {
        $from = rtrim($this->option("from") ?: config("services.minipainting.source"), "/");
        $remote = collect(Http::acceptJson()->get("{$from}/minipainting.json")->throw()->json("photos"));
        $disk = Storage::disk("public");

        $downloaded = 0;
        foreach ($remote as $photo) {
            // Only ever write inside the minipainting folder, whatever the remote says.
            if (! preg_match('#^' . MiniPaintings::FOLDER . '/[a-z0-9]+\.(jpe?g|png|webp)$#', $photo["file"])) {
                $this->fail("Unexpected file path from {$from}: {$photo["file"]}");
            }

            if ($disk->exists($photo["file"])) {
                continue;
            }

            $disk->put($photo["file"], Http::get($photo["url"])->throw()->body());
            $downloaded++;
        }

        $local = MiniPaintings::all();
        $gone = $local->pluck("file")->diff($remote->pluck("file"));

        // Keep photos only uploaded locally unless --prune, after the remote ones.
        $photos = $remote->map(fn($photo) => collect($photo)->except("url")->all());
        if ($this->option("prune")) {
            $disk->delete($gone->all());
        } else {
            $photos = $photos->concat($local->whereIn("file", $gone));
        }

        MiniPaintings::save($photos);

        $this->info("Copied {$downloaded} new photos from {$from}; {$photos->count()} photos in total.");
        if ($gone->isNotEmpty()) {
            $this->line($this->option("prune")
                ? "Deleted {$gone->count()} local photos that aren't on {$from}."
                : "Kept {$gone->count()} local photos that aren't on {$from} (use --prune to delete them).");
        }

        return self::SUCCESS;
    }
}
