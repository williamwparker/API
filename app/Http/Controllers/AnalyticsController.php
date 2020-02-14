<?php

namespace App\Http\Controllers;

use App\User;
use App\Abre_Tenant_Map;
use App\Conduct_Discipline;
use App\Abre_Attendance;
use App\Http\Controllers\Helper\DatabaseConnection;

use DB;

use Illuminate\Http\Request;

use JWTAuth;

use Config;

/**
 * @resource Analytics
 *
 * Analytics Controller:
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class AnalyticsController extends Controller
{
    public function __construct(){

        // User has a site id in token
        // Sets site_domain (gafe) and site_id in DatabaseConnection
        // calls SetDatabase
        $this->middleware('auth.connection');

        // User has a user type in token
        // calls CheckSitePermission
        $this->middleware('auth.permission');

        // Must be super admin
        $this->middleware('auth.superadmin');

        // Checks to see if token is expired or invalid
        $this->middleware('jwt.auth');

        //$this->middleware('jwt.refresh');
    }

    /**
     * Get subset of Attendance
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_attendance(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      if ($site_id == -1){
        $select = 'SELECT StudentID as sid, AbsenceReasonCode as codes, siteID
        FROM cAbre_Attendance LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));  
      }
      else{
        $select = 'SELECT StudentID as sid, AbsenceReasonCode as codes, siteID
        FROM Abre_Attendance WHERE siteID = ? 
        LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));         
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];

      return response()->json($response, 201);
    }

    /**
     * Get subset of Conduct
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_conduct(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      if ($site_id == -1){
        $select = 'SELECT StudentID as sid, Offence_Codes as codes, siteID
        FROM conduct_discipline LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));  
      }
      else{
        $select = 'SELECT StudentID as sid, Offence_Codes as codes, siteID
        FROM conduct_discipline WHERE siteID = ? 
        LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));         
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];

      return response()->json($response, 201);
    }

    /**
     * Get subset of Unweighted GPAs
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_unweightedgpa(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      if ($site_id == -1){
        $select = 'SELECT StudentNumber as sid, GPA as gpa, SchoolBuilding as building, siteID
        FROM Abre_StudentUnweightedGPA LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));  
      }
      else{
        $select = 'SELECT StudentNumber as sid, GPA as gpa, SchoolBuilding as building, siteID
        FROM Abre_StudentUnweightedGPA WHERE siteID = ? 
        LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));         
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];

      return response()->json($response, 201);
    }

    /**
     * Get subset of Weighted GPAs
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_weightedgpa(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      if ($site_id == -1){
        $select = 'SELECT StudentNumber as sid, GPA as gpa, SchoolBuilding as building, siteID
        FROM Abre_StudentWeightedGPA LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));      
      }
      else{
        $select = 'SELECT StudentNumber as sid, GPA as gpa, SchoolBuilding as building, siteID
        FROM Abre_StudentWeightedGPA WHERE siteID = ? 
        LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));      
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];

      return response()->json($response, 201);
    }

    /**
     * Get subset of ACT score
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_act(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      //$site_id = DatabaseConnection::$site_id;

      if ($site_id == -1){
        $select = 'SELECT StudentID as sid, STR_TO_DATE(TestingDate, "%Y-%m-%d") as test_date, Score as act, siteID
        FROM Abre_StudentACT WHERE CategoryName="Composite Score"
        ORDER BY STR_TO_DATE(TestingDate, "%Y-%m-%d") DESC LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));      
      }
      else{
        $select = 'SELECT StudentID as sid, STR_TO_DATE(TestingDate, "%Y-%m-%d") as test_date, Score as act, siteID
        FROM Abre_StudentACT WHERE CategoryName="Composite Score" AND siteID = ? 
        ORDER BY STR_TO_DATE(TestingDate, "%Y-%m-%d") DESC LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));      
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];

      return response()->json($response, 201);
    }

    /**
     * Get subset of PRE ACT score
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function students_preact(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'siteID' => 'required',
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $site_id = $request->input('siteID');

      if ($site_id == -1){
        $select = 'SELECT StudentID as sid, STR_TO_DATE(test_date, "%Y-%m-%d") as test_date, composite_score as preact, siteID
        FROM  Abre_PreACTData
        ORDER BY STR_TO_DATE(test_date, "%Y-%m-%d") DESC LIMIT ?, ?';
  
        $list = DB::select($select, array($offset, $limit));      
      }
      else{
        $select = 'SELECT StudentID as sid, STR_TO_DATE(test_date, "%Y-%m-%d") as test_date, composite_score as preact, siteID
        FROM Abre_PreACTData WHERE siteID = ? 
        ORDER BY STR_TO_DATE(test_date, "%Y-%m-%d") DESC LIMIT ?, ?';
  
        $list = DB::select($select, array($site_id, $offset, $limit));      
      }

      $response = [
        'status' => 'Success',
        'result' => $list
      ];


      return response()->json($response, 201);
    }

    private function get_community_count($site_id){
        // Community
        if ($site_id == -1){
          $results = DB::select('SELECT * FROM users_parent AS up LEFT JOIN parent_students AS ps
          ON up.id = ps.parent_id WHERE ps.studentId Is Null');
        }
        else{
          $results = DB::select('SELECT * FROM users_parent AS up LEFT JOIN parent_students AS ps
          ON up.id = ps.parent_id WHERE ps.studentId Is Null AND up.siteID = ?', array($site_id));
        }

        $community = count($results);

        return $community;
    }

    private function get_parents_count($site_id){
        // Parents
        if ($site_id == -1){
          $results = DB::select('SELECT DISTINCT up.id FROM users_parent AS up JOIN parent_students AS ps
          ON up.id = ps.parent_id WHERE ps.studentId');
        }
        else{
          $results = DB::select('SELECT DISTINCT up.id FROM users_parent AS up JOIN parent_students AS ps
          ON up.id = ps.parent_id WHERE ps.studentId AND up.siteID = ?', array($site_id));
        }

        $parents = count($results);

        return $parents;
    }

    private function get_staff_students_count($site_id){
      // Staff

      if ($site_id == -1){
        $user_list = DB::select('SELECT DISTINCT email FROM users');  
      }
      else{
        $user_list = DB::select('SELECT DISTINCT email FROM users  
        WHERE siteID = ?', array($site_id));  
      }

      $domain = DatabaseConnection::getConfigSiteGafeDomain();

      $student_list = array();
      $staff_list = array();
      foreach ($user_list as $staff) {
        $email = $staff->email;

        $usertype = DatabaseConnection::getUserType($domain, $email);

        if ($usertype ==  "student"){
          array_push($student_list, $email);
        }
        else{
          array_push($staff_list, $email);
        }
      }

      $students = count($student_list);
      $staff = count($staff_list);

      $staff_students = [
        'staff' => $staff,
        'students' => $students
      ];

      return $staff_students;
    }

    private function get_counts($site_id){

      $staff_students = $this->get_staff_students_count($site_id);
      $staff = $staff_students["staff"];
      $students = $staff_students["students"];

      $community = $this->get_community_count($site_id);

      $parents = $this->get_parents_count($site_id);

      //$students = $this->get_students_count($site_id);;

      //foreach ($results as $value) {
      //    error_log(json_encode($value));
      //}
      //error_log("COUNT");
      //error_log(count($results));

      $collection = [
          'staff' => $staff,
          'students' => $students,
          'parents' => $parents,
          'community' => $community
      ];

      return $collection;
    }


    /**
     * Get usertypes by site_id
     * 
     * <strong>Access:</strong> superadmin
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_types($site_id){

      $collection = $this->get_counts($site_id);

      $response = [
          'status' => 'Success',
          'result' => $collection
      ];
      return response()->json($response, 201);
    }

    /**
     * Get all usertypes across Abre
     * 
     * <strong>Access:</strong> superadmin
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function user_types_all(){

      $collection = $this->get_counts(-1);

      $response = [
          'status' => 'Success',
          'result' => $collection
      ];
      return response()->json($response, 201);
    }

    /**
     * Get site ID
     *
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function site_id($site_name){

      $select = 'SELECT siteID 
      FROM abre_tenant_map WHERE district_name = ?';

      $id = DB::select($select, array($site_name));  
      
      $response = [
        'status' => 'Success',
        'result' => $id
      ];

      return response()->json($response, 201);
    }
}