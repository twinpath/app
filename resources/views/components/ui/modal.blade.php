@props([
    'isOpen' => false,
    'showCloseButton' => true,
    'containerClass' => 'max-w-[500px]',
])

<div {{ $attributes->merge([
    'x-data' => '{
        open: ' . ($isOpen ? 'true' : 'false') . ',
        init() {
            this.$watch(\'open\', value => {
                if (value) {
                    document.body.style.overflow = \'hidden\';
                } else {
                    document.body.style.overflow = \'unset\';
                }
            });
        }
    }'
]) }}
x-modelable="open"
x-show="open"
x-cloak
@keydown.escape.window="open = false"
class="relative z-99999" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <!-- Backdrop -->
    <div x-show="open" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" 
        aria-hidden="true">
    </div>

    <!-- Scrollable Container -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <!-- Modal Panel -->
            <div @click.stop x-show="open" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all dark:bg-gray-900 sm:my-8 w-full {{ $containerClass }}">
                
                <!-- Close Button -->
                @if ($showCloseButton)
                    <div class="absolute right-4 top-4 z-10 hidden sm:block">
                        <button @click="open = false" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:bg-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Modal Content -->
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none;
    }
</style>
