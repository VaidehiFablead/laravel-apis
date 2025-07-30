<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = Login::where('email', $request->email)->where('password', $request->password)->first();
            Session::put('login_id',$user->login_id);
            Session::put('name',$user->name);
            Session::put('email',$user->email);

        if ($user) {
              return response()->json(['success' => true], 200);
        } else {
            return back()->with('error', 'Invalid email or password');
        }
    }

       public function logout(){
        Session::flush();
        return redirect('/login');
    }

    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }
}
