@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Open New Ticket
            </h2>
            <nav class="mt-1">
                <ol class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('support.index') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Support Tickets
                        </a>
                    </li>
                    <li>
                        <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </li>
                    <li class="font-medium text-gray-800 dark:text-white/90">Open Ticket</li>
                </ol>
            </nav>
        </div>
    </div>

    <x-common.component-card :title="$title">
        <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Subject -->
                <div class="col-span-2">
                    <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                    <input type="text" name="subject" id="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white" placeholder="Briefly describe your issue" required>
                    @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                    <select name="category" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white" required>
                        <option value="Technical">Technical Support</option>
                        <option value="Billing">Billing & Subscription</option>
                        <option value="General">General Inquiry</option>
                        <option value="Feature Request">Feature Request</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                    <select name="priority" id="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white" required>
                        <option value="low">Low - General Question</option>
                        <option value="medium" selected>Medium - Need Assistance</option>
                        <option value="high">High - Urgent Issue</option>
                    </select>
                    @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Message -->
                <div class="col-span-2">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                    <textarea name="message" id="message" rows="6" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white" placeholder="Please provide detailed information about your issue..." required></textarea>
                    @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Attachment -->
                <div class="col-span-2" x-data="{ fileName: '' }">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="attachment">Attachment (Optional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 p-4 text-center" x-show="!fileName">
                                <svg class="w-8 h-8 mb-3 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF or DOCX (MAX. 2MB)</p>
                            </div>
                             <div class="flex flex-col items-center justify-center pt-5 pb-6 p-4 text-center" x-show="fileName" style="display: none;">
                                <svg class="w-8 h-8 mb-3 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="mb-1 text-sm text-gray-700 dark:text-gray-300 truncate max-w-xs" x-text="fileName"></p>
                                <p class="text-xs text-brand-500 dark:text-brand-400 font-medium">Click to change file</p>
                            </div>
                            <input id="attachment" name="attachment" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                                </label>
                            </div>
                            @error('attachment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-brand-500 dark:hover:bg-brand-600 dark:focus:ring-brand-800">
                    Submit Ticket
                </button>
            </div>
        </form>
    </x-common.component-card>
@endsection
