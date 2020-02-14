<?php

namespace App\Http\Controllers;

use App\App;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Apps
 *
 * Apps Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class AppsController extends Controller
{
    //

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
     * Gets all apps ordered by sort column
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function apps()
    {
        
        
    }

    
}
