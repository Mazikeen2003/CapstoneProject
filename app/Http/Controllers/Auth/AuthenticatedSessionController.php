<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $email = $request->email;
        $password = $request->password;

        // Updated mock users with correct credentials
        $mockUsers = [
            'admin@cabuyao.gov.ph' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
            'department@cabuyao.gov.ph' => ['password' => 'department123', 'role' => 'department', 'name' => 'Department Staff'],
            'city@cabuyao.gov.ph' => ['password' => 'city123', 'role' => 'city', 'name' => 'City Official'],
            'barangay@cabuyao.gov.ph' => ['password' => 'barangay123', 'role' => 'barangay', 'name' => 'Barangay Official'],
            
            // Alternative emails for testing (from LoginRequest.php)
            'admin@example.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
            'department@example.com' => ['password' => 'department123', 'role' => 'department', 'name' => 'Department Staff'],
            'city@example.com' => ['password' => 'city123', 'role' => 'city', 'name' => 'City Official'],
            'barangay@example.com' => ['password' => 'barangay123', 'role' => 'barangay', 'name' => 'Barangay Official'],
        ];

        if (isset($mockUsers[$email]) && $mockUsers[$email]['password'] === $password) {
            // Store user in session
            $request->session()->put('mock_user', [
                'email' => $email,
                'role' => $mockUsers[$email]['role'],
                'name' => $mockUsers[$email]['name']
            ]);
            
            $request->session()->regenerate();

            // Redirect based on role
            $role = $mockUsers[$email]['role'];
            return redirect()->route("{$role}.dashboard");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('mock_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}