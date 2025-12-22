<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\UiController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\RootCaController;

// Public API Routes
Route::get('/api/public/ca-certificates', [\App\Http\Controllers\Api\PublicCaController::class, 'index'])->name('api.public.ca-certificates');

// Authenticated API Routes (v1)
Route::middleware('api_key')->prefix('api/v1')->group(function () {
    Route::get('/certificates', [\App\Http\Controllers\Api\CertificateApiController::class, 'index'])->name('api.v1.certificates.index');
});

Route::get('/ping', function () {
    return response()->noContent();
});

// authentication pages
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'signin'])->name('home');
    Route::get('/signin', [AuthController::class, 'signin'])->name('signin');
    Route::post('/signin', [AuthController::class, 'authenticate']);
    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'store']);

    // Password Setup (for social signup)
    Route::get('/setup-password', [AuthController::class, 'showPasswordSetup'])->name('setup-password');
    Route::post('/setup-password', [AuthController::class, 'completePasswordSetup']);

    // Forgot Password
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password', function () {
        return redirect()->route('password.request');
    })->name('password.reset.missing_token');
    
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Social Auth Redirects (context-aware)
Route::get('/auth/{provider}/redirect/{context}', [AuthController::class, 'socialRedirect'])
    ->name('auth.social')
    ->where('provider', 'github|google')
    ->where('context', 'signin|signup|connect');

// Social Auth Callbacks
Route::get('/auth/{provider}/callback', [AuthController::class, 'socialCallback'])
    ->name('auth.social.callback')
    ->where('provider', 'github|google');

// Public Certificate Routes
Route::prefix('certificate')->name('certificate.')->group(function () {
    Route::get('/download-ca/{type}', [CertificateController::class, 'downloadCa'])->name('download-ca');
    Route::get('/download-ca-bundle', [CertificateController::class, 'downloadCaBundle'])->name('download-ca-bundle');
    Route::get('/download-ca-android', [CertificateController::class, 'downloadCaAndroid'])->name('download-ca-android');
    Route::get('/download-installer', [CertificateController::class, 'downloadInstaller'])->name('download-installer');
});

// Email Verification (Public/Signed)
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Authenticated Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class])->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', [App\Http\Controllers\VerificationController::class, 'show'])->name('verification.notice');

    Route::post('/email/verification-notification', [App\Http\Controllers\VerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');

    // Suspended Page
    Route::get('/suspended', [\App\Http\Controllers\SuspendedController::class, 'index'])->name('suspended');
    
    // Logout
    Route::get('/logout', [AuthController::class, 'logoutGet'])->name('logout.get');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated & Verified Routes
    Route::middleware('verified')->group(function () {
        // dashboard pages
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Certificate routes
        Route::prefix('certificate')->name('certificate.')->group(function () {
            Route::get('/', [CertificateController::class, 'index'])->name('index');
            Route::get('/create', [CertificateController::class, 'create'])->name('create');
            Route::post('/generate', [CertificateController::class, 'generate'])->name('generate');
            // Setup CA moved to admin routes

            Route::post('/regenerate/{certificate:uuid}', [CertificateController::class, 'regenerate'])->name('regenerate');
            Route::get('/download-zip/{certificate:uuid}', [CertificateController::class, 'downloadZip'])->name('download-zip');
            Route::get('/download-p12/{certificate:uuid}', [CertificateController::class, 'downloadP12'])->name('download-p12');
            Route::get('/view/{certificate:uuid}/{type}', [CertificateController::class, 'viewFile'])->name('view');
            Route::delete('/{certificate:uuid}', [CertificateController::class, 'delete'])->name('delete');
        });



        // Admin Only Pages (No Prefix)
        Route::middleware('admin')->group(function () {
            // calender pages
            Route::get('/calendar', [PageController::class, 'calendar'])->name('calendar');

            // form pages
            Route::get('/form-elements', [UiController::class, 'formElements'])->name('form-elements');

            // tables pages
            Route::get('/basic-tables', [UiController::class, 'basicTables'])->name('basic-tables');

            // pages
            Route::get('/blank', [PageController::class, 'blank'])->name('blank');

            // chart pages
            Route::get('/line-chart', [ChartController::class, 'lineChart'])->name('line-chart');
            Route::get('/bar-chart', [ChartController::class, 'barChart'])->name('bar-chart');

            // ui elements pages
            Route::get('/alerts', [UiController::class, 'alerts'])->name('alerts');
            Route::get('/avatars', [UiController::class, 'avatars'])->name('avatars');
            Route::get('/badge', [UiController::class, 'badges'])->name('badges');
            Route::get('/buttons', [UiController::class, 'buttons'])->name('buttons');
            Route::get('/image', [UiController::class, 'images'])->name('images');
            Route::get('/videos', [UiController::class, 'videos'])->name('videos');
        });

        // profile pages
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // account settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::delete('/settings/social/{provider}', [SettingsController::class, 'disconnectSocial'])->name('settings.social.disconnect');
        Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');

        // API Keys
        Route::resource('api-keys', \App\Http\Controllers\ApiKeyController::class)->only(['index', 'store', 'destroy', 'update']);
        Route::patch('/api-keys/{apiKey}/toggle', [\App\Http\Controllers\ApiKeyController::class, 'toggle'])->name('api-keys.toggle');
        Route::post('/api-keys/{apiKey}/regenerate', [\App\Http\Controllers\ApiKeyController::class, 'regenerate'])->name('api-keys.regenerate');

        // Admin Pages
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::delete('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('users.destroy');
            Route::post('/users/{user}/send-reset-link', [App\Http\Controllers\UserManagementController::class, 'sendResetLink'])->name('users.send-reset-link');
            Route::post('/users/{user}/send-verification', [App\Http\Controllers\UserManagementController::class, 'sendVerification'])->name('users.send-verification');
            Route::patch('/users/{user}/update-email', [App\Http\Controllers\UserManagementController::class, 'updateEmail'])->name('users.update-email');
            
            // Root CA Management
            Route::get('/root-ca', [RootCaController::class, 'index'])->name('root-ca.index');
            Route::post('/root-ca/{certificate}/renew', [RootCaController::class, 'renew'])->name('root-ca.renew');
            
            // Setup Route (Admin Only)
            Route::post('/setup-ca', [CertificateController::class, 'setupCa'])->name('setup-ca'); 
        });
    });
});

// Public / Error Pages
Route::get('/error-404', [PageController::class, 'error404'])->name('error-404');

Route::get('/php', [PageController::class, 'php']);

