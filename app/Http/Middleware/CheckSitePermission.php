<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;

class CheckSitePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype == "") {
            $response = [
                'status' => 'Error',
                'result' => 'Invalid credentials'
            ];
            return response()->json($response, 404);
        }

        return $next($request);
    }
}
