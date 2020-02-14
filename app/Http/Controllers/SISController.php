<?php

namespace App\Http\Controllers;

use App\Abre_AD;
use App\Abre_AIRSubsore_Categories;
use App\Abre_AIRData;
use App\Abre_Attendance;
use App\Abre_ParentContacts;
use App\Abre_Staff;
use App\Abre_StudentACT;
use App\Abre_StudentAP;
use App\Abre_StudentAssessments;
use App\Abre_Students;
use App\Abre_StudentSchedules;

use Illuminate\Http\Request;

/**
 * @resource SIS
 *
 * SIS Controller:
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class SISController extends Controller
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
     * Get Abre_AD for authenticated user
     *
     * <strong>Access: </strong>staff<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_ad()
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        $email = $user->email;

        $collection = Abre_AD::where('email', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_AIRData for LocalID
     *
     * <strong>Access: </strong>staff<br>
     * 
     * <strong>Body:</strong><br>
     * student_id (required)<br>
     * 
     * * <strong>Notes:</strong><br>
     * Ordered by TestName<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_airdata(Request $request)
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
            'student_id' => 'required',
        ]);

        $localID = $request->input('student_id');

        $collection = Abre_AIRData::where('LocalID', $localID)->orderBy('TestName')->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_AIRSubscore_Categories for TestName
     *
     * <strong>Access: </strong>staff<br>
     *      
     * <strong>Body:</strong><br>
     * test_name (required)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_airsubscore_categories(Request $request)
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
            'test_name' => 'required',
        ]);

        $testName = $request->input('test_name');

        $collection = Abre_AIRSubsore_Categories::where('TestName', $testName)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_Attendance for StudentId
     *
     * <strong>Access: </strong>parent<br>
     *      
     * <strong>Body:</strong><br>
     * student_id (required)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_attendance(Request $request)
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
            'student_id' => 'required',
        ]);

        $studentID = $request->input('student_id');

        $collection = Abre_Attendance::where('StudentID', $studentID)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_ParentContacts for Email1
     *
     * <strong>Access: </strong>parent<br>        
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_parent_contacts(Request $request)
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

        $collection = Abre_ParentContacts::where('Email1', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_Staff for Email1
     *
     * <strong>Access: </strong>staff<br>      
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_staff(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        $email = $user->email;

        $collection = Abre_Staff::where('Email1', $email)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

     /**
     * Get Abre_StaffSchedules for StaffID
     *
     * <strong>Access: </strong>staff<br>
     *      
     * <strong>Body:</strong><br>
     * staff_id (required)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_staff_schedules(Request $request)
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
            'staff_id' => 'required',
        ]);

        $staffID = $request->input('staff_id');

        $collection = Abre_StaffSchedules::where('StaffID', $staffID)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_StudentACT
     *
     * <strong>Access: </strong>parent<br>
     *      
     * <strong>Body:</strong><br>
     * student_id (required)<br>
     * testing_date (optional)<br>
     * category_name (optional)<br>
     * category_equal (optional, boolean, equal to category if true, else not equal to category)<br>
     * order_by (optional, field to order by)<br>
     * order_desc (optional, boolean: DESC if true)<br>
     * 
     * <strong>Notes:</strong><br>
     * If category name included, it can be specified to be equal or not equal to value.<br>
     * "order_by" is optional field to order by.
     *       
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_student_ACT(Request $request)
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
            'student_id' => 'required',
        ]);

        $studentID = $request->input('student_id');
        $testingDate = $request->input('testing_date');
        $categoryName = $request->input('category_name');
        $categoryEqual = $request->input('category_equal');
        $orderBy = $request->input('order_by');
        $orderDesc = $request->input('order_desc');

        $direction = "Asc";
        if ($orderDesc) $direction = "Desc";
        $comp = "<>";
        if ($categoryEqual) $comp = "=";

        $filterList = array("StudentID"=>$studentID);
        if ($testingDate) 
            $filterList['TestingDate'] = $testingDate;
        
        $collection = Abre_StudentACT::where($filterList)->where('CategoryName', $comp, $categoryName)->orderBy($orderBy, $direction)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_StudentAP
     * 
     * <strong>Access: </strong>parent<br>     
     *
     * <strong>Body:</strong><br>
     * student_id (required)<br>
     * 
     * <strong>Notes:</strong><br>
     * Ordered by APExamSubject<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_student_AP(Request $request)
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
            'student_id' => 'required',
        ]);

        $studentID = $request->input('student_id');
        
        $collection = Abre_StudentAP::where('StudentID', $studentID)->orderBy('APExamSubject')->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_StudentAssessments
     *
     *       
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_student_assessments(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        //TODO

        $collection = "";

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_Students
     * 
     * <strong>Access: </strong>staff<br>     
     *
     * <strong>Body:</strong><br>
     * student_id (required)<br>
     * 
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_students(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $studentID = $request->input('student_id');
        
        $collection = Abre_Students::where('StudentID', $studentID)->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

    /**
     * Get Abre_StudentSchedules
     * 
     * <strong>Access: </strong>staff<br>     
     *
     * <strong>Body:</strong><br>
     * student_id (optional)<br>
     * section_code (optional)<br>
     * staff_id (optional)<br>
     * course_code (optional)<br>
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function abre_student_schedules(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        //TODO

        if ($usertype != "staff") {
            $response = [
                'status' => 'Error',
                'result' => 'Permission error'
            ];
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'student_id' => 'required',
            'section_code' => 'required',
            'staff_id' => 'required',
            'course_code' => 'required',
        ]);

        $studentID = $request->input('student_id');
        $sectionCode = $request->input('section_code');
        $staffID = $request->input('staff_id');
        $courseCode = $request->input('course_code');
        if ($studentID) 
            $filterList['StudentID'] = $studentID;
        if ($sectionCode) 
            $filterList['SectionCode'] = $sectionCode;
        if ($staffID) 
            $filterList['StaffId'] = $staffID;
        if ($courseCode) 
            $filterList['CourseCode'] = $courseCode;
        
        $collection = Abre_Students::where('$filterList')->get();

        $response = [
            'status' => 'Success',
            'result' => $collection
        ];
        return response()->json($response, 201);
    }

}
