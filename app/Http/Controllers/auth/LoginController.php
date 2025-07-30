<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login authentication and redirect.
     */
    public function login(Request $request)
    {

        $credentials = $request->only('username', 'password');
        $role = strtolower($request->input('role'));

        $user = User::where('username', $credentials['username'])
                    ->where('role', $role)
                    ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Invalid credentials or role mismatch.');
        }

        Auth::login($user);

        session([
            'FLEET' => [
                'user_type' => $user->role,
                'user_name' => $user->username,
                'em_number' => $user->name,
                'em_id' => $user->id,
                'dv_name' => $user->faculty ?? '',
                'dp_name' => $user->department ?? '',
                'unit_name' => $user->unit ?? '',
                'em_std' => $user->designation ?? '',
                'site_name' => $user->campus ?? '',
            ]
        ]);

        return redirect()->route('redirect.by.role');
        // return redirect()->intended('/main');

        // return redirect()->route('test.redirect');

    }


    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
