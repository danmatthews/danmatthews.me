<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ManageController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireManagePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get("manage.unlocked")) {
            $token = ManageController::rememberToken();
            $cookie = (string) $request->cookie(ManageController::REMEMBER_COOKIE);

            if ($token === null || ! hash_equals($token, $cookie)) {
                return redirect()->guest(route("manage.login"));
            }

            $request->session()->put("manage.unlocked", true);
        }

        return $next($request);
    }
}
