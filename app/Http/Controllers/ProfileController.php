<?php

namespace App\Http\Controllers;

use App\Profile;

use Illuminate\Http\Request;

use JWTAuth;
use Auth;


/**
 * @resource Profiles
 *
 * Profile Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class ProfileController extends Controller
{
    public function __construct()
    {
        // User has a site id in token
        // Sets site_domain (gafe) and site_id in DatabaseConnection
        // calls SetDatabase
        $this->middleware('auth.connection');

        // User has a user type in token
        // calls CheckSitePermission
        $this->middleware('auth.permission');

        $this->middleware('auth.noaccess');

        // Checks to see if token is expired or invalid
        $this->middleware('jwt.auth');
    }

    /**
     * Gets all profiles
     * 
     * <strong>Access: </strong>superadmin<br>
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function profiles()
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
        
        $collection = Profile::all();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Gets profile by ID
     * 
     * <strong>Access: </strong>superadmin<br>
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function profile_id($id)
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

        $collection = Profile::find($id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Gets profile for authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function user_profile()
    {
        $user = Auth::User();
        $email = $user->email;

        $collection = Profile::where('email', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }

    /**
     * Update profile
     * 
     * <strong>Body:</strong><br>
     * streams (optional)<br>
     * apps_order (optional)<br>
     * work_calendar (optional)<br>
     * widgets_order (optional)<br>
     * widgets_hidden (optional)<br>
     * widgets_open (optional)<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function profile_user_update(Request $request)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        $email = $user->email;

        $profile = Profile::where('email', $email)->get()->first();

        $streams = $request->input('streams');
        $apps_order = $request->input('apps_order');
        $work_calendar = $request->input('work_calendar');
        $widgets_order = $request->input('widgets_order');
        $widgets_hidden = $request->input('widgets_hidden');
        $widgets_open = $request->input('widgets_open');

        if ($streams)
            $profile->streams = $streams;
        if ($apps_order)
            $profile->apps_order = $apps_order;
        if ($work_calendar)
            $profile->work_calendar = $work_calendar;
        if ($widgets_order)
            $profile->widgets_order = $widgets_order;
        if ($widgets_hidden)
            $profile->widgets_hidden = $widgets_hidden;
        if ($widgets_open)
            $profile->widgets_open = $widgets_open;
                    
        $profile->save();

        $response = [
            'status' => 'Success',
            'result' => $email
        ];
        return response()->json($response, 201);
    }

    /**
     * Save user profile
     * 
     * <strong>Body:</strong><br>
     * startup (optional)<br>
     * streams (optional)<br>
     * apps_order (optional)<br>
     * work_calendar (optional)<br>
     * widgets_order (optional)<br>
     * widgets_hidden (optional)<br>
     * widgets_open (optional)<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function profile(Request $request)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        $email = $user->email;

        $startup = $request->input('startup');
        $streams = $request->input('streams');
        $apps_order = $request->input('apps_order');
        $work_calendar = $request->input('work_calendar');
        $widgets_order = $request->input('widgets_order');
        $widgets_hidden = $request->input('widgets_hidden');
        $widgets_open = $request->input('widgets_open');

        $profile = new Profile;

        $profile->email = $email;
        $profile->startup = $startup;
        $profile->streams = $streams;
        $profile->apps_order = $apps_order;
        $profile->work_calendar = $work_calendar;
        $profile->widgets_order = $widgets_order;
        $profile->widgets_hidden = $widgets_hidden;
        $profile->widgets_open = $widgets_open;
        
        if (!$startup )
            $profile->startup  = 0;
        if (!$streams)
            $profile->streams = "";
        if (!$apps_order)
            $profile->apps_order = "";
        if (!$work_calendar)
            $profile->work_calendar = "";
        if (!$widgets_order)
            $profile->widgets_order = "";
        if (!$widgets_hidden)    
            $profile->widgets_hidden = "";
        if (!$widgets_open)
            $profile->widgets_open = "";

        $profile->save();

        $response = [
            'status' => 'Success',
            'result' => $email
        ];
        return response()->json($response, 201);
    }
}
