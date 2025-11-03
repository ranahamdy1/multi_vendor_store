<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocal
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = LaravelLocalization::setLocale();
        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);
        return $next($request);
    }
}
