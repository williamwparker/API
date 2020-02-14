<?php

namespace App\Http\Controllers;

use App\Curriculum_Assessments;
use App\Curriculum_Course;
use App\Curriculum_Lesson;
use App\Curriculum_Libraries;
use App\Curriculum_Resources;
use App\Curriculum_Standards;
use App\Curriculum_Unit;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Curriculum
 *
 * Curriculum Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class CurriculumController extends Controller
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
     * Get curriculum course by ID
     *
     * <strong>Access: </strong>staff<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function curriculum_course_id($id)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = Curriculum_Course::find($id);

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];

        return response()->json($response, 201);
    }



    
}
