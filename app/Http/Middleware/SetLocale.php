<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');

        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
