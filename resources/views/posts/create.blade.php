<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-8">
                <form @submit.prevent="submitForm" x-data="postForm()"  class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="title">Title</label>
                        <input type="text" id="title" x-model="form.title"
                            class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="content">Content</label>
                        <textarea id="content" x-model="form.content" rows="5"
                            class="form-textarea rounded-md shadow-sm mt-1 block w-full" required></textarea>
                    </div>

                    <!-- Image URL -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="image_url">Image URL</label>
                        <input type="url" id="image_url" x-model="form.image_url"
                            class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="status">Status</label>
                        <select id="status" x-model="form.status"
                            class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            @foreach (\App\Enums\PostStatusEnum::cases() as $statusOption)
                                @if ($statusOption !== \App\Enums\PostStatusEnum::PUBLISHED)
                                    <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Scheduled At -->
                    <div x-show="form.status === '1'" x-transition>
                        <label class="block font-medium text-sm text-gray-700" for="scheduled_at">Scheduled At</label>
                        <input type="datetime-local" id="scheduled_at" x-model="form.scheduled_at"
                            class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>

                    <!-- Platforms (Multi-select) -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="platforms">Platforms</label>
                        <select id="platforms" multiple x-ref="platforms" size="4"
                            class="form-multiselect rounded-md shadow-sm mt-1 block w-full"></select>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-200
                                   text-white font-semibold px-6 py-2 rounded-md shadow-md
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 transition">
                            Create Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script defer>
            function postForm() {
                return {
                    form: {
                        title: '',
                        content: '',
                        image_url: '',
                        status: '',
                        scheduled_at: '',
                        platforms: [],
                    },

                    async submitForm() {
                        this.form.platforms = Array.from(this.$refs.platforms.selectedOptions).map(opt => opt.value);

                        try {
                            const response = await axios.post('{{ route('posts.api.store') }}', this.form);

                            alert('Post created successfully!');
                            this.form = {
                                title: '',
                                content: '',
                                image_url: '',
                                status: '',
                                scheduled_at: '',
                                platforms: [],
                            };
                            this.$refs.platforms.selectedIndex = -1;

                        } catch (error) {
                            console.error('Error creating post:', error);
                            alert('Failed to create post. Please check your input.');
                        }
                    },

                    async init() {
                        const disabled_platforms = @json(auth()->user()->disabled_platforms()->pluck('platform_id'));

                        try {
                            const response = await axios.get('{{ route('platforms.index') }}');
                            const select = this.$refs.platforms;

                            response.data.forEach(platform => {
                                if (disabled_platforms.includes(platform.id)) return;

                                const option = document.createElement('option');
                                option.value = platform.id;
                                option.textContent = platform.name;
                                select.appendChild(option);
                            });

                        } catch (error) {
                            console.error('Error loading platforms:', error);
                        }
                    }
                };
            }

        </script>
    @endPushOnce
</x-app-layout>
