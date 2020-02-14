<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Helper\DatabaseConnection;

use DB;

use Illuminate\Http\Request;

use JWTAuth;

use Config;

/**
 * @resource People
 *
 * People Controller:
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class PeopleController extends Controller
{
    public function __construct(){

        // User has a site id in token
        // Sets site_domain (gafe) and site_id in DatabaseConnection
        // calls SetDatabase
        $this->middleware('auth.connection');

        // User has a user type in token
        // calls CheckSitePermission
        $this->middleware('auth.permission');

        //$this->middleware('jwt.refresh');

        // Checks to see if token is expired or invalid
        $this->middleware('jwt.auth');

    }

    /**
     * Get count of staff from directory
     *
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_staff_total(){
      $site_id = DatabaseConnection::$site_id;

      $count = DB::select('SELECT COUNT(*) as count FROM directory  
      WHERE archived = ? AND siteID = ?', array(0, $site_id));  

      $response = [
          'status' => 'Success',
          'result' => $count
      ];
      return response()->json($response, 201);
    }

    /**
     * Get subset of staff from directory
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * sort (required)<br>
     * descending (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_staff_page(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'sort' => 'required',
        'descending' => 'required'
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $sort = $request->input('sort');
      $descending = $request->input('descending');

      $select = 'SELECT id, firstname, lastname, location, email, title, picture, extension FROM directory  
      WHERE archived = ? AND siteID = ? ORDER BY '.$sort;

      if ($descending == true)
        $select = $select.' DESC LIMIT ?, ?';
      else
        $select = $select.' ASC LIMIT ?, ?';

      $site_id = DatabaseConnection::$site_id;

      $people_list = DB::select($select, array(0, $site_id, $offset, $limit));    

      foreach ($people_list as $member) {
        ///*
        $member->email = stripslashes($member->email);
        $member->firstname = htmlspecialchars($member->firstname, ENT_QUOTES);
        $member->firstname = stripslashes($member->firstname);
        $member->lastname = htmlspecialchars($member->lastname, ENT_QUOTES);
        $member->lastname = stripslashes($member->lastname);
        $member->employeeid = htmlspecialchars($member->id, ENT_QUOTES);
        $member->location = htmlspecialchars($member->location, ENT_QUOTES);
        $member->location = stripslashes($member->location);
        $member->title = htmlspecialchars($member->title, ENT_QUOTES);
        $member->title = stripslashes($member->title);
        $member->extension = htmlspecialchars($member->extension, ENT_QUOTES);
        $member->extension = stripslashes($member->extension);  
        //*/

        $member->picture = htmlspecialchars($member->picture, ENT_QUOTES);

        if (strpos($member->picture, 'http') === false) {

          if($member->picture == ""){
            $member->picture = "https://storage.googleapis.com/abre-production/private_html/Abre-People/user.png";
          }else{

            // Fix
            $member->picture = "https://storage.googleapis.com/abre-production/private_html/Abre-People/user.png";

            //$picture = $portal_root."/modules/Abre-Directory/serveimage.php?file=$picture";


          }
        }
      }

      $response = [
          'status' => 'Success',
          'result' => $people_list
      ];
      return response()->json($response, 201);
    }

    /**
     * Get count of community
     *
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_community_total(){
      $site_id = DatabaseConnection::$site_id;

      $count = DB::select('SELECT COUNT(*) as count FROM users_parent AS up LEFT JOIN parent_students AS ps
			ON up.id = ps.parent_id WHERE ps.studentId Is Null AND up.siteID = ?', array($site_id));  

      $response = [
          'status' => 'Success',
          'result' => $count
      ];
      return response()->json($response, 201);
    }

    /**
     * Get subset of community
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * sort (required)<br>
     * descending (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_community_page(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'sort' => 'required',
        'descending' => 'required'
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $sort = $request->input('sort');
      $descending = $request->input('descending');

      if ($sort == "firstname") $sort = "email";

      $select = 'SELECT up.id, picture, email, firstname, lastname, up.siteID FROM users_parent AS up LEFT JOIN parent_students AS ps
			ON up.id = ps.parent_id WHERE ps.studentId Is Null AND up.siteID = ? ORDER BY '.$sort;

      if ($descending == true)
        $select = $select.' DESC LIMIT ?, ?';
      else
        $select = $select.' ASC LIMIT ?, ?';

      $site_id = DatabaseConnection::$site_id;

      $people_list = DB::select($select, array($site_id, $offset, $limit));    

      $picture = "https://storage.googleapis.com/abre-production/private_html/Abre-People/user.png";

      foreach ($people_list as $member) {
        $member->picture = $picture;
        $member->email = stripslashes($member->email);
        if ($member->firstname == "" && $member->lastname == ""){
          $member->firstname = $member->email;
        }
      }

      $response = [
        'status' => 'Success',
        'result' => $people_list
      ];
      return response()->json($response, 201);
    }

    /**
     * Get count of parents
     *
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_parents_total(){
      $site_id = DatabaseConnection::$site_id;

      $count = DB::select('SELECT COUNT(DISTINCT(parent_students.parent_id)) as count FROM parent_students
			LEFT JOIN users_parent
			ON parent_students.parent_id = users_parent.id
			WHERE parent_students.siteID = ? AND users_parent.siteID =  ?', array($site_id, $site_id));  

      $response = [
          'status' => 'Success',
          'result' => $count
      ];
      return response()->json($response, 201);
    }

    private function getStudentName($studentId, $site_id){

      if ($studentId == "") return "";

      $student = DB::select('SELECT FirstName, LastName FROM Abre_Students 
      WHERE StudentId = ? AND siteID =  ?', array($studentId, $site_id));  
  
      foreach ($student as $s){
        $name = $s->FirstName." ".$s->LastName;
      }

      error_log($name);

      return $name;
    }
  
    /**
     * Get subset of parents from directory
     *
     * <strong>Body:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * sort (required)<br>
     * descending (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_parents_page(Request $request){

      $validatedData = $request->validate([
        'offset' => 'required',
        'limit' => 'required',
        'sort' => 'required',
        'descending' => 'required'
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $sort = $request->input('sort');
      $descending = $request->input('descending');

      if ($sort == "firstname") $sort = "email";


      $select = 'SELECT parent_id, parent_students.studentId, email FROM parent_students
      LEFT JOIN users_parent
      ON parent_students.parent_id = users_parent.id
      WHERE parent_students.siteID = ? AND users_parent.siteID = ? ORDER BY '.$sort;
      
      if ($descending == true)
        $select = $select.' DESC LIMIT ?, ?';
      else
        $select = $select.' ASC LIMIT ?, ?';

      $site_id = DatabaseConnection::$site_id;

      $people_list = DB::select($select, array($site_id, $site_id, $offset, $limit));    

      foreach ($people_list as $parent) {

        $picture = "https://storage.googleapis.com/abre-production/private_html/Abre-People/user.png";

        $parent->picture = $picture;
        $parent->email = stripslashes($parent->email);
        $parent->firstname = $parent->email;
        $parent->lastname = "";

        $parent->students = $parent->studentId;
      }

      $i = 0;
      $last = "0";
      $lastparent = "";
      $parentsCombined = array();
      $parentStudents = "";
      foreach($people_list as $parent){
        $current = $parent->email;
        if ($current != $last && $i > 0){
          $lastparent->students = $parentStudents;
          array_push($parentsCombined, $lastparent);
          $studentName = self::getStudentName($parent->students, $site_id);
          $parentStudents = $studentName;
        }
        else{
          if ($i != 0) $parentStudents .= ", ";
          $studentName = self::getStudentName($parent->students, $site_id);
          $parentStudents .= $studentName;
        }

        $i = $i + 1;
        $last = $current;
        $lastparent = $parent;
      }
      $lastparent->students = $parentStudents;
      array_push($parentsCombined, $lastparent);


      $response = [
          'status' => 'Success',
          'result' => $parentsCombined
        ];
      return response()->json($response, 201);
    
    }

    /**
     * Get count of students
     *
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_students_total(){
      $site_id = DatabaseConnection::$site_id;

      $count = DB::select('SELECT COUNT(*) as count
      FROM Abre_Students 
			LEFT JOIN Abre_AD
			ON Abre_Students.StudentID = Abre_AD.StudentId
			WHERE Abre_Students.Status != "I" AND Abre_Students.siteID = ?  
      AND (Abre_AD.siteID = ? OR Abre_AD.siteID IS NULL)', array($site_id, $site_id));        

      $response = [
          'status' => 'Success',
          'result' => $count
      ];
      return response()->json($response, 201);
    }

    private function resize_picture($rawimage, $maxsize){

      $image = "data:image/jpeg;base64, ".$rawimage;

      $mime = getimagesize($image);
      $width = $mime[0];
        
      if ($width < $maxsize) {
        //return $rawimage;
      }
      
      $imageCreated = imagecreatefromjpeg($image);

      //error_log("2");

      if ($width < $maxsize) {

        //error_log("3");

        ob_start();
        imagejpeg($imageCreated);
        $image =  ob_get_contents();
        ob_end_clean();
      }
      else{
        //error_log("4");

        $imageScaled = imagescale($imageCreated, $maxsize);
        ob_start();
        imagejpeg($imageScaled);
        $image =  ob_get_contents();
        ob_end_clean();
      }
  
      imagedestroy($imageCreated);
  
      $image = base64_encode($image);
      return $image;
    }
  
    /**
     * Get subset of students
     *
     * <strong>Body:</strong><br>
     * term (required)<br>
     * offset (required)<br>
     * limit (required)<br>
     * sort (required)<br>
     * descending (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_students_page(Request $request){

      $validatedData = $request->validate([
        'term' => 'required',
        'offset' => 'required',
        'limit' => 'required',
        'sort' => 'required',
        'descending' => 'required'
      ]);

      $offset = $request->input('offset');
      $limit = $request->input('limit');
      $term = $request->input('term');
      $sort = $request->input('sort');
      $descending = $request->input('descending');

      $select = 'SELECT FirstName as firstname, LastName as lastname, 
      Abre_Students.StudentId, SchoolName as location, CurrentGrade as grade, Abre_AD.Email as email
      FROM Abre_Students 
			LEFT JOIN Abre_AD
			ON Abre_Students.StudentID = Abre_AD.StudentId
			WHERE Abre_Students.Status != "I" AND Abre_Students.siteID = ? 
      AND (Abre_AD.siteID = ? OR Abre_AD.siteID IS NULL) ORDER BY '.$sort;

      if ($descending == true)
        $select = $select.' DESC LIMIT ?, ?';
      else
        $select = $select.' ASC LIMIT ?, ?';

      $site_id = DatabaseConnection::$site_id;

      $people_list = DB::select($select, array($site_id, $site_id, $offset, $limit));    

      foreach ($people_list as $student) {

        $studentID = $student->StudentId;

        ///*
        $schedule = DB::select('SELECT CourseName as course, TeacherName as teacher, Period as period, RoomNumber as number
        FROM Abre_StudentSchedules
        WHERE StudentID = ? AND 
        (TermCode = ? OR TermCode = "Year") AND siteID = ?', array($studentID, $term, $site_id));

        $student->schedule = $schedule;
        //*/

        $picture = "https://storage.googleapis.com/abre-production/private_html/Abre-People/user.png";
        $student->picture = $picture;

        ///*
        $picture = DB::select('SELECT Value FROM 
        Abre_VendorLink_SIS_StudentPictures WHERE StudentID = ? AND 
        Value != "" AND siteID = ?', array($studentID, $site_id));

        foreach($picture as $p){
          $current = $p->Value;
        }

        $picture = $current;
        $rawdata = htmlspecialchars($picture, ENT_QUOTES);

        $rawdata = self::resize_picture($rawdata, 30);

        $image_data = "data:image/png;base64, ".$rawdata;
        $student->picture = $image_data;
        //*/

      }

      $response = [
        'status' => 'Success',
        'result' => $people_list
      ];
      return response()->json($response, 201);
    }

    /**
     * Get student portfolio
     *
     * <strong>Body:</strong><br>
     * studentId (required)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function people_student_portfolio(Request $request){

      $portfolio_list = array();

      $response = [
        'status' => 'Success',
        'result' => $portfolio_list
      ];
      return response()->json($response, 201);
    }

  }