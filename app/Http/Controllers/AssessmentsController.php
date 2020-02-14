<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Assessment_Questions;
use App\Assessment_Resuls;
use App\Assessment_Scores;
use App\Assessment_Settings;
use App\Assessment_Standards;
use App\Assessment_Status;

use Illuminate\Http\Request;

use JWTAuth;
use Auth;

/**
 * @resource Assessments
 *
 * Assessments Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class AssessmentsController extends Controller
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
     * Gets assessments for authenticated user
     *
     * <strong>Body:</strong><br>
     * verified (optional)<br>
     * id (optional)<br>
     * editors (optional)<br>
     * shared (optional)<br>
     * 
     * <strong>Notes:</strong><br>
     * Ordered by Title<br>
     *  
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function assessments_email(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $user = Auth::User();
        $email = $user->email;

        $verified = $request->query('verified');
        $id = $request->query('id');
        $editors  = $request->query('editors');
        $shared  = $request->query('shared');

        $filterList = array();
        $filterList['Owner'] = $email;
        if ($verified) 
            $filterList['Verified'] = $verified;        
        if ($id) 
            $filterList['ID'] = $id;
            
        // Check if they passed in an editor but use authenticated email for editor not what was passed in
        if ($editors)
            $editors = $email;

        if ($editors && $shared)    

            $collection = Assessment::
                where('ID', $id)
                ->where(function ($query) {
                    $query->where('Owner', $email)
                      ->orWhere('Editors', 'like', '%'.$editors.'%')
                      ->orWhere('Shared', $shared);
            })
            ->orderBy('Title')->get();
        else
            $collection = Assessment::where($filterList)->orderBy('Title')->get();

        $response = [
            'msg' => 'Success',
            'assessments' => $collection
        ];

        return response()->json($response, 201);
    }

}
