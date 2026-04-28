<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdmissionAuthController extends Controller
{
    public function showLogin(){
        return view('admission.login');
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            $user = auth()->user();
            if ($user && isset($user->type) && $user->type === 'admission') {
                return redirect()->route('admission.dashboard');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => 'You are not authorized as an admission user.',
                ]);
            }
        }
        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);

    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admission.login');
    }

}
