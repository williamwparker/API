<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\DatabaseConnection;

use Closure;

use JWTAuth;

class SetDatabase
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
        //error_log(JWTAuth::parseToken()->getPayload());

        $site = JWTAuth::parseToken()->getPayload()->get('site');
        
        //error_log(JWTAuth::parseToken()->getPayload()->get('site'));

        //error_log(JWTAuth::parseToken()->getPayload()->get('domain'));

        //error_log($site);


        if ($site === null) {
                $response = [
                'status' => 'Error',
                'result' => 'Invalid credentials 4'
            ];
            return response()->json($response, 404);
        }

        DatabaseConnection::setConnection($site);

        return $next($request);
    }
}
