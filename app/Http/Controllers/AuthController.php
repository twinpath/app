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
        ]);

        $roleInfo = \App\Models\Role::where('name', 'customer')->first();

        $user = User::create([
            'first_name' => $validated['fname'],
            'last_name' => $validated['lname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $roleInfo ? $roleInfo->id : null,
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->route('verification.notice');
    }

    /**
     * Unified social redirect method
     */
    public function socialRedirect($provider, $context)
    {
        // Store context in session for callback
        session(['social_auth_context' => $context]);
        
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Unified social callback method
     */
    public function socialCallback($provider)
    {
        if (request()->has('error')) {
            \Log::info('Social auth error: ' . request()->get('error'));
            return redirect()->route('signin')->with('error', 'Authentication failed or was cancelled.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
            \Log::info('Social user retrieved', ['provider' => $provider, 'email' => $socialUser->email]);
        } catch (\Exception $e) {
            \Log::error('Socialite error: ' . $e->getMessage());
            return redirect()->route('signin')->with('error', 'Failed to authenticate with ' . ucfirst($provider) . '.');
        }

        $context = session('social_auth_context', 'signin');
        \Log::info('Social auth context', ['context' => $context, 'is_authenticated' => Auth::check()]);
        session()->forget('social_auth_context');

        // Handle 'connect' context - user wants to link social account
        if ($context === 'connect') {
            if (!Auth::check()) {
                \Log::warning('Connect attempt without authentication');
                return redirect()->route('signin')->with('error', 'Please sign in first to connect your social account.');
            }
            \Log::info('Handling social connect from settings');
            return $this->connectSocialAccount($provider, $socialUser);
        }

        // If user is already authenticated (shouldn't happen for signin/signup)
        if (Auth::check()) {
            \Log::info('User already authenticated, treating as connect');
            return $this->connectSocialAccount($provider, $socialUser);
        }

        // Handle based on context
        if ($context === 'signin') {
            \Log::info('Handling social signin');
            return $this->handleSocialSignin($provider, $socialUser);
        } else {
            \Log::info('Handling social signup');
            return $this->handleSocialSignup($provider, $socialUser);
        }
    }

    /**
     * Connect social account to existing authenticated user
     */
    protected function connectSocialAccount($provider, $socialUser)
    {
        $user = Auth::user();
        
        $updateData = [
            $provider . '_id' => $socialUser->id,
            $provider . '_token' => $socialUser->token,
            $provider . '_refresh_token' => $socialUser->refreshToken,
        ];

        // Ensure update persists
        $user->forceFill($updateData)->save();
        
        $this->recordLogin($user, $provider);
        
        \Log::info('Social account connected for user', ['user_id' => $user->id, 'provider' => $provider]);
        
        return redirect()->route('settings')->with('success', ucfirst($provider) . ' account connected successfully.');
    }

    /**
     * Handle social signin (only for existing users)
     */
    protected function handleSocialSignin($provider, $socialUser)
    {
        // Try to find user by provider ID first
        $user = User::where($provider . '_id', $socialUser->id)->first();

        // If user not found by ID
        if (!$user) {
            // Check if user exists by email just to provide a helpful error message
            if ($socialUser->email && User::where('email', $socialUser->email)->exists()) {
                 $this->revokeSocialToken($provider, $socialUser->token);
                 return redirect()->route('signin')->with('error', "An account with this email exists but is not connected to " . ucfirst($provider) . ". Please log in with your password and connect your social account from Settings.");
            }

            // Genuinely no account found
            $this->revokeSocialToken($provider, $socialUser->token);
            return redirect()->route('signin')->with('error', 'No account found with this ' . ucfirst($provider) . ' account. Please sign up first.');
        }

        // Proceed with login for connected user

        // Update login tracking


        $this->recordLogin($user, $provider);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Handle social signup (only for new users)
     */
    protected function handleSocialSignup($provider, $socialUser)
    {
        // Check if user already exists by email
        $existingUser = User::where('email', $socialUser->email)->first();

        if ($existingUser) {
            return redirect()->route('signup')->withErrors([
                'email' => 'An account with this email already exists. Please sign in instead.',
            ]);
        }

        // Download and save avatar
        $avatarPath = null;
        if ($socialUser->avatar) {
            $avatarPath = $this->downloadSocialAvatar($socialUser->avatar, $socialUser->email);
        }

        // Parse name
        $nameParts = explode(' ', $socialUser->name ?? $socialUser->email, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Store user data in session for password setup
        session([
            'needs_password_setup' => true,
            'social_signup_provider' => $provider,
            'social_signup_name' => $socialUser->name ?? $socialUser->email,
            'social_signup_email' => $socialUser->email,
            'social_signup_first_name' => $firstName,
            'social_signup_last_name' => $lastName,
            'social_signup_avatar' => $avatarPath ? asset('storage/' . $avatarPath) : null,
            'social_signup_avatar_path' => $avatarPath,
            'social_signup_provider_id' => $socialUser->id,
            'social_signup_provider_token' => $socialUser->token,
            'social_signup_provider_refresh_token' => $socialUser->refreshToken,
        ]);

        \Log::info('Social signup - redirecting to password setup', ['email' => $socialUser->email]);

        // Redirect to password setup page
        return redirect()->route('setup-password');
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
                return null;
            }

            // Save to storage
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            // If download fails, return null (user will have default avatar)
            \Log::error('Failed to download social avatar: ' . $e->getMessage());
            return null;
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
