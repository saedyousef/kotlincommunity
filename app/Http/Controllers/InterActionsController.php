<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Model\Interaction;

/**
* @author Saed Yousef <saed.alzaben@gmail.com>
* @desc Interactions controllers to handle all related actions to the downvote and upvote
*/

class InterActionsController extends Controller
{	

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save upovte interactions for posts and comments
    */
    public function upvote($reference_id, $reference_type)
    {
        $conditions = [
            'user_id'        => Auth::user()->id,
            'reference_id'   => $reference_id,
            'reference_type' => $reference_type,
        ];

        $user_interactions = Interaction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result['id']   = $user_interaction->id;
            $result['type'] = $user_interaction->type;
        }
        $response = [];
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $interaction = new Interaction();

            $interaction->user_id        = Auth::user()->id;
            $interaction->reference_id   = $reference_id;
            $interaction->reference_type = $reference_type;
            $interaction->type           = 1;
            $interaction->save();

            $response = response('Upvoted',200);
        }else
        {   
            if($result['type'] == 2)
            {
                // if this post is already downvote by the same user , then delete the exist action and save a new one
                $interaction  = Interaction::find($result['id']);
                $interaction->delete();

                $interaction = new Interaction();

                $interaction->user_id        = Auth::user()->id;
                $interaction->reference_id   = $reference_id;
                $interaction->reference_type = $reference_type;
                $interaction->type           = 1;
                $interaction->save();

                $response = response('Upvoted',200);
            }else
            {
                // If the user already has upvoted the post or the comment and reclick on the upvote action
                $interaction  = Interaction::find($result['id']);
                $interaction->delete();

                $response = response('Interaction deleted',404);
            }
            
        }

        return $response;
    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc Save downvote interaction for posts and comments
    */
    public function downvote($reference_id, $reference_type)
    {
        $conditions = [
            'user_id'        => Auth::user()->id,
            'reference_id'   => $reference_id,
            'reference_type' => $reference_type,
        ];


        $user_interactions = Interaction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result['id']   = $user_interaction->id;
            $result['type'] = $user_interaction->type;
        }
        $response = [];
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $interaction = new Interaction();

            $interaction->user_id        = Auth::user()->id;
            $interaction->reference_id   = $reference_id;
            $interaction->reference_type = $reference_type;
            $interaction->type           = 2;

            $interaction->save();

            $response = response('Downvoted',200);
        }else
        {   
            if($result['type'] == 1)
            {
                // if this post is already upvote by the same user , then delete the exist action and save a new one
                $interaction  = Interaction::find($result['id']);
                $interaction->delete();

                $interaction = new Interaction();

                $interaction->user_id        = Auth::user()->id;
                $interaction->reference_id   = $reference_id;
                $interaction->reference_type = $reference_type;
                $interaction->type           = 2;
                $interaction->save();

                $response = response('Downvoted',200);
            }else
            {
                // If the user already has downvoted the post or the comment and reclick on the upvote action
                $interaction  = Interaction::find($result['id']);
                $interaction->delete();

                $response = response('Interaction deleted',404);
            }

        }
        return $response;
    }

}
