<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class UsersController extends Controller
{
    public function view_user($username)
    {
    	$user_id = User::select('id')->where('username', $username)->first()->id;
    	$user = User::find($user_id);
    	$data['user'] = $user;

    	return view('users.index', $data);
    }
}
