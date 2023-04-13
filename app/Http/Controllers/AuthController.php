<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }
    public function registerForm()
    {
        return view('auth.register');
    }
    public function registerPost(Request $req)
    {
        $credential = $req->validate([
            'name' => 'required|max:225',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        $credential['password'] = Hash::make($req->password);
        $user = User::create($credential);

        if ($user) {
            return redirect()->route('login');
        }
        return back()->with('registerError', 'Register Erorr');
    }

    public function loginPost(Request $req)
    {

        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array('email' => $req->input('email'), 'password' => $req->input('password')))) {
            $req->session()->regenerate();
            return redirect()->intended('/panel/dashboard');
        } else {
            return back()->with('loginError', 'Login Erorr');
        }
    }

    public function password(Request $req)
    {

        $password = $req->password;
        $id_user = auth()->user()->id_user;

        $user = User::where('id_user', $id_user)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($password),
            ]);
        }

        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
