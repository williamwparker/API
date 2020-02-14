<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\DatabaseConnection;

use App\Setting;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Settings
 *
 * Setting Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class SettingController extends Controller
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
     * Get settings
     *
     * <strong>Access: </strong>superadmin<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function settings()
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $collection = Setting::all()->take(1);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Gets setting by ID
     *
     * <strong>Access: </strong>superadmin<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function setting_id($id)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $collection = Setting::find($id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Update settings
     * 
     * <strong>Access: </strong>superadmin<br>
     * 
     * <strong>Body:</strong><br>
     * id (required)<br>
     * options (optional)<br>
     * parentaccess (optional)<br>
     * integrations (optional)<br>
     * authentication (optional)<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function setting_update(Request $request)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $id = $request->input('id');
        $options = $request->input('options');
        $parentaccess = $request->input('parentaccess');
        $integrations = $request->input('integrations');
        $authentication = $request->input('authentication');

        $setting = Setting::find($id);

        if ($options)
            $setting->options = $options;
        if ($parentaccess)            
            $setting->parentaccess = $parentaccess;
        if ($integrations)            
            $setting->integrations = $integrations;
        if ($authentication)            
            $setting->authentication = $authentication;
        
        $setting->save();

        $response = [
            'status' => 'Success',
            'result' => $id
        ];
        return response()->json($response, 201);
    }

    /**
     * Save settings
     *
     * <strong>Access: </strong>superadmin<br>
     * 
     * <strong>Body:</strong><br>
     * options (optional)<br>
     * parentaccess (optional)<br>
     * integrations (optional)<br>
     * authentication (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function setting_save(Request $request)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $options = $request->input('options');
        $parentaccess = $request->input('parentaccess');
        $integrations = $request->input('integrations');
        $authentication = $request->input('authentication');

        $setting = new Setting;

        $setting->options = $options;
        $setting->parentaccess = $parentaccess;
        $setting->integrations = $integrations;
        $setting->authentication = $authentication;
        
        if (!$options )
            $setting->options = "";
        if (!$parentaccess)
            $setting->parentaccess = "";
        if (!$integrations)
            $setting->integrations = "";
        if (!$authentication)
            $setting->authentication = "";

        $setting->save();

        $response = [
            'status' => 'Success',
            'result' => $options
        ];
        return response()->json($response, 201);
    }
}
