<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class SocialController extends Controller
{
    public function index($provider)
    {
        $user = Auth::user();
        $provider_user = Socialite::driver($provider)->userFromToken($user->provider_token);
        dd($provider_user);
    }
}
