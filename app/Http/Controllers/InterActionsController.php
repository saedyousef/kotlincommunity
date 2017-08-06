<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Interaction;

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
            'type'           => 1
        ];


        $user_interactions = Interaction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $interaction = new Interaction();

            $interaction->user_id        = Auth::user()->id;
            $interaction->reference_id   = $reference_id;
            $interaction->reference_type = $reference_type;
            $interaction->type           = 1;

            $interaction->save();

            return response('Upvoted',200);
        }else
        {
            $interaction  = Interaction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

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
            'type'           => 2
        ];


        $user_interactions = Interaction::where($conditions)->get();
        $result = [];
        foreach ($user_interactions as $user_interaction) {
            $result[] = $user_interaction->id;
        }
        // Check if the user already have voted this post or not
        if(empty($result))
        {
            $interaction = new Interaction();

            $interaction->user_id        = Auth::user()->id;
            $interaction->reference_id   = $reference_id;
            $interaction->reference_type = $reference_id;
            $interaction->type           = 2;

            $interaction->save();

            return response('Upvoted',200);
        }else
        {
            $interaction  = Interaction::find($result[0]);
            $interaction->delete();
            return response('Interaction deleted',200);
        }

    }

}
