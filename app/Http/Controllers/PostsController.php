<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Model\Post;
use App\Model\Answer;
use App\Model\Comment;
use App\User;
use Markdown;
use App\Model\Views;
use Illuminate\Http\Request;
use App\Http\Requests\SavePostRequest;
use App\Http\Requests\SaveAnswerRequest;

class PostsController extends Controller
{
    /**
    * @author Saed Yousef
    * @return all posts
    */
    public function index()
    {	$posts = Post::all();
        $data['posts'] = $posts;
    	return view('posts.index',$data);
    }

    /**
    *@author Saed Yousef
    *@param  instance of SavePostRequest
    *@return add new post
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
    * @author Saed Yousef
    * @return the add_post view
    */
    public function get_add_post()
    {
    	return view('posts.add');
    }

    /**
    * @author Saed Yousef
    * @param  $id
    * @return post details
    */
    public function view_post($id)
    {   
        $post = Post::find($id);
        if(empty($post))
        {
            $response = view('errors.posts_404');
        }else
        {
            $data['posts'] = $post;
            $data['post_body'] = Markdown::convertToHtml($post->body);
            $user = User::find($post->user_id);
            $data['user'] = $user;

            $answers = DB::table('answers')
                ->join('users', 'answers.user_id', '=', 'users.id')
                ->select('users.*','answers.*')
                ->where('post_id', $id)
                ->paginate(5);

            // Save the view for this post   
            $this->save_views($id);
            $data['answers'] = $answers;

            $response = view('posts.view', $data);
        }

        return $response;
    }

    /**
    * @author Saed Yousef
    * @param  instance of SaveAnswerRequest
    * @param  post_id
    * @param  body
    * @return posts/view
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

   // this function has been commented for future use
   /*   
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
    */

    /**
    * @author Saed Yousef
    * @param  $post_id
    * @return save a new view with device_id and post_id
    */
    public function save_views($post_id)
    {
        // Get the session id from the cooki
        $session_id = session()->getId();

        $views = DB::table('views')
            ->select('id')
            ->where('device_id', $session_id)
            ->where('post_id', $post_id)
            ->get('id')
            ->toArray();

        // Check if the device_id already exist with the same post_id
        if(empty($views)){
            $views            = new Views();
            $views->post_id   = $post_id;
            $views->device_id = $session_id;

            $views->save();
        } 
    }

}
