<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt([...$credentials, 'is_active' => true], $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $user->forceFill(['last_login_at' => now()])->save();
        ActivityLog::record('login', $user, 'Admin signed in');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLog::record('logout', Auth::user(), 'Admin signed out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
