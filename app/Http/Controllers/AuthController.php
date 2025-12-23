<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\LoginHistory;
use App\Traits\RevokesSocialTokens;

use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    use RevokesSocialTokens;

    public function signin()
    {
        return view('pages.auth.signin', ['title' => 'Sign In']);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'cf-turnstile-response' => ['required', new \RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile],
        ]);

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and is social-only (no password set)
        if ($user && !$user->password) {
            return back()->withErrors([
                'email' => 'This account was created using social login. Please sign in with ' . 
                          ($user->google_id ? 'Google' : 'GitHub') . ' instead.',
            ])->onlyInput('email');
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            
            $this->recordLogin($user, 'credentials');

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function signup()
    {
        return view('pages.auth.signup', ['title' => 'Sign Up']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'terms' => 'required|accepted',
            'cf-turnstile-response' => ['required', new \RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile],
        ]);

        $roleInfo = \App\Models\Role::where('name', 'customer')->first();

        // Generate default avatar
        $avatarPath = $this->generateDefaultAvatar($validated['fname'] . ' ' . ($validated['lname'] ?? ''), $validated['email']);

        $user = User::create([
            'first_name' => $validated['fname'],
            'last_name' => $validated['lname'],
            'email' => $validated['email'],
            'avatar' => $avatarPath,
            'password' => Hash::make($validated['password']),
            'role_id' => $roleInfo ? $roleInfo->id : null,
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->route('verification.notice');
    }

    /**
     * Redirect to social provider
     */
    public function socialRedirect($provider, $context)
    {
        session(['social_auth_context' => $context]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback
     */
    public function socialCallback($provider, Request $request)
    {
        $context = session('social_auth_context', 'signin');
        session()->forget('social_auth_context');
        
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            \Log::error("Social login failed for {$provider}: " . get_class($e) . ' - ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('signin')->with('error', 'Authentication failed. Please try again.');
        }

        // Find user by social ID or email
        $user = User::where($provider . '_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($user) {
            // Context: Connect
            if ($context === 'connect') {
                if (Auth::check()) {
                    $currentUser = Auth::user();
                    if ($user->id !== $currentUser->id) {
                        return redirect()->route('settings')->with('error', "This {$provider} account is already linked to another user.");
                    }
                }
            }

            // Update social tokens/ID if not set or changed
            $user->update([
                $provider . '_id' => $socialUser->getId(),
                $provider . '_token' => $socialUser->token,
                $provider . '_refresh_token' => $socialUser->refreshToken ?? $user->{$provider . '_refresh_token'},
                'email_verified_at' => $user->email_verified_at ?? now(), // Auto-verify if not already
            ]);

            // Login user if not already auth or if in auth context
            if ($context !== 'connect') {
                Auth::login($user);
                $this->recordLogin($user, $provider);
                return redirect()->intended('dashboard');
            }

            return redirect()->route('settings')->with('success', "{$provider} account connected successfully.");
        } else {
            // New User or Connect (but account doesn't exist)
            
            if ($context === 'connect' && Auth::check()) {
                $user = Auth::user();
                $user->update([
                    $provider . '_id' => $socialUser->getId(),
                    $provider . '_token' => $socialUser->token,
                    $provider . '_refresh_token' => $socialUser->refreshToken,
                ]);
                return redirect()->route('settings')->with('success', "{$provider} account connected successfully.");
            }

            if ($context === 'signin') {
                return redirect()->route('signin')->with('error', 'No account found with this email. Please sign up first.');
            }

            // Signup flow - Store in session and redirect to password setup
            $nameParts = explode(' ', $socialUser->getName() ?? '', 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            
            $avatarPath = $this->downloadSocialAvatar($socialUser->getAvatar(), $socialUser->getEmail());

            session([
                'needs_password_setup' => true,
                'social_signup_provider' => $provider,
                'social_signup_email' => $socialUser->getEmail(),
                'social_signup_first_name' => $firstName,
                'social_signup_last_name' => $lastName,
                'social_signup_avatar_path' => $avatarPath,
                'social_signup_provider_id' => $socialUser->getId(),
                'social_signup_provider_token' => $socialUser->token,
                'social_signup_provider_refresh_token' => $socialUser->refreshToken ?? null,
            ]);

            return redirect()->route('setup-password');
        }
    }

    /**
     * Download social avatar and save to storage
     */
    protected function downloadSocialAvatar($avatarUrl, $userEmail)
    {
        try {
            // Generate unique filename
            $filename = 'avatars/' . Str::slug($userEmail) . '_' . time() . '.jpg';
            
            // Download image content
            $imageContent = file_get_contents($avatarUrl);
            
            if ($imageContent === false) {
                // Determine name for fallback
                // We don't have the name here easily, so we use email part
                $name = explode('@', $userEmail)[0];
                return $this->generateDefaultAvatar($name, $userEmail);
            }

            // Save to storage
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            // If download fails, return generated avatar
            \Log::error('Failed to download social avatar: ' . $e->getMessage());
            $name = explode('@', $userEmail)[0];
            return $this->generateDefaultAvatar($name, $userEmail);
        }
    }

    /**
     * Generate default avatar using Laravolt
     */
    protected function generateDefaultAvatar($name, $email)
    {
        try {
            $filename = 'avatars/' . Str::slug($email) . '_' . time() . '.png';
            $avatar = \Laravolt\Avatar\Facade::create($name)->getImageObject()->encode(new \Intervention\Image\Encoders\PngEncoder());
            Storage::disk('public')->put($filename, $avatar);
            return $filename;
        } catch (\Exception $e) {
            \Log::error('Failed to generate default avatar: ' . $e->getMessage());
            return null; // Ultimate fallback to null
        }
    }

    /**
     * Show password setup page for social signup users
     */
    public function showPasswordSetup()
    {
        // Check if user has the session flag
        if (!session('needs_password_setup')) {
            return redirect()->route('dashboard');
        }

        return view('pages.auth.setup-password', ['title' => 'Setup Password']);
    }

    /**
     * Complete password setup for social signup users
     */
    public function completePasswordSetup(Request $request)
    {
        // Validate session
        if (!session('needs_password_setup')) {
            return redirect()->route('dashboard');
        }

        // Validate input
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get data from session
        $provider = session('social_signup_provider');
        $email = session('social_signup_email');
        $firstName = session('social_signup_first_name');
        $lastName = session('social_signup_last_name');
        $avatarPath = session('social_signup_avatar_path');
        $providerId = session('social_signup_provider_id');
        $providerToken = session('social_signup_provider_token');
        $providerRefreshToken = session('social_signup_provider_refresh_token');

        // Create user
        $roleInfo = \App\Models\Role::where('name', 'customer')->first();

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'avatar' => $avatarPath,
            'password' => Hash::make($validated['password']),
            'role_id' => $roleInfo ? $roleInfo->id : null,
            $provider . '_id' => $providerId,
            $provider . '_token' => $providerToken,
            $provider . '_refresh_token' => $providerRefreshToken,
            'email_verified_at' => now(), // Auto-verify email from trusted social provider
        ]);

        $this->recordLogin($user, $provider);

        // Clear session data
        session()->forget([
            'needs_password_setup',
            'social_signup_provider',
            'social_signup_name',
            'social_signup_email',
            'social_signup_first_name',
            'social_signup_last_name',
            'social_signup_avatar',
            'social_signup_avatar_path',
            'social_signup_provider_id',
            'social_signup_provider_token',
            'social_signup_provider_refresh_token',
        ]);

        // Login user
        Auth::login($user);

        \Log::info('Social signup completed with password setup', ['user_id' => $user->id]);

        return redirect()->route('dashboard')->with('success', 'Welcome! Your account has been created successfully.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }

    /**
     * Handle manual GET access to logout (e.g. typing in URL)
     */
    public function logoutGet()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('error', 'For security reasons, you cannot logout by typing the URL. Please use the Logout button.');
        }
        return redirect()->route('signin');
    }



    /**
     * Record login history and enforce limits
     */
    protected function recordLogin(User $user, $provider)
    {
        $loginAt = now();
        
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'provider' => $provider,
            'login_at' => $loginAt,
        ]);

        // Cleanup old records (older than 1 month)
        LoginHistory::where('user_id', $user->id)
            ->where('login_at', '<', $loginAt->subMonth())
            ->delete();

        // Cleanup excess records (keep only latest 10)
        $idsToKeep = LoginHistory::where('user_id', $user->id)
            ->latest('login_at')
            ->take(10)
            ->pluck('id');

        LoginHistory::where('user_id', $user->id)
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
