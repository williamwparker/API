<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Helper\DatabaseConnection;

use DB;

use Illuminate\Http\Request;

use JWTAuth;

use Config;

/**
 * @resource Users
 *
 * User Controller:
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.connection');

        $this->middleware('auth.permission');

        $this->middleware('jwt.auth');
    }

    /**
     * Get all users
     * 
     * <strong>Access:</strong> superadmin
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function users()
    {    
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];
        if ($user->superadmin != 1) {
            return response()->json(['status' => 'Error', 'result' => "Access denied"], 401);
        }

        //$token = JWTAuth::getPayload()->get('site');

        $collection = User::all();

        $response = [
            'status' => 'Success',
            'result' => $collection
            //'user' => $user,
            //'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * Get current authenticated user
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user()
    {    
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];

        $response = [
            'status' => 'Success',
            'result' => $user
        ];

        return response()->json($response, 201);
    }
    
    /**
     * Get any user by ID
     * 
     * <strong>Access:</strong> superadmin
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_id($id)
    {
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];
        if ($user->superadmin != 1) {
            return response()->json(['status' => 'Error', 'result' => "Access denied"], 401);
        }

        $collection = User::find($id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }

    /**
     * Get any user by cookie value
     * 
     *      
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_cookie_token($token)
    {
        $collection = User::where('cookie_token', $token)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }

    /**
     * Get any user for an email
     * 
     * <strong>Access:</strong> superadmin<br>
     * 
     * <strong>Body:</strong><br>
     * user (required)<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function user_email(Request $request)
    {
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];
        if ($user->superadmin != 1) {
            return response()->json(['status' => 'Error', 'result' => "Access denied"], 401);
        }

        $validatedData = $request->validate([
            'user' => 'required',
        ]);

        $email = $request->input('user');

        $collection = User::where('user', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }


    /**
     * Update authenticated user
     * 
     * <strong>Body:</strong><br>
     * refreshToken (optional)<br>
     * cookieToken (optional)<br>
     * authService (optional)<br>
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function user_update(Request $request)
    {
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];

        $refreshToken = $request->input('refreshToken');
        $cookieToken = $request->input('cookieToken');
        $authService = $request->input('authService');

        if ($authService) {
            if ($authService != "google" && $authService != "microsoft" && $authService != "facebook") {
                return response()->json(['status' => 'Error', 'result' => "Parameter error"], 401);
            }
        }

        if ($refreshToken)
            $user->refresh_token = $refreshToken;
        if ($cookieToken)
            $user->cookie_token = $cookieToken;
        if ($authService)
            $user->auth_service = $authService;

        $user->save();

        $response = [
            'status' => 'Success',
            'result' => $user->email
        ];

        return response()->json($response, 201);
    }

    /**
     * Delete authenticated user
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_delete()
    {
        $collection = DatabaseConnection::validateUser();

        $status = $collection['status'];
        if ($status == "Error") {
            $result = $collection['result'];
            return response()->json(['status' => 'Error', 'result' => $result], 401);
        }

        $user = $collection['result'];

        $email = $user->email;

        $user->delete();

        $response = [
            'status' => 'Success',
            'result' => $email
        ];

        return response()->json($response, 201);
    }

    /**
     * Get usertypes by site_id
     *    
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_types($site_id)
    {

        //$results = DB::select('select * from users where id = ?', array(1));
        // Community
        $results = DB::select('SELECT * FROM users_parent AS up LEFT JOIN parent_students AS ps
                                ON up.id = ps.parent_id WHERE ps.studentId Is Null');

        //error_log("COUNT");
        //error_log(count($results));

        $community = count($results);

        // Parents
        $results = DB::select('SELECT DISTINCT up.id FROM users_parent AS up JOIN parent_students AS ps
        ON up.id = ps.parent_id WHERE ps.studentId');

        $parents = count($results);

        //foreach ($results as $value) {
        //    error_log(json_encode($value));
        //}
        //error_log("COUNT");
        //error_log(count($results));

        $collection = [
            'status' => 'Success',
            'parents' => $parents,
            'community' => $community
        ];

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }
}
