<?php

namespace App\Http\Controllers\Concerns;

trait SharesPostMeta
{
    protected function sharePostMeta(
        string $title,
        string $excerpt,
        string $url,
        ?string $ogImage = null,
        ?string $type = "article",
    ): void {
        $structuredData = json_encode([
            "@context" => "https://schema.org",
            "@type" => $type === "article" ? "Article" : "WebPage",
            "headline" => $title,
            "description" => $excerpt,
            "url" => $url,
            "image" => $ogImage,
            "author" => [
                "@type" => "Person",
                "name" => config("app.name"),
            ],
        ]);

        view()->share("postMeta", [
            "title" => $title,
            "excerpt" => $excerpt,
            "site_name" => config("app.name"),
            "og_image" => $ogImage,
            "url" => $url,
            "structured_data" => $structuredData,
        ]);
    }
}
