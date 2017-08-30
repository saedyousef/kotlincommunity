<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Auth;
use DB;
use App\Model\Interaction;
use App\Model\Post;
use App\Model\Comment;
use App\Model\Answer;

/**
* @author Saed Yousef <saed.alzaben@gmail.com>
* @desc Scores class for handling all scores operations for users
*/

class ScoresController extends Controller
{   
    // Function to test get score from redis
    public function showProfile($user_id)
    {
        $points = Redis::get('user_score'.$user_id);
        return view('users.profile', ['points' => $points]);
    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @param $user_id
    * @desc calculate the score for the provided user
    */
    public function calculate_score($user_id)
    {
        $posts = DB::table('posts')
            ->select('id')
            ->where('user_id', $user_id)
            ->pluck('id')
            ->toArray();

        $answers = DB::table('answers')
            ->select('id')
            ->where('user_id', $user_id)
            ->pluck('id')
            ->toArray();

        $positive_posts_points = count(DB::table('interactions')
            ->select('id')
            ->whereIn('reference_id', $posts)
            ->where('reference_type', 1)
            ->where('type', 1)
            ->get()) * env('POSTS_POINTS');

        $negative_posts_points = count(DB::table('interactions')
            ->select('id')
            ->whereIn('reference_id', $posts)
            ->where('reference_type', 1)
            ->where('type', 2)
            ->get()) * env('POSTS_POINTS');

        $positive_answers_points = count(DB::table('interactions')
            ->select('id')
            ->whereIn('reference_id', $answers)
            ->where('reference_type', 2)
            ->where('type', 1)
            ->get()) * env('ANSWERS_POINTS');

        $negative_answers_points = count(DB::table('interactions')
            ->select('id')
            ->whereIn('reference_id', $answers)
            ->where('reference_type', 2)
            ->where('type', 2)
            ->get()) * env('ANSWERS_POINTS'); 

        $points = ($positive_posts_points + $positive_answers_points) - ($negative_posts_points + $negative_answers_points);

        if($points < 0)
        {
            $points = 0;
        }

        // Caching the scores
        $score = Redis::set('user_score'.$user_id, $points);

        return $points;
    }
}
