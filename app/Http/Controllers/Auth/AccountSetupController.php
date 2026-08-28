<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccountSetupController extends Controller
{
    public function create(string $token): View
    {
        $setupToken = $this->findValidToken($token);

        return view('auth.account-setup', [
            'token' => $token,
            'user' => User::findOrFail($setupToken->user_id),
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
        ]);

        $setupToken = $this->findValidToken($token);
        $user = User::findOrFail($setupToken->user_id);

        DB::transaction(function () use ($setupToken, $user, $validated): void {
            $updated = DB::table('account_setup_tokens')
                ->where('id', $setupToken->id)
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->update(['used_at' => now(), 'updated_at' => now()]);

            if ($updated !== 1) {
                throw ValidationException::withMessages([
                    'token' => 'This account setup link has expired or has already been used.',
                ]);
            }

            $user->forceFill([
                'password_hash' => Hash::make($validated['password']),
                'must_change_password' => false,
            ])->save();
        });

        return redirect()->route('login')->with('status', 'Your password has been created. You can now sign in.');
    }

    private function findValidToken(string $token): object
    {
        $setupToken = DB::table('account_setup_tokens')
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $setupToken) {
            abort(404, 'This account setup link is invalid or has expired.');
        }

        return $setupToken;
    }
}
