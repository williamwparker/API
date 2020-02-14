<?php

namespace App\Http\Controllers;

use App\Parent_Student;
use App\Users_Parent;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Parent
 *
 * Parent Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class ParentController extends Controller
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
    //

    //-------------------------------------------
    // Parent_Student
    //-------------------------------------------

    /**
     * Get all parents
     * 
     * <strong>Access: </strong>superadmin<br>
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function parents()
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

        $collection = Parent_Student::all();

        return response()->json($collection, 201);
    }

    /**
     * Get Parent_Student
     * 
     * <strong>Access: </strong>parent<br>
     *
     * <strong>Body:</strong><br>
     * parent_id (required)<br>
     * student_id (optional)<br>
     * student_token (optional)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function parent_student(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'parent_id' => 'required',
        ]);

        $parentID = $request->input('parent_id');
        $studentID = $request->input('student_id');
        $studentToken = $request->input('student_token');

        $filterList = array("parent_id"=>$parentID);
        if ($studentID) 
            $filterList = array("studentId"=>$studentID);
        if ($studentToken) 
            $filterList['student_token'] = $studentToken;

        $collection = Parent_Student::where($filterList)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get parent by ID
     * 
     * <strong>Access: </strong>parent<br>
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function parent_id($id)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = Parent_Student::find($id);

        return response()->json($collection, 201);
    }

    /**
     * Delete parent by token
     * 
     * <strong>Access: </strong>parent<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function parent_delete_token($token)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $parent = Parent_Student::where('student_token', $token)->get()->first();

        $parent->delete();

        $response = [
            'status' => 'Success',
            'result' => $token
        ];
        return response()->json($response, 201);
    }
    
    /**
     * Save Parent_Student
     * 
     * <strong>Access: </strong>parent<br>
     *
     * <strong>Body:</strong><br>
     * parent_id (required)<br>
     * student_id (required)<br>
     * student_token (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function parent_save(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'parent_id' => 'required',
            'student_id' => 'required',
            'student_token' => 'required',
        ]);

        $parentId = $request->input('parent_id');
        $studentToken = $request->input('student_token');
        $studentId = $request->input('student_id');

        $parent = new Parent_Student;

        $parent->parent_id = $parentId;
        $parent->student_token = $studentToken;
        $parent->studentId = $studentId;
        
        if (!$parentId)
            $parent->parent_id = "";
        if (!$studentToken)
            $parent->student_token = "";
        if (!$studentId)
            $parent->studentId = "";

        $parent->save();

        $response = [
            'status' => 'Success',
            'result' => $parentId
        ];
        return response()->json($response, 201);
    }

    /**
     * Update parent student token 
     * 
     * <strong>Access: </strong>parent<br>
     * 
     * <strong>Body:</strong><br>
     * parent_id (required)<br>
     * student_id (required)<br>
     * student_token (required)<br>
     * 
     * <strong>Notes:</strong><br>
     * Update student_token on parent_id and student_id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function parent_student_update(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'student_token' => 'required',
            'parent_id' => 'required',
            'student_id' => 'required',
        ]);

        $token = $request->input('student_token');
        $parentId = $request->input('parent_id');
        $studentId = $request->input('student_id');

        $parent = Parent_Student::where('parent_id', $parentId)->where('studentId', $studentId)->get()->first();

        $parent->student_token = $token;
        
        $parent->save();

        $response = [
            'status' => 'Success',
            'result' => $parentId
        ];
        return response()->json($response, 201);
    }

    //-------------------------------------------
    // Users_Parent
    //-------------------------------------------

    /**
     * Save user parents authenticated email
     * 
     * <strong>Access: </strong>parent<br>
     *      
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function user_parent_save(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        $email = $user->email;

        $parent = new Users_Parent;

        $parent->email = $email;
        
        $parent->save();

        $response = [
            'status' => 'Success',
            'result' => $email
        ];
        return response()->json($response, 201);
    }

    /**
     * Get user parents for an authenticated email
     * 
     * <strong>Access: </strong>parent<br>
     *    
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function user_parent_email()
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "parent") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];

        $email = $user->email;

        $collection = Users_Parent::where('email', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }
}
