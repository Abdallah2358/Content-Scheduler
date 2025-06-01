<div class="p-6 bg-white rounded-lg shadow-md max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <button wire:click="previousMonth"
            class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            &larr; Prev
        </button>

        <h2 class="text-2xl font-semibold text-gray-800">
            {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
        </h2>

        <button wire:click="nextMonth"
            class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            Next &rarr;
        </button>
    </div>

    @php
        $startOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $startDay = $startOfMonth->dayOfWeekIso;
        $daysInMonth = $startOfMonth->daysInMonth;
        $today = \Carbon\Carbon::today()->format('Y-m-d');
    @endphp

    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-gray-700 select-none">
        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $index => $day)
            <div @class([
                'text-red-600' => in_array($day, ['Sat', 'Sun']),
            ])>
                {{ $day }}
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-2 mt-3 text-sm">
        {{-- Blank cells for offset --}}
        @for ($i = 1; $i < $startDay; $i++)
            <div></div>
        @endfor

        {{-- Days --}}
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateKey = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                $isToday = $dateKey === $today;
                $isWeekend = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->isWeekend();
            @endphp
            <div class="border rounded-lg p-2 h-32 flex flex-col overflow-hidden cursor-default
                           transition shadow-sm
                           {{ $isToday ? 'ring-2 ring-indigo-500 bg-indigo-50' : 'bg-white' }}
                           {{ $isWeekend ? 'bg-red-50' : '' }}
                           hover:shadow-md">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-xs {{ $isToday ? 'text-indigo-700' : 'text-gray-700' }}">
                        {{ $day }}
                    </span>
                </div>

                <div
                    class="flex-1 overflow-y-auto space-y-1 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @if(isset($postsByDate[$dateKey]))
                        <div class="flex flex-wrap gap-1">
                            @foreach($postsByDate[$dateKey] as $post)
                                <span
                                    class="inline-block max-w-full px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full truncate"
                                    title="{{ $post['title'] }}">
                                    {{ $post['title'] }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-300 italic">No posts</p>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>