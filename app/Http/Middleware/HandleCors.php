<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');

        $allowedOrigins = [
            env('FRONTEND_URL', 'http://localhost:5173'),
        ];

        $response = $next($request);

        if ($origin && in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Range, X-Total-Count');
        }

        if ($request->isMethod('OPTIONS')) {
            return new Response('', 204, $response->headers->all());
        }

        return $response;
    }
}
