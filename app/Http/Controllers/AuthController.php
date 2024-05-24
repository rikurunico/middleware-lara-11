<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Show the Register form.
     *
     * @return \Illuminate\View\View
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerPost(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Your account has been created.');
        } catch (ValidationException $e) {
            return redirect()->route('register')->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->route('register')->with('error', 'An error occurred while creating your account. ' . $th->getMessage())->withInput();
        }
    }


    /**
     * Show the Login form.
     *
     * @return \Illuminate\View\View
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'username' => 'The provided credentials do not match our records.',
                ]);
            } else {
                Auth::login($user);
                return redirect()->route('home');
            }
        } catch (\Throwable $th) {
            return redirect()->route('login')->with('error', 'An error occurred while logging in. ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Check Authenticated User.
     *
     * @return String
     */
    public function check(): String
    {
        return 'You are logged in as ' . Auth::user()->name;
    }
}
