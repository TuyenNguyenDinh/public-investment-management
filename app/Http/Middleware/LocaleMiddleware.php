<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Locale is enabled and allowed to be change
        $locale = session()->get('locale') ?? app()->getLocale();
        if (session()->has('locale') && in_array($locale, ['en', 'vn'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
