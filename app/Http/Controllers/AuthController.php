<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use Illuminate\Validation\ValidationException; use Inertia\Inertia;
class AuthController extends Controller { public function showLogin(){return Inertia::render('Auth/Login');} public function login(Request $r){$c=$r->validate(['email'=>'required|email','password'=>'required|string']);if(!Auth::attempt($c,$r->boolean('remember'))){throw ValidationException::withMessages(['email'=>'The provided credentials are incorrect.']);}$r->session()->regenerate();return redirect()->intended(route('dashboard'));} public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');} }
