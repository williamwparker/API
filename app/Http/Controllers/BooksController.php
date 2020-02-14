<?php

namespace App\Http\Controllers;

use App\Book;
use App\Books_Libraries;
use App\Users_Parent;

use Illuminate\Http\Request;

use JWTAuth;

/**
 * @resource Books
 *
 * Books Controller
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class BooksController extends Controller
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
     * 
     * Gets books
     *
     * <strong>Body:</strong><br>
     * id (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function books(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $bookId = $request->input('id');

        $collection = Books::where('ID', $bookId)->where->get();

        if ($collection->isEmpty()) {
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
     * 
     * Gets books library
     *
     * <strong>Body:</strong><br>
     * bookId (optional)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function library(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        if ($usertype != "parent") {

            $userId = $user->id;
        }
        else {

            $parent = Users_Parent::where('email', $user->email)->get();
            $parentId = $parent->id;
        }

        $bookId = $request->input('bookId');

        $filterList = array();
        if ($parentId) 
            $filterList['Parent_ID'] = $parentId;        
        if ($bookId) 
            $filterList['Book_ID'] = $bookId;        
        if ($userId) 
            $filterList['User_ID'] = $userId;        

        $collection = Books_Libraries::where($filterList)->where->orderBy('ID', 'DESC')->get();

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
     * Save book to library
     *     
     * <strong>Body:</strong><br>
     * bookId (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function library_book_save(Request $request)
    {
        $usertype = JWTAuth::getPayload()->get('usertype');

        $collection = DatabaseConnection::validateUser();
        $user = $collection['result'];
        if ($usertype != "parent") {

            $userId = $user->id;
        }
        else {

            $parent = Users_Parent::where('email', $user->email)->get();
            $parentId = $parent->id;
        }

        $bookId = $request->input('bookId');

        $library = new Books_Libraries;

        $library->Parent_ID = $staffId;
        $library->User_ID = $userId;

        $library->Book_ID = $bookId;

        if (!$parentId)
            $library->Parent_ID = "";
        if (!$userId)
            $library->User_ID = "";

        if ($library->save()) {
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

    /**
     * Delete student group student
     * 
     * <strong>Access: </strong>staff<br>
     *
     * <strong>Query string:</strong><br>
     * id (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function library_book_delete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $bookId = $request->input('id');

        $collection = Books::where('ID', $bookId)->where->get();

        if ($collection->isEmpty()) {
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

}
