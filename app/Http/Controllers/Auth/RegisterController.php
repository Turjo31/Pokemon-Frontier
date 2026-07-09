<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50|unique:TRAINER,username',
            'email'    => 'required|email|max:100|unique:TRAINER,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $trainer = Trainer::create([
            'username'      => $request->name,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'rank_points'   => 0,
        ]);

        Auth::guard('trainer')->login($trainer);

        return redirect()->route('dashboard');
    }
}