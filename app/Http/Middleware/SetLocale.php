<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Controllers only declare the parameters they actually use (e.g.
        // `show(Page $page)`), but Laravel's ControllerDispatcher passes
        // route parameters to the action positionally via array_values().
        // Leaving `locale` in the route's parameter bag would shift every
        // subsequent positional argument by one, so drop it once consumed.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
