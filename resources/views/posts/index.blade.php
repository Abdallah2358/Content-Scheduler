<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Posts Calendar</h3>
                    <a href="{{ route('posts.create') }}"
                        class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                               transition">
                        Create Post
                    </a>
                </div>

                <!-- Calendar Livewire Component -->
                <livewire:calendar-view />
            </div>

        </div>
    </div>
</x-app-layout>
