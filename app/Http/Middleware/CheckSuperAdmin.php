<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\DatabaseConnection;

use Closure;

class CheckSuperAdmin
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
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 404);
        }

        return $next($request);
    }
}
