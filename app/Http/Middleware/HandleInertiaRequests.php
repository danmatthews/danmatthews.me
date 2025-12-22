<?php

namespace App\Http\Middleware;

use App\Data\NavigationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup
     */
    protected $rootView = "app";

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        $navigation = collect(config("site.navigation"))
            ->map(
                fn(NavigationItem $item) => [
                    "title" => $item->title,
                    "url" => url($item->url),
                    "active" => is_callable($item->isActive)
                        ? ($item->isActive)($request)
                        : $request->is(trim($item->url, "/")),
                ],
            )
            ->values();

        return [
            ...parent::share($request),
            "appName" => config("app.name"),
            "navigation" => $navigation,
            "socialLinks" => Arr::wrap(config("site.social-links")),
            "canonical" => $request->fullUrl(),
        ];
    }
}
