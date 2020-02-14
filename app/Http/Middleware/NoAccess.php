<?php

namespace App\Http\Middleware;

use Closure;

class NoAccess
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
        $response = [
            'status' => 'Error',
            'result' => 'Access not allowed'
        ];
        return response()->json($response, 404);

        return $next($request);
    }
}
