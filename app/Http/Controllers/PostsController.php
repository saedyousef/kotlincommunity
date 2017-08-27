<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Model\Post;
use App\Model\Answer;
use App\Model\Comment;
use App\User;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\SavePostRequest;
use App\Http\Requests\SaveAnswerRequest;
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
    public function add_post(SavePostRequest $request)
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

        $answers = DB::table('answers')
            ->join('users', 'answers.user_id', '=', 'users.id')
            ->select('users.*','answers.*')
            ->where('post_id', $id)
            ->paginate(5);
        $data['answers'] = $answers;
          
        return view('posts.view', $data);
    }

    /**
    *@author Saed Yousef
    *@param instance of SaveAnswerRequest
    *@param  post_id
    *@param  body
    *@return posts/view
    */
    public function add_answer(SaveAnswerRequest $request, $post_id)
    {
        $answer = new Answer(); // Create new instance from model

        $answer->body = $request->input('body');
        $answer->user_id  = Auth::user()->id;
        $answer->post_id  = $post_id;

        $answer->save(); // Create new record with requested data

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

}
