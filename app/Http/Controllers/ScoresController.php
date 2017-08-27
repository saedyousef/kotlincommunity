<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Auth;
use App\Model\Interaction;
use App\Model\Post;
use App\Model\Comment;



class ScoresController extends Controller
{
    public function showProfile($id)
    {
        $user = Redis::get('user:profile:'.$id);

        return view('user.profile', ['user' => $user]);
    }

    public function calculate_scores()
    {

    }

    public function get_score($user_id)
    {

    }
}
