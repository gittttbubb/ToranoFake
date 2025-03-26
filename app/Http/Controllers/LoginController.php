<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show_login()
    {
        return view('admin.login');
    }
    public function check_login(Request $request)
    {
        if (Auth::attempt(['email' => $request-> input('email'), 'password' => $request->input('password')])) {
            return redirect('/admin');
        }
        else{
            return redirect() -> back();
        }
    }
    //user torano
    public function login()
    {
        return view('login');
    }
    public function login_user(Request $request){
        // if (Auth::attempt(['email' => $request-> input('email'), 'password' => $request->input('password')])) {
        //     return redirect('/');
        // }
        // else{
        //     return redirect() -> back();
        // }
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            session(['user' => $user->toArray()]);
            return redirect('/');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ])->withInput($request->only('email'));
        }
    }
    public function register()
    {
        return view('register');
    }
    public function create_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login');
    }
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect('/');
    }
}
