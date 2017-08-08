<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Http\Requests\SaveUserRequest;

class UsersController extends Controller
{   
    
    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @param $username
    * @desc return user profile
    */
    public function view_user($username)
    {
    	$user_id = User::select('id')->where('username', $username)->first()->id;
    	$user = User::find($user_id);
    	$data['user'] = $user;

    	return view('users.index', $data);
    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @param name
    * @param username
    * @param email
    * @desc update user profile ,return user profile
    */
    public function edit_profile(SaveUserRequest $request)
    {
    	$user = User::find(Auth::user()->id);

    	$user->name 	= $request->input('name');
        $user->username = $request->input('username');
    	$user->email    = $request->input('email');

    	$user->save();

        return redirect()->route('view_user',$user->username);
    }

    /**
    * @author Saed Yousef <saed.alzaben@gmail.com>
    * @desc return the edit profile form
    */
    public function get_edit_profile()
    {
    	$user = User::find(Auth::user()->id);
    	$data['user'] = $user;
    	
    	return view('users.edit', $data);
    }
}
