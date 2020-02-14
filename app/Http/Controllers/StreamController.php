<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\DatabaseConnection;

use App\Stream_Comment;
use App\Stream;

use Illuminate\Http\Request;

use JWTAuth;
use Auth;


/**
 * @resource Streams
 *
 * Stream Controller:
 * 
 * Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
 */
class StreamController extends Controller
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

        
        $this->middleware('jwt.refresh');
    }
    //-------------------------------------------
    // Streams
    //-------------------------------------------

    /**
     * Get all streams
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function streams()
    {
        $comments = Stream::all();

        $response = [
            'status' => 'Success',
            'result' => $comments
        ];

        return response()->json($response, 201);
    }

    /**
     * Save stream
     * 
     * <strong>Body:</strong><br>
     * group (required)<br>
     * title (optional)<br>
     * slug (optional)<br>
     * type (optional)<br>
     * required (optional)<br>
     * 
     * Request body:
     * group, title, slug, type, url, required
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function stream(Request $request)
    {
        $validatedData = $request->validate([
            'group' => 'required',
        ]);

        $group = $request->input('group');
        $title = $request->input('title');
        $slug = $request->input('slug');
        $type = $request->input('type');
        $url = $request->input('url');
        $required = $request->input('required');

        $stream = new Stream;

        $stream->group = $group;
        $stream->title = $title;
        $stream->slug = $slug;
        $stream->type = $type;
        $stream->url = $url;
        $stream->required = $required;
        
        $stream->save();

        return "Success";
    }    

    //-------------------------------------------
    // Stream Comments
    //-------------------------------------------

    /**
     * Gets all stream comments/likes
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function stream_comments()
    {
        $comments = Stream_Comment::all();

        $response = [
            'status' => 'Success',
            'result' => $comments
        ];

        return response()->json($response, 201);
    }

    /**
     * Get stream comments/likes by ID
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function stream_comments_id($id)
    {
        $comments = Stream_Comment::find($id);

        $response = [
            'status' => 'Success',
            'result' => $comments
        ];

        return response()->json($response, 201);
    }

    /**
     * Get stream cards for authenticated user that have comments
     *
     * <strong>Query string:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     * 
     * <strong>Notes:</strong><br>
     * Ordered by id (most recent first)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function stream_user_comment_cards(Request $request)
    {
        //$collection = DatabaseConnection::validateUser();
        //$user = $collection['result'];
        //$email = $user->email;

        $user = Auth::User();
        $email = $user->email;

        $offset = $request->query('offset');
        $limit = $request->query('limit');

        if (!$limit) $limit = 24;

        $comments = Stream_Comment::where('user', $email)->where('comment','<>','')->take($limit)->skip($offset)->orderBy('id', 'DESC')->get();

        // Dedup
        $unique = $comments->unique('url');

        // Total cards
        $comments = Stream_Comment::where('user', $email)->where('comment','<>','')->get();
        $totalUnique = $comments->unique('url');
        $total = $totalUnique->count();

        $response = [
            'status' => 'Success',
            'result' => [
                'comments' => $unique,
                'totalCards' => $total
            ]
        ];

        return response()->json($response, 201);
    }

    /**
     * Get stream cards for authenticated user that have likes
     * 
     * <strong>Query string:</strong><br>
     * offset (required)<br>
     * limit (required)<br>
     *
     * <strong>Notes:</strong><br>
     * Ordered by id (most recent first)<br>
     * 
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
    */
    public function stream_user_like_cards(Request $request)
    {
        //$collection = DatabaseConnection::validateUser();
        //$user = $collection['result'];
        //$email = $user->email;

        $user = Auth::User();
        $email = $user->email;

        $offset = $request->query('offset');
        $limit = $request->query('limit');

        if (!$limit) $limit = 24;

        $likes = Stream_Comment::where('user', $email)->where('liked',1)->take($limit)->skip($offset)->orderBy('id', 'DESC')->get();

        // Dedup
        $unique = $likes->unique('url');

        // Total cards
        $likes = Stream_Comment::where('user', $email)->where('liked',1)->get();
        $totalUnique = $likes->unique('url');
        $total = $totalUnique->count();
        
        $response = [
            'status' => 'Success',
            'result' => [
                'likes' => $unique,
                'totalCards' => $total
            ]

        ];

        return response()->json($response, 201);
    }

    /**
     * Get stream contents for a url
     *
     * <strong>Body:</strong><br>
     * url (required)<br>
     * 
     * <strong>Notes:</strong><br>
     * Comments ordered by id<br>
     * Also returns total liked count and comments count for url<br> 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function stream_card_contents(Request $request)
    {
        $user = Auth::User();
        $email = $user->email;

        $validatedData = $request->validate([
            'url' => 'required',
        ]);

        $url = $request->input('url');

        $comments = Stream_Comment::where('url', $url)->where('comment','<>','')->orderBy('id', 'DESC')->get();

        $commentCount = $comments->count();

        $userComments = Stream_Comment::where('user', $email)->where('url', $url)->where('comment','<>','')->get();
        $userCommentCount = $userComments->count();

        $likedCount = Stream_Comment::where('url', $url)->where('liked',1)->where('comment','')->get()->count();
        $userLikedCount = Stream_Comment::where('user', $email)->where('url', $url)->where('liked',1)->where('comment','')->get()->count();

        $response = [
            'status' => 'Success',
            'result' => [
                'comments' => $comments,
                'counts' =>
                [
                    'comments' => $commentCount,
                    'userComments' => $userCommentCount,
                    'likes' => $likedCount,
                    'userLikes' => $userLikedCount
                ]
            ]
        ];

        return response()->json($response, 201);
    }

    /**
     * Delete stream like by url for authenticated user
     *
     * <strong>Body:</strong><br>
     * url (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function stream_delete_like(Request $request)
    {
        $user = Auth::User();
        $email = $user->email;

        $validatedData = $request->validate([
            'url' => 'required',
        ]);

        $url = $request->input('url');

        $comments = Stream_Comment::where('user', $email)->where('url', $url)->where('comment','')->where('liked',1)->get()->first();

        $comment = $comments->comment;

        $comments->delete();

        $response = [
            'status' => 'Success',
            'result' => $comment
        ];

        return response()->json($response, 201);
    }

    /**
     * Delete stream comment by ID for authenticated user
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function stream_delete_id($id)
    {
        $user = Auth::User();

        $comments = Stream_Comment::find($id);

        if ($user->email != $comments->user) {

            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $comment = $comments->comment;

        $comments->delete();

        $response = [
            'status' => 'Success',
            'result' => $comment
        ];

        return response()->json($response, 201);
    }

    /**
     * Save stream comment
     * 
     * <strong>Body:</strong><br>
     * url (required)<br>
     * title (required)<br>
     * image (required)<br>
     * liked (required)<br>
     * excerpt (required)<br>
     * comment (optional)<br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function stream_save(Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'required',
            'title' => 'required',
            'image' => 'required',
            'liked' => 'required',
            'excerpt' => 'required',
        ]);

        $user = Auth::User();

        $streamComment = new Stream_Comment;

        $streamComment->url = $request->input('url');
        $streamComment->title = $request->input('title');
        $streamComment->image = $request->input('image');
        $streamComment->liked = $request->input('liked');
        $streamComment->excerpt = $request->input('excerpt');
        $streamComment->user = $user->email;

        $comment = $request->input('comment');
        if ($comment)
            $streamComment->comment = $comment;

        $streamComment->save();

        $response = [
            'status' => 'Success',
            'result' => $streamComment->url
        ];

        return response()->json($response, 201);
    }

    /**
     * Update stream comment for authenticated user
     *
     * <strong>Body:</strong><br>
     * id (required)<br>
     * comment (required)<br>
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function stream_comment_update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'comment' => 'required',
        ]);

        $id = $request->input('id');

        $user = Auth::User();

        $streamComment = Stream_Comment::find($id);

        if ($user->email != $streamComment->user) {
            $response = [
                'status' => 'Error',
                'result' => "Access denied",
            ];

            return response()->json($response, 401);
        }

        $comment = $request->input('comment');
        if ($comment)
            $streamComment->comment = $comment;

        $streamComment->save();

        $response = [
            'status' => 'Success',
            'result' => $comment
        ];

        return response()->json($response, 201);
    }
}
