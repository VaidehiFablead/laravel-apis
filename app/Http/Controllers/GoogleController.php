<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account']) // Force Google account chooser
            ->redirect();
    }


    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        // dd($googleUser);

        $user = Login::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            ['name' => $googleUser->getName()]
        );

        if ($user) {
            Auth::login($user);
            Session::put('login_id', $user->login_id);
            Session::put('name', $user->name);
            Session::put('email', $user->email);
        }

        Auth::login($user);

        return redirect('/tables');
    }
}
