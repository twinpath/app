<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Traits\RevokesSocialTokens;

class UserManagementController extends Controller
{
    use RevokesSocialTokens;
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        
        return view('pages.admin.users.index', [
            'users' => $users
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Revoke Google Token
        if ($user->google_token) {
            $this->revokeSocialToken('google', $user->google_token);
        }

        // Revoke GitHub Token
        if ($user->github_token) {
            $this->revokeSocialToken('github', $user->github_token);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function sendResetLink(User $user)
    {
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        $user->sendPasswordResetNotification($token);

        return back()->with('success', 'Password reset link sent to ' . $user->email);
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $newStatus = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->update(['status' => $newStatus]);
        
        $message = $newStatus === 'suspended' 
            ? 'User account has been suspended.' 
            : 'User account has been activated.';

        return back()->with('success', $message);
    }

    public function sendVerification(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('message', 'User is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent to ' . $user->email);
    }

    public function updateEmail(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot update your own email from here.');
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->forceFill([
            'email' => $validated['email'],
            'email_verified_at' => null, // Reset verification status
        ])->save();

        return back()->with('success', 'User email updated. Verification status has been reset.');
    }
}
