<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Platform Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-6">Toggle Platforms</h3>

                <div x-data="platformManager()" x-init="loadPlatforms">
                    <template x-if="platforms.length === 0">
                        <p class="text-gray-500">No platforms found.</p>
                    </template>

                    <template x-for="platform in platforms" :key="platform . id">
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <span class="text-gray-800 font-medium" x-text="platform.name"></span>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only" :checked="!platform . disabled"
                                    @change="togglePlatform(platform)" />
                                <div class="relative">
                                    <div class="w-10 h-5 rounded-full shadow-inner transition" :class="platform . disabled ? 'bg-gray-300' : 'bg-indigo-500'"></div>
                                    <div class="absolute left-0 top-0 w-5 h-5 bg-white border rounded-full shadow transform transition"
                                        :class="platform . disabled ? '' : 'translate-x-full'"></div>
                                </div>
                            </label>

                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            function platformManager() {
                return {
                    platforms: [],

                    async loadPlatforms() {
                        try {

                            const response = await axios.get('{{ route('platforms.index') }}');
                            this.platforms = response.data.map(p => ({
                                ...p,
                                disabled: @json(auth()->user()->disabled_platforms()->pluck('platform_id')).includes(p.id)
                            }));
                        } catch (error) {
                            console.error('Failed to load platforms', error);
                        }
                    },

                    async togglePlatform(platform) {
                        try {
                            await axios.get(`/api/platforms/${platform.id}/toggle`);
                            platform.disabled = !platform.disabled;
                        } catch (error) {
                            console.error('Toggle failed', error);
                            alert('Failed to toggle platform.');
                        }
                    }
                };
            }
        </script>
    @endPushOnce
</x-app-layout>