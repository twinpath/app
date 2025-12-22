<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Traits\RevokesSocialTokens;

class SettingsController extends Controller
{
    use RevokesSocialTokens;
    /**
     * Display account settings.
     */
    public function index()
    {
        return view('pages.settings', [
            'title' => 'Account Settings',
            'user' => Auth::user()->load(['loginHistories' => fn($q) => $q->latest('login_at')]),
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }



    /**
     * Disconnect a social account.
     */
    public function disconnectSocial(string $provider)
    {
        $user = Auth::user();

        if (!in_array($provider, ['google', 'github'])) {
            return back()->with('error', 'Invalid provider.');
        }

        // Revoke token on provider side if exists
        $this->revokeSocialToken($provider, $user->{$provider . '_token'});

        $user->update([
            "{$provider}_id" => null,
            "{$provider}_token" => null,
            "{$provider}_refresh_token" => null,
        ]);

        return back()->with('success', ucfirst($provider) . ' account disconnected successfully.');
    }

    /**
     * Delete user account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'string', 'in:Yes I will delete my account'],
        ]);

        $user = $request->user();

        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be deleted.');
        }

        // Revoke connected social tokens before deletion
        foreach (['google', 'github'] as $provider) {
            if ($user->{$provider . '_id'}) {
                $this->revokeSocialToken($provider, $user->{$provider . '_token'});
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin')->with('success', 'Your account has been deleted.');
    }
}
