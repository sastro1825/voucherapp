<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class SecurityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('withSecurityHeaders', function () {
            $response = $this;

            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                   "style-src 'self' 'unsafe-inline'; " .
                   "img-src 'self' data:; " .
                   "font-src 'self' data:; " .
                   "connect-src 'self'; " .
                   "frame-ancestors 'self';";

            return $response
                ->header('Content-Security-Policy', $csp)
                ->header('X-Frame-Options', 'DENY')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Powered-By', null);
        });

        \Illuminate\Routing\ResponseFactory::macro('make', function ($content = '', $status = 200, array $headers = []) {
            $response = new Response($content, $status, $headers);
            return $response->withSecurityHeaders();
        });
    }
}