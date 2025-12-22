@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6" x-data="{
        showEditEmailModal: false,
        selectedUserId: null,
        selectedUserEmail: '',
        editEmailUrl: '',
        openEditEmailModal(userId, userEmail) {
            this.selectedUserId = userId;
            this.selectedUserEmail = userEmail;
            this.editEmailUrl = '{{ route('admin.users.update-email', ':id') }}'.replace(':id', userId);
            this.showEditEmailModal = true;
            $nextTick(() => $refs.emailInput.focus());
        }
    }">
        <!-- Breadcrumb -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-semibold text-black dark:text-white">
                User Management
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                            href="{{ route('dashboard') }}">
                            Dashboard /
                        </a>
                    </li>
                    <li class="font-medium text-brand-500">Users</li>
                </ol>
            </nav>
        </div>

        <!-- Table Section -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1102px]">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    User
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Role
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Joined Date
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Status
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Actions
                                </p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs font-bold">{{ substr($user->first_name ?? $user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                {{ $user->name ?? $user->first_name . ' ' . $user->last_name }}
                                            </span>
                                            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isAdmin() ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-500' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $user->role ? ucfirst($user->role->name) : 'User' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                        <span class="block text-theme-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                    </p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit {{ $user->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit {{ $user->hasVerifiedEmail() ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500' }}">
                                            {{ $user->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center space-x-2">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-gray-500 hover:text-{{ $user->status === 'active' ? 'red' : 'green' }}-500 dark:text-gray-400 dark:hover:text-{{ $user->status === 'active' ? 'red' : 'green' }}-500" title="{{ $user->status === 'active' ? 'Suspend' : 'Activate' }} User">
                                                    @if($user->status === 'active')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <button @click="openEditEmailModal('{{ $user->id }}', '{{ $user->email }}')" class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500" title="Edit Email">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                        @endif

                                        @if(!$user->hasVerifiedEmail())
                                            <form action="{{ route('admin.users.send-verification', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Send verification email to {{ $user->email }}?');">
                                                @csrf
                                                <button type="submit" class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500" title="Send Verification Email">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.users.send-reset-link', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Send password reset link to {{ $user->email }}?');">
                                            @csrf
                                            <button type="submit" class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500" title="Send Password Reset Link">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11 9 13.536 7.464 12 4.929 14.536V17h2.472l4.243-4.243a6 6 0 018.828-5.743zM16.5 13.5V18h6v-4.5h-6z"></path></svg>
                                            </button>
                                        </form>

                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-500" title="Delete User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Edit Email Modal -->
        <div x-show="showEditEmailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs"
            x-transition.opacity style="display: none;">
            <div @click.outside="showEditEmailModal = false"
                class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900 mx-4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" 
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Edit User Email</h3>
                
                <form :action="editEmailUrl" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-5">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email Address
                        </label>
                        <input type="email" name="email" x-model="selectedUserEmail" x-ref="emailInput" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90">
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showEditEmailModal = false"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-hidden focus:ring-2 focus:ring-brand-500/50">
                            Update Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
