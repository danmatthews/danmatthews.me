<?php

namespace App\Http\Controllers;

use App\MiniPaintings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The password-protected management area at /manage. Each section of the site
 * that can be managed is listed on the dashboard; add new ones to index().
 */
class ManageController extends Controller
{
    /** Keeps a device unlocked between sessions, so a home-screen app stays signed in. */
    public const REMEMBER_COOKIE = "manage_remember";

    private const REMEMBER_MINUTES = 60 * 24 * 90;

    public function login(Request $request): Response|RedirectResponse
    {
        if ($request->session()->get("manage.unlocked")) {
            return redirect()->route("manage");
        }

        return Inertia::render("Manage/Login");
    }

    public function unlock(Request $request): RedirectResponse
    {
        $password = (string) config("services.manage.password");

        if ($password === "" || ! hash_equals($password, (string) $request->input("password"))) {
            throw ValidationException::withMessages(["password" => "That password isn't right."]);
        }

        $request->session()->regenerate();
        $request->session()->put("manage.unlocked", true);
        Cookie::queue(self::REMEMBER_COOKIE, self::rememberToken(), self::REMEMBER_MINUTES);

        return redirect()->intended(route("manage"));
    }

    public function lock(Request $request): RedirectResponse
    {
        $request->session()->forget("manage.unlocked");
        Cookie::queue(Cookie::forget(self::REMEMBER_COOKIE));

        return redirect()->route("manage.login");
    }

    public function index(): Response
    {
        $photos = MiniPaintings::all()->count();

        return Inertia::render("Manage/Index", [
            "sections" => [
                [
                    "title" => "Miniature painting",
                    "description" => "Upload, caption and reorder gallery photos.",
                    "summary" => $photos . ($photos === 1 ? " photo" : " photos"),
                    "url" => route("manage.minipainting"),
                ],
            ],
        ]);
    }

    /**
     * The remember cookie's value. It derives from the password, so changing
     * the password signs out every remembered device. Null while no password is set.
     */
    public static function rememberToken(): ?string
    {
        $password = (string) config("services.manage.password");

        return $password === "" ? null : hash_hmac("sha256", $password, config("app.key"));
    }
}
