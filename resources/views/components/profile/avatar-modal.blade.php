@props(['user', 'show'])

<x-ui.modal 
    @open-avatar-modal.window="open = true" 
    x-model="{{ $show }}"
    :isOpen="false" 
    containerClass="max-w-[600px]">
    <div 
        x-data="{ 
            cropper: null,
            imageSrc: null,
            isDragging: false,
            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                }
                const image = this.$refs.cropImage;
                this.cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            },
            handleFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageSrc = e.target.result;
                    this.$nextTick(() => {
                        this.initCropper();
                    });
                };
                reader.readAsDataURL(file);
            },
            saveAvatar() {
                if (!this.cropper) return;
                const canvas = this.cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });
                canvas.toBlob((blob) => {
                    const formData = new FormData();
                    formData.append('avatar', blob, 'avatar.png');
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PATCH');
                    
                    fetch('{{ route('profile.update') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Failed to upload image');
                        }
                    });
                }, 'image/png');
            }
        }"
        class="relative w-full overflow-hidden rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <div class="mb-6">
            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">
                Update Profile Picture
            </h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Upload a new photo and crop it to fit.
            </p>
        </div>

        <!-- Drag & Drop Area -->
        <div x-show="!imageSrc" 
            @dragover.prevent="isDragging = true" 
            @dragleave.prevent="isDragging = false"
            @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0])"
            :class="isDragging ? 'border-brand-500 bg-brand-50' : 'border-gray-300 dark:border-gray-700'"
            class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed p-10 transition-colors">
            <svg class="mb-4 text-gray-400" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            <p class="mb-2 text-sm text-gray-700 dark:text-gray-300">
                Drag and drop your image here, or
            </p>
            <button @click="$refs.fileInput.click()" class="text-brand-500 hover:text-brand-600 text-sm font-medium">
                browse files
            </button>
            <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="handleFile($event.target.files[0])">
        </div>

        <!-- Cropping Area -->
        <div x-show="imageSrc" class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800">
            <div class="max-h-[400px] w-full">
                <img x-ref="cropImage" :src="imageSrc" class="max-w-full">
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
            <button @click="open = false; imageSrc = null; if(cropper) cropper.destroy();" type="button"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                Cancel
            </button>
            <button x-show="imageSrc" @click="saveAvatar" type="button"
                class="rounded-lg bg-brand-500 px-6 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Save Photo
            </button>
        </div>
    </div>
</x-ui.modal>
