<?php

namespace App\Http\Controllers;

use App\MiniPaintings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MiniPaintingController extends Controller
{
    /** Every photo with its image URL, read by `php artisan minipainting:pull`. */
    public function json(): JsonResponse
    {
        return response()->json([
            "photos" => MiniPaintings::all()->map(
                fn($photo) => [...$photo, "url" => MiniPaintings::url($photo)],
            ),
        ]);
    }

    public function manage(): Response
    {
        return Inertia::render("Manage/MiniPainting", [
            "photos" => MiniPaintings::all()->map(
                fn($photo) => [...$photo, "src" => MiniPaintings::url($photo)],
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "image" => ["required", "image", "mimes:jpeg,png,webp", "max:10240"],
            "caption" => ["nullable", "string", "max:500"],
            "date" => ["nullable", "date_format:Y-m-d"],
        ]);

        MiniPaintings::add(
            $request->file("image"),
            (string) $request->input("caption"),
            $request->input("date") ?: now()->toDateString(),
        );

        return back();
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            "caption" => ["nullable", "string", "max:500"],
            "date" => ["nullable", "date_format:Y-m-d"],
        ]);

        MiniPaintings::update($id, (string) $request->input("caption"), $request->input("date") ?: null);

        return back();
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate(["ids" => ["required", "array"], "ids.*" => ["string"]]);

        MiniPaintings::reorder($request->input("ids"));

        return back();
    }

    public function destroy(string $id): RedirectResponse
    {
        MiniPaintings::delete($id);

        return back();
    }
}
