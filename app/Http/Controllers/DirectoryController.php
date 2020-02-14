<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Directory_Setting;

use Illuminate\Http\Request;

/**
 * @resource Directory
 *
 * Directory Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class DirectoryController extends Controller
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

    private function permissions($email) {

        $access = 0;

        $directory = Directory::where('user', $email)->where('url', $url)->where('admin',1)->where('archived','')->get()->count();
        if ($directory) $access = 1;

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        if ($user->superadmin == 1) $access = 1;

        $directory = Directory::where('user', $email)->where('url', $url)->where('admin',2)->where('archived','')->get()->count();
        if ($directory) $access = 2;

        return $access;
    }

    /**
     * Gets all directories
     *
     * <strong>Access: </strong>superadmin<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function directories()
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {
            return response()->json(['status' => 'Error', 'result' => "Access denied"], 401);
        }
        
        $collection = Directory::all();

        return response()->json($collection, 201);
    }

    /**
     * Gets directory by ID
     *
     * <strong>Access: </strong>superadmin<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function directory_id($id)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        if ($user->superadmin != 1) {
            return response()->json(['status' => 'Error', 'result' => "Access denied"], 401);
        }

        $collection = Directory::find($id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Gets directory for user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function directory_email(Request $request)
    {
        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        $email = $user->email;

        $collection = Directory::where('email', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }

    //TODO
    // Save directory
    // Directory Discipline including delete


    /**
     * Gets directory settings by dropdown ID
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function directory_settings_dropdown($id)
    {
        $collection = Directory_Setting::where('dropdownID', $id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Gets all directory settings
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function directory_settings()
    {  
        $collection = Directory_Setting::all();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Update directory settings: Input: id, fields to be udpated
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function directory_setting_update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $id = $request->input('id');
        $dropdownID = $request->input('dropdownID');
        $options = $request->input('options');

        $directorySetting = Directory_Setting::find($id);

        if ($dropdownID)
            $directorySetting->dropdownID = $dropdownID;
        if ($options)            
            $directorySetting->options = $options;
        
        $directorySetting->save();

        $response = [
            'status' => 'Success',
            'result' => $id
        ];
        return response()->json($response, 201);
    }

    /**
     * Save directory settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function directory_settings_save(Request $request)
    {
        $dropdownID = $request->input('dropdownID');
        $options = $request->input('options');

        $directorySetting = new Directory_Setting;

        $directorySetting->dropdownID = $dropdownID;
        $directorySetting->options = $options;
        
        if (!$dropdownID)
            $directorySetting->dropdownID = "";
        if (!$options)
            $directorySetting->options = "";

        $directorySetting->save();

        $response = [
            'status' => 'Success',
            'result' => $dropdownID
        ];
        return response()->json($response, 201);
    }
}
