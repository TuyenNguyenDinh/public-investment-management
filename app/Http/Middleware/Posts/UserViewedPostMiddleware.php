<?php

namespace App\Http\Middleware\Posts;

use App\Models\LogActivity;
use App\Models\LogUserViewedPost;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserViewedPostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = null;
        $slug = request()->segment(count(request()->segments()));
        $postId = Post::where('slug', $slug)->first()->id;

        if (Auth::check()) {
            $userId = $request->user()->id;
        }
        $isViewed = LogUserViewedPost::where('user_id', $userId)->where('post_id', $postId)->first();
        if ($isViewed) {
            return $next($request);
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

        $userAgent = $request->header('user-agent');
        $locale = $request->header('accept-language');

        LogUserViewedPost::create([
            'user_id' => $userId,
            'post_id' => Post::where('slug', $slug)->first()->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'locale' => $locale,
            'country' => $this->getLocationInfo($ip)['data']['country'] ?? 'unknown',
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
