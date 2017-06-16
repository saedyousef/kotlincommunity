<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\CommentsInteraction;
use App\Model\PostsInteraction;

class InterActionsController extends Controller
{	
	/**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save downvote interaction for users on a comment
    */
     public function comment_downvote($comment_id)
    {
        $conditions = [
            'user_id' => Auth::user()->id,
            'comment_id' => $comment_id
        ];


        $user_interactions = CommentsInteraction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $comment_interaction = new CommentsInteraction();

            $comment_interaction->user_id = Auth::user()->id;
            $comment_interaction->comment_id = $comment_id;
            $comment_interaction->type = 2;

            $comment_interaction->save();

            return response('Comment Downvoted',200);
        }else
        {
            $interaction  = CommentsInteraction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save upovte interaction for users on a comment
    */
    public function comment_upvote($comment_id)
    {
        $conditions = [
            'user_id' => Auth::user()->id,
            'comment_id' => $comment_id
        ];


        $user_interactions = CommentsInteraction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $comment_interaction = new CommentsInteraction();

            $comment_interaction->user_id = Auth::user()->id;
            $comment_interaction->comment_id = $comment_id;
            $comment_interaction->type = 1;

            $comment_interaction->save();

            return response('Comment Upvoted',200);
        }else
        {
            $interaction  = CommentsInteraction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

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
    * @desc Save downvote interaction for users on a post
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
