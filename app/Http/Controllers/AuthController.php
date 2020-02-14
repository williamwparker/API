<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Helper\DatabaseConnection;

use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;

/**
 * @resource Authentication
 *
 * Authentication Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class AuthController extends Controller
{
    public function store(Request $request)
    {

    }

    public function signin(Request $request) 
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'cookie_token' => 'required',
            'site' => 'required',
        ]);

        $email = $request->input('email');
        $cookie_token = $request->input('cookie_token');
        $site = $request->input('site');

        DatabaseConnection::setConnection($site);

        //GAFE_DOMAIN
        $domain = DatabaseConnection::$site_domain;

        if ($domain == null){
            $response = ['status' => 'Error', 'result' => 'Invalid site'];
            return response()->json($response, 401);                        
        }

        $token = "";
        try {

            //error_log($email + " " + $site + " " + $cookie_token);
            //error_log("sign in".$cookie_token);

            $collection = User::where([['email', $email], ['siteID', $site],
            ['cookie_token', $cookie_token]]);

            if (!$collection) {
                $response = ['status' => 'Error', 'result' => 'Invalid credentials1'];
                return response()->json($response, 401);                
            }

            $user = new User;

            if ($collection->count()==0) {

                //TODO: Check is user parent/community
                //and allow

                $response = ['status' => 'Error', 'result' => 'Invalid credentials2'];
                return response()->json($response, 401);                
            }
            else {

                $user->email = $collection->first()->email;
                $user->id = $collection->first()->id;
    
                $usertype = DatabaseConnection::getUserType($domain, $user->email);
    
                $customClaims = ['site' => $site, 'domain' => $domain, 'usertype' => $usertype];
                
                if (!$token = JWTAuth::fromUser($user, $customClaims)) {
                    $response = ['status' => 'Error', 'result' => 'Invalid credentials3'];
                    return response()->json($response, 401);                
                }
                /*
                if (! $token = JWTAuth::attempt($user)) {
    
                    return response()->json(['msg' => 'Invalid credentials'], 401);
                }
                */
            }


        } catch(JWTException $e) {
            $response = ['status' => 'Error', 'result' => 'Could not create token'];
            return response()->json($response, 500);                        
        } 
        
        $response = [
            'status' => 'Success',
            'result' => $token
        ];
        return response()->json($response, 201);
    }
}
