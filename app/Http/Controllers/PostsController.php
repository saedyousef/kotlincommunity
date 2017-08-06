<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Model\Post;
use App\Model\Comment;
use App\User;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\SavePostRequest;
use App\Http\Requests\SaveCommentRequest;
use App\Http\Requests\SaveTagRequest;

class PostsController extends Controller
{
    /**
    *@author Saed Yousef
    *@return index view
    */
    public function index()
    {	$posts = Post::all();
        $data['posts'] = $posts;
    	return view('posts.index',$data);
    }

    /**
    *@author Saed Yousef
    *@param instance of SavePostRequest
    *@param instance of SaveTagRequest
    *@desc add new post with tags
    *@return posts/index view
    */
    public function add_post(SavePostRequest $request, SaveTagRequest $tag_request)
    {   
        //Cretae a new instance of Post model
        $post = new Post();

        $post->title    = $request->input('title');
        $post->body     = $request->input('body');
        $post->user_id  = Auth::user()->id;
        $post->save();
        
        return redirect()->route('view_post',$post->id);//$this->view_post($post->id);  
    }

    /**
    *@author Saed Yousef
    *@return the add_post view
    */
    public function get_add_post()
    {
    	return view('posts.add');
    }

    public function view_post($id)
    {
        $post = Post::find($id);
        $data['posts'] = $post;

        $user = User::find($post->user_id);
        $data['user'] = $user;

        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('users.*','comments.*')
            ->where('post_id', $id)
            ->paginate(5);
        $data['comments'] = $comments;
          
        return view('posts.view', $data);
    }

    /**
    *@author Saed Yousef
    *@param instance of SaveCommentRequest
    *@param passed post_id
    *@return posts/view
    */
    public function add_comment(SaveCommentRequest $request, $post_id)
    {
        $comment = new Comment(); // Create new instance from model

        $comment->body = $request->input('body');
        $comment->user_id  = Auth::user()->id;
        $comment->post_id  = $post_id;

        $comment->save(); // Create new record with requested data

        return $this->view_post($post_id);
    }

    public function delete_post($id)
    {
        $post = Post::find($id);
        
        if(!empty($post)){
            $post->delete();
            return response('Post Deleted', 200);
        }
        else
            return response('Post not found', 404);
            

    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save upovte interaction for users on a post
    */
    public function post_upvote($post_id)
    {
        $conditions = [
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
            'type' => 1
        ];


        $user_interactions = PostsInteraction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $post_interaction = new PostsInteraction();

            $post_interaction->user_id = Auth::user()->id;
            $post_interaction->post_id = $post_id;
            $post_interaction->type = 1;

            $post_interaction->save();

            return response('Post Upvoted',200);
        }else
        {
            $interaction  = PostsInteraction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save upovte interaction for users on a post
    */
    public function post_downvote($post_id)
    {
        $conditions = [
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
            'type' => 2
        ];


        $user_interactions = PostsInteraction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $post_interaction = new PostsInteraction();

            $post_interaction->user_id = Auth::user()->id;
            $post_interaction->post_id = $post_id;
            $post_interaction->type = 2;

            $post_interaction->save();

            return response('Post Downvoted',200);
        }else
        {
            $interaction  = PostsInteraction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

    }

}
