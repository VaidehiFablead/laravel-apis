<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $googleUser = Socialite::driver('google')->user();
        // dd($googleUser);


        // Just match or create by email and name only (no google_id)
        $user = Login::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            ['name' => $googleUser->getName()]
        );

        Auth::login($user);

        return redirect('/tables');
    }
}
