<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireMiniPaintingPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get("minipainting.unlocked")) {
            return redirect()->route("minipainting.login");
        }

        return $next($request);
    }
}
