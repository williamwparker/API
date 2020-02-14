<?php

namespace App\Http\Controllers;

use App\Student_Group;
use App\Student_Group_Student;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Students
 *
 * Student Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class StudentController extends Controller
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

    //-------------------------------------------
    // Students Groups
    //-------------------------------------------

    /**
     * Gets student groups
     * 
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * id (optional)<br>
     * staff_id (optional)<br>
     * name (optional)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function student_groups(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $id = $request->input('id');
        $staffId = $request->input('staff_id');
        $name = $request->input('name');

        $filterList = array();
        if ($id) 
            $filterList['ID'] = $id;        
        if ($staffId) 
            $filterList['StaffId'] = $staffId;        
        if ($name) 
            $filterList['Name'] = $name;        

        $collection = Student_Group::where($filterList)->get();

        if ($collection->isEmpty() || empty($filterList)) {
            $response = [
                'status' => 'Error',
                'result' => 'Not found'
            ];
            return response()->json($response, 201);
        }

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Update student group
     *
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * id (required)<br>
     * staff_id (optional)<br>
     * name (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function student_group_update(Request $request)
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
            'id' => 'required',
        ]);

        $id = $request->input('id');
        $staffId = $request->input('staff_id');
        $name = $request->input('name');

        $studentGroup = Student_Group::where('ID', $id)->get()->first();

        if ($studentGroup->isEmpty()) {
            $response = [
                'status' => 'Error',
                'result' => 'Not found'
            ];
            return response()->json($response, 201);
        }
               
        if ($staffId)
            $studentGroup->StaffId = $staffId;
        if ($name)            
            $studentGroup->Name = $name;
            
        if ($studentGroup->save()) {
            $response = [
                'status' => 'Success',
                'result' => $id
            ];
            return response()->json($response, 201);
        }

        $response = [
            'status' => 'Error',
            'result' => $id
        ];
        return response()->json($response, 404);
    }
    
    /**
     * Save student group
     *
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * staff_id (optional)<br>
     * name (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function student_group_save(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $staffId = $request->input('staff_id');
        $name = $request->input('name');

        $studentGroup = new Student_Group;

        $studentGroup->StaffId = $staffId;
        $studentGroup->Name = $name;
        
        if (!$staffId)
            $studentGroup->StaffId = "";
        if (!$name)
            $studentGroup->Name = "";

        if ($studentGroup->save()) {
            $response = [
                'status' => 'Success',
                'result' => $name
            ];
            return response()->json($response, 201);
        }
        $response = [
            'status' => 'Error',
            'result' => $name
        ];
        return response()->json($response, 404);
    }

    //-------------------------------------------
    // Students Groups Students
    //-------------------------------------------

    /**
     * Gets student groups students
     * 
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * group_id (optional)<br>
     * student_id (optional)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function student_group_student(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $groupId = $request->input('group_id');
        $studentId = $request->input('student_id');

        $filterList = array();
        if ($groupId) 
            $filterList['Group_ID'] = $groupId;        
        if ($studentId) 
            $filterList['Student_ID'] = $studentId;        

        $collection = Student_Group_Student::where($filterList)->get();

        if ($collection->isEmpty() || empty($filterList)) {
            $response = [
                'status' => 'Error',
                'result' => 'Not found'
            ];
            return response()->json($response, 201);
        }

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Update students groups students
     *
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * id (required)<br>
     * staff_id (optional)<br>
     * group_id (optional)<br>
     * student_id (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function student_group_student_update(Request $request)
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
            'id' => 'required',
        ]);

        $id = $request->input('id');
        $staffId = $request->input('staff_id');
        $groupId = $request->input('group_id');
        $studentId = $request->input('student_id');

        $studentGroup = Student_Group_Student::find($id);

        if ($studentGroup->isEmpty()) {
            $response = [
                'status' => 'Error',
                'result' => 'Not found'
            ];
            return response()->json($response, 201);
        }

        if ($staffId)
            $studentGroup->StaffId = $staffId;
        if ($groupId)            
            $studentGroup->Group_ID = $groupId;
        if ($studentId)            
            $studentGroup->Student_ID = $studentId;
        
        if ($studentGroup->save()) {
            $response = [
                'status' => 'Success',
                'result' => $id
            ];
            return response()->json($response, 201);
        }

        $response = [
            'status' => "Error",
            'result' => $id
        ];
        return response()->json($response, 404);
    }
        
    /**
     * Save student group student
     *
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * staff_id (optional)<br>
     * group_id (optional)<br>
     * student_id (optional)<br>
     *      
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function student_group_student_save(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $staffId = $request->input('staff_id');
        $groupId = $request->input('group_id');
        $studentId = $request->input('student_id');

        $studentGroup = new Student_Group_Student;

        $studentGroup->StaffId = $staffId;
        $studentGroup->Group_ID = $groupId;
        $studentGroup->Student_ID = $studentId;
        
        if (!$staffId)
            $studentGroup->StaffId = "";
        if (!$groupId)
            $studentGroup->Group_ID = "";
        if (!$studentId)
            $studentGroup->Student_ID = "";

        if ($studentGroup->save()) {
            $response = [
                'status' => 'Success',
                'result' => ""
            ];
            return response()->json($response, 201);
        }
    
        $response = [
            'status' => "Error",
            'result' => ""
        ];
        return response()->json($response, 404);
    }

    /**
     * Delete student group student
     * 
     * <strong>Access: </strong>staff<br>
     *
     * <strong>Query string:</strong><br>
     * groupId (optional)<br>
     * staffId (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function student_delete(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $groupId = $request->query('groupId');
        $staffId = $request->query('staffId');

        $filterList = array();
        if ($groupId) 
            $filterList['Group_ID'] = $groupId;        
        if ($staffId) 
            $filterList['StaffId'] = $staffId;        
            
        $collection = Student_Group_Student::where($filterList)->get()->first();

        if (!$collection || empty($filterList)) {
            $response = [
                'status' => 'Error',
                'result' => 'Not found'
            ];
            return response()->json($response, 201);
        }

        $collection->delete();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }


     /**
     * Get student picture by ID
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function student_picture_id(Request $request)
    {
    }

    //TODO
}
