<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Carbon\Carbon;

class CalendarView extends Component
{
    public $currentMonth;
    public $currentYear;
    public $postsByDate = [];
    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadPosts();
    }
    public function loadPosts()
    {
        $start = Carbon::create($this->currentYear, $this->currentMonth)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $posts = Post::whereBetween('scheduled_at', [$start, $end])->latest()->get();
        $this->postsByDate = $posts
            ->groupBy(function ($post) {
                return Carbon::parse($post->scheduled_at)->format('Y-m-d');
            })->toArray();
            
    }

    public function render()
    {
        return view('livewire.calendar-view');
    }
    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadPosts();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadPosts();
    }
}
