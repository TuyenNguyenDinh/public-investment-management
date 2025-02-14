<?php

namespace App\Http\Middleware\Loggers;

use App\Models\LogActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userType = 'Guest';
        $userId = $organizationId = null;

        if (Auth::check()) {
            $userType = 'Registered';
            $userId = $request->user()->id;
            $organizationId = session('organization_id');
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $request->ip();
        }

        $route = $request->fullUrl();
        $userAgent = $request->header('user-agent');
        $method = $request->method();
        $locale = $request->header('accept-language');

        $verb = match (strtolower($method)) {
            'post' => 'Created',
            'patch', 'put' => 'Edited',
            'delete' => 'Deleted',
            default => 'Accessed',
        };
        $description = $verb . ' ' . ($request->path() !== '/' ? $request->path() : 'dashboards');

        if ($request->path() === 'logout') {
            $description = 'Logged out';
        }
        LogActivity::create([
            'description' => $description,
            'user_type' => $userType,
            'user_id' => $userId,
            'organization_id' => $organizationId,
            'route' => $route,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'locale' => $locale,
            'country' => $this->getLocationInfo($ip)['data']['country'] ?? 'unknown',
            'method_type' => $method,
        ]);

        return $next($request);
    }

    /**
     * Get IP info with ipinfo.io
     * 
     * @param $ip
     * @return array|string[]
     */
    public function getLocationInfo($ip): array
    {
        $location = [
            'status' => 'error',
            'message' => 'Unable to retrieve location data.',
        ];

        $response = Http::get("http://ipinfo.io/$ip/json");

        if ($response->successful()) {
            $location = [
                'status' => 'success',
                'data' => $response->json(),
            ];
        }

        return $location;
    }
}
