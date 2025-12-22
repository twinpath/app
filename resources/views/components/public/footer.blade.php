<footer class="py-12 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex flex-wrap justify-center gap-6 mb-6 text-gray-500 dark:text-gray-400 text-sm font-medium">
            <a href="{{ route('contact') }}" class="hover:text-brand-500 transition-colors">Contact</a>
            <a href="{{ route('legal.show', 'terms-and-conditions') }}" class="hover:text-brand-500 transition-colors">Terms and Conditions</a>
            <a href="{{ route('legal.show', 'privacy-policy') }}" class="hover:text-brand-500 transition-colors">Privacy Policy</a>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Built for security and performance.
        </p>
    </div>
</footer>
