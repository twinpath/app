@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6" x-data="{ 
        showProfileModal: false,
        showAvatarModal: false,
        showPasswordModal: false,
        showSocialModal: false,
        showDeleteModal: false,
        socialProvider: '',
        socialProviderName: '',
        socialAction: 'connect',
        socialForm: null,
        socialConnectUrl: '',
        deleteConfirmation: '',
        activeSection: '#profile',
        mobileNavOpen: false,
        init() {
            // Smooth scrolling behavior and active section tracking
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeSection = '#' + entry.target.id;
                    }
                });
            }, { threshold: 0.3 });

            ['profile', 'security', 'social', 'access', 'danger'].forEach(id => {
                const el = document.getElementById(id);
                if (el) observer.observe(el);
            });
            
            // Check initial hash
            if (window.location.hash) {
                this.activeSection = window.location.hash;
            }
        },
        submitPasswordForm() {
            this.$refs.passwordForm.submit();
        },
        confirmSocialDisconnect(providerName, providerSlug, form) {
            this.socialProviderName = providerName;
            this.socialProvider = providerSlug;
            this.socialAction = 'disconnect';
            this.socialForm = form;
            this.showSocialModal = true;
        },
        confirmSocialConnect(providerName, url) {
            // Direct redirect without modal for connect
            window.location.href = url;
        },
        submitSocialForm() {
            if (this.socialAction === 'connect') {
                window.location.href = this.socialConnectUrl;
            } else {
                this.socialForm.submit();
            }
        },
        saveProfile() {
            this.$refs.profileForm.submit();
        }
    }">
        <style>
            html {
                scroll-behavior: smooth;
            }
            [id] {
                scroll-margin-top: 100px;
            }
            @media (max-width: 1023px) {
                [id] {
                    scroll-margin-top: 140px;
                }
            }
        </style>
        <!-- Breadcrumb -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                Account Settings
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                            href="{{ route('dashboard') }}">
                            Dashboard /
                        </a>
                    </li>
                    <li class="font-medium text-brand-500">Settings</li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Sidebar-like navigation for settings -->
            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 sticky top-24 z-10">
                    <div class="p-4 sm:p-6">
                        <!-- Mobile Header -->
                        <div class="lg:hidden flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Settings Section</span>
                            <button @click="mobileNavOpen = !mobileNavOpen" class="flex items-center gap-1.5 text-xs font-semibold text-brand-500 hover:text-brand-600 transition-colors">
                                <span x-text="mobileNavOpen ? 'Collapse Menu' : 'Change Section'"></span>
                                <svg class="transition-transform duration-200" :class="mobileNavOpen ? 'rotate-180' : ''" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                        </div>

                        <ul class="flex flex-col gap-1 transition-all duration-300">
                            <!-- Profile Information -->
                            <li x-show="activeSection === '#profile' || mobileNavOpen || window.innerWidth >= 1024" 
                                x-collapse.duration.300ms>
                                <a href="#profile"
                                    @click="if(window.innerWidth < 1024) mobileNavOpen = false"
                                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                                    :class="activeSection === '#profile' 
                                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-500 shadow-sm' 
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-white/90'">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Profile Information
                                    <template x-if="activeSection === '#profile' && !mobileNavOpen && window.innerWidth < 1024">
                                        <div class="ml-auto animate-pulse h-1.5 w-1.5 rounded-full bg-brand-500"></div>
                                    </template>
                                </a>
                            </li>

                            <!-- Security & Password -->
                            <li x-show="activeSection === '#security' || mobileNavOpen || window.innerWidth >= 1024"
                                x-collapse.duration.300ms>
                                <a href="#security"
                                    @click="if(window.innerWidth < 1024) mobileNavOpen = false"
                                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                                    :class="activeSection === '#security' 
                                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-500 shadow-sm' 
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-white/90'">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    Security & Password
                                    <template x-if="activeSection === '#security' && !mobileNavOpen && window.innerWidth < 1024">
                                        <div class="ml-auto animate-pulse h-1.5 w-1.5 rounded-full bg-brand-500"></div>
                                    </template>
                                </a>
                            </li>

                            <!-- Connected Accounts -->
                            <li x-show="activeSection === '#social' || mobileNavOpen || window.innerWidth >= 1024"
                                x-collapse.duration.300ms>
                                <a href="#social"
                                    @click="if(window.innerWidth < 1024) mobileNavOpen = false"
                                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                                    :class="activeSection === '#social' 
                                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-500 shadow-sm' 
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-white/90'">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                    Connected Accounts
                                    <template x-if="activeSection === '#social' && !mobileNavOpen && window.innerWidth < 1024">
                                        <div class="ml-auto animate-pulse h-1.5 w-1.5 rounded-full bg-brand-500"></div>
                                    </template>
                                </a>
                            </li>

                            <!-- Account Access -->
                            <li x-show="activeSection === '#access' || mobileNavOpen || window.innerWidth >= 1024"
                                x-collapse.duration.300ms>
                                <a href="#access"
                                    @click="if(window.innerWidth < 1024) mobileNavOpen = false"
                                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                                    :class="activeSection === '#access' 
                                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-500 shadow-sm' 
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-white/90 dark:hover:bg-white/[0.03]'">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    Account Access
                                    <template x-if="activeSection === '#access' && !mobileNavOpen && window.innerWidth < 1024">
                                        <div class="ml-auto animate-pulse h-1.5 w-1.5 rounded-full bg-brand-500"></div>
                                    </template>
                                </a>
                            </li>

                            <div class="my-2 h-px bg-gray-100 dark:bg-gray-800" x-show="mobileNavOpen || window.innerWidth >= 1024"></div>

                            <!-- Danger Zone -->
                            <li x-show="activeSection === '#danger' || mobileNavOpen || window.innerWidth >= 1024"
                                x-collapse.duration.300ms>
                                <a href="#danger"
                                    @click="if(window.innerWidth < 1024) mobileNavOpen = false"
                                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                                    :class="activeSection === '#danger' 
                                        ? 'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400 shadow-sm' 
                                        : 'text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10'">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    Danger Zone
                                    <template x-if="activeSection === '#danger' && !mobileNavOpen && window.innerWidth < 1024">
                                        <div class="ml-auto animate-pulse h-1.5 w-1.5 rounded-full bg-error-500"></div>
                                    </template>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Messages -->
                <!-- Status Messages handled globally -->

                <!-- Profile Information Card -->
                <div id="profile" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-200 p-4 sm:px-6 dark:border-gray-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Profile Information
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Update your personal details and avatar.
                            </p>
                        </div>
                        <button @click="showProfileModal = true" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                            Edit Profile
                        </button>
                    </div>

                    <div class="p-4 sm:p-6 relative">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <!-- Avatar Section -->
                            <div class="flex flex-col items-center gap-3">
                                <div class="group relative h-24 w-24 overflow-hidden rounded-full border border-gray-200 dark:border-gray-800">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/user/owner.jpg') }}" alt="user" class="h-full w-full object-cover" />
                                    <button @click="showAvatarModal = true" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                                        <svg class="text-white" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                                <button @click="showAvatarModal = true" class="text-xs font-medium text-gray-500 hover:text-brand-500">Change Photo</button>
                            </div>

                            <!-- Info Grid -->
                            <div class="grid grow grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-6">
                                <div>
                                    <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Email Address</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $user->email }}
                                        @if($user->hasVerifiedEmail())
                                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500">
                                                Unverified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Phone Number</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->phone ?? 'Not set' }}</p>
                                </div>
                                <div>
                                    <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Bio</p>
                                    <p class="text-sm line-clamp-1 font-medium text-gray-800 dark:text-white/90">{{ $user->bio ?? 'Not set' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div id="security" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-200 p-4 sm:px-6 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Change Password
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Update your password to keep your account secure.
                        </p>
                    </div>

                    <div class="p-4 sm:p-6">
                        <form x-ref="passwordForm" action="{{ route('settings.password') }}" method="POST" class="space-y-5" @submit.prevent="showPasswordModal = true">
                            @csrf
                            <!-- Old Password -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Current Password
                                </label>
                                <div x-data="{ show: false }" class="relative">
                                    <input :type="show ? 'text' : 'password'" name="current_password" required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg x-show="!show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg x-show="show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    New Password
                                </label>
                                <div x-data="{ show: false }" class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg x-show="!show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg x-show="show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Confirm New Password
                                </label>
                                <div x-data="{ show: false }" class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg x-show="!show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg x-show="show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-end pt-3">
                                <button type="submit"
                                    class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-hidden focus:ring-2 focus:ring-brand-500/50">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Connected Accounts Card -->
                <div id="social" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-200 p-4 sm:px-6 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Connected Accounts
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Manage your linked social accounts.
                        </p>
                    </div>

                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Google -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 dark:border-gray-800">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" /><path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" /><path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" /><path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-white/90">Google</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->google_id ? 'Connected' : 'Not connected' }}
                                    </p>
                                </div>
                            </div>
                            @if ($user->google_id)
                                <form action="{{ route('settings.social.disconnect', 'google') }}" method="POST" x-ref="disconnectGoogleForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmSocialDisconnect('Google', 'google', $refs.disconnectGoogleForm)" class="text-sm font-medium text-error-500 hover:text-error-600">
                                        Disconnect
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('auth.social', ['provider' => 'google', 'context' => 'connect']) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                                    Connect
                                </a>
                            @endif
                        </div>

                        <!-- GitHub -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-white/90">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-white/90">GitHub</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->github_id ? 'Connected' : 'Not connected' }}
                                    </p>
                                </div>
                            </div>
                            @if ($user->github_id)
                                <form action="{{ route('settings.social.disconnect', 'github') }}" method="POST" x-ref="disconnectGitHubForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmSocialDisconnect('GitHub', 'github', $refs.disconnectGitHubForm)" class="text-sm font-medium text-error-500 hover:text-error-600">
                                        Disconnect
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('auth.social', ['provider' => 'github', 'context' => 'connect']) }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">
                                    Connect
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Access Card -->
                <div id="access" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-200 p-4 sm:px-6 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Account Access
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Recent login activity on your account (Max 10 records, last 1 month).
                        </p>
                    </div>

                    <div class="p-4 sm:p-6">
                        @forelse($user->loginHistories as $history)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-white/[0.03] text-gray-700 dark:text-white/90">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            @if($history->provider === 'google')
                                                <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" /><path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" /><path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" /><path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335" />
                                            @elseif($history->provider === 'github')
                                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                                            @else
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h4M10 17l5-5-5-5M13.8 12H3"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $history->login_at->format('d M Y, H:i') }}
                                            </p>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $history->login_at->diffForHumans() }})
                                            </span>
                                        </div>
                                        @if($history->ip_address)
                                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="font-mono bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-gray-600 dark:text-gray-300">
                                                    {{ $history->ip_address }}
                                                </span>
                                                @if($history->user_agent)
                                                    <span class="truncate max-w-[200px]" title="{{ $history->user_agent }}">
                                                        Login on {{ Str::limit($history->user_agent, 40) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize
                                        {{ $history->provider === 'google' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 
                                          ($history->provider === 'github' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : 
                                          'bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-300') }}">
                                        {{ ucfirst($history->provider) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic py-4 text-center">No login history recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Danger Zone Card -->
                <div id="danger" class="rounded-2xl border border-error-200 bg-white dark:border-error-500/30 dark:bg-gray-900">
                    <div class="border-b border-error-100 p-4 sm:px-6 dark:border-error-500/20">
                        <h3 class="text-lg font-semibold text-error-600 dark:text-error-400">
                            Danger Zone
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Irreversible actions for your account.
                        </p>
                    </div>

                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-800 dark:text-white/90">Delete Account</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Once you delete your account, there is no going back. Please be certain.
                                </p>
                            </div>
                            <button @click="showDeleteModal = true" class="rounded-lg bg-error-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-error-600 focus:outline-hidden focus:ring-2 focus:ring-error-500/50">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->

        <!-- Refactored Modals -->
        <x-profile.edit-modal :user="$user" show="showProfileModal" />
        <x-profile.avatar-modal :user="$user" show="showAvatarModal" />
        <x-settings.password-modal show="showPasswordModal" />
        <x-settings.social-modal show="showSocialModal" socialProvider="socialProviderName" submitAction="submitSocialForm()" />
        <x-settings.delete-account-modal show="showDeleteModal" deleteConfirmation="deleteConfirmation" />
    </div>
@endsection
