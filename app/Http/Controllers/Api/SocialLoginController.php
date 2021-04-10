<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller
{
    public function redirectToProvider(){
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback(){
        $user = Socialite::driver('facebook')->user();
    }
}
