<?php

namespace App\Http\Controllers;

use App\Conduct_Colors;
use App\Conduct_Consequences;
use App\Conduct_Discipline_Consequences;
use App\Conduct_Discipline;
use App\Conduct_Log;
use App\Conduct_Offenses;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Conduct
 *
 * Conduct Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class ConductController extends Controller
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
     * Gets conduct colors for a student and course group
     * 
     * <strong>Access: </strong>staff<br> 
     *
     * <strong>Body:</strong><br>
     * studentID (required)<br>
     * courseGroup (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function conduct_colors(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'studentID' => 'required',
            'courseGroup' => 'required',
        ]);

        $studentId = $request->input('studentID');
        $courseGroup = $request->input('courseGroup');

        $collection = Conduct_Colors::where('StudentID', $studentId)->where('CourseGroup',$courseGroup)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection,
        ];

        return response()->json($response, 201);
    }

}
