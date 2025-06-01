<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <form method="POST" id="create-post-form" action="{{ route('posts.api.store') }}" class="p-20"
        @submit.prevent="submitForm" 
        
        x-data="{ status: '{{ old('status', '') }}' }">
        @csrf

        <!-- Title -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Title</label>
            <input type="text" name="title" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
        </div>

        <!-- Content -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Content</label>
            <textarea name="content" rows="5" class="form-textarea rounded-md shadow-sm mt-1 block w-full"
                required></textarea>
        </div>

        <!-- Image URL -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Image URL</label>
            <input type="url" name="image_url" class="form-input rounded-md shadow-sm mt-1 block w-full">
        </div>

        <!-- Status -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Status</label>
            <select name="status" id="status" x-model="status"
                class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                @foreach (\App\Enums\PostStatusEnum::cases() as $statusOption)
                    @if ($statusOption !== \App\Enums\PostStatusEnum::PUBLISHED)
                        <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Scheduled At -->
        <div id="scheduled_at_wrapper" class="mt-4" x-show="status === '1'" x-transition>
            <label class="block font-medium text-sm text-gray-700">Scheduled At</label>
            <input type="datetime-local" {{-- :min="new Date() . toISOString() . slice(0, 16)" --}} name="scheduled_at"
                class="form-input rounded-md shadow-sm mt-1 block w-full">
        </div>

        <!-- Platforms (Multi-select) -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Platforms</label>
            <select id="platforms" name="platforms[]" multiple size="2"
                class="form-multiselect rounded-md shadow-sm mt-1 block w-full">
            </select>
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create Post</button>
        </div>
    </form>
    @pushOnce('scripts')
        <script defer type="module">
            const disabled_platforms = @json(auth()->user()->disabled_platforms()->pluck('platform_id'));
            axios.get("{{ route('platforms.index') }}")
                .then(response => {
                    const select = document.getElementById('platforms');
                    response.data.forEach(platform => {
                        if (disabled_platforms.includes(platform.id)) {
                            return;
                        }
                        const option = document.createElement('option');
                        option.value = platform.id;
                        option.textContent = platform.name;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching platforms:', error);
                });
            document.getElementById('create-post-form').addEventListener(
                'submit',
                (e) => {
                    e.target.preventDefault();
                    console.log(e.target);

                }
            )
        </script>
    @endPushOnce

</x-app-layout>