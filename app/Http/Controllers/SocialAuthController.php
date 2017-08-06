<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Model\Provider;
use App\User;

class SocialAuthController extends Controller
{
     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('auth/twitter');
        }
        
        $user_provider = Provider::where('provider_id', $user->getId())->first();

        if(!$user_provider)
        {
        	// create new user
        	$real_user = User::where('email', $user->getEmail())->first();

        	if(!$real_user)
        	{
        		$real_user = new User();

        		$real_user->email = $user->getEmail();
        		$real_user->name = $user->getName();
        		$already_registred = User::where('username', $user->getNickname())->first();
        		if(empty($already_registred)){
        			$real_user->username = $user->getNickname();
        		}
        		$real_user->avatar = $user->getAvatar();
        		
        		$real_user->save();
        	}

        	$create_provider = new Provider();

        	$create_provider->provider_id = $user->getId();
        	$create_provider->user_id 	  = $real_user->id;
        	$create_provider->provider 	  = $provider;

        	$create_provider->save();
        }else
        {
        	// login user
        	$real_user = User::find($user_provider->user_id);
        }
        
        auth()->login($real_user);

        return redirect()->route('welcome');
        
    }
}
