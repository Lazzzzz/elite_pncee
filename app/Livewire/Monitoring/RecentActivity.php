<?php

namespace App\Livewire\Monitoring;

use App\Models\SearchHistory;
use Livewire\Component;

class RecentActivity extends Component
{
    public $recentActivities = [];

    public function mount()
    {
        $this->loadRecentActivities();
    }

    public function loadRecentActivities()
    {
        // Get the most recent activities (logins, searches, system events)
        // For now, we'll just use the search history for demonstration
        $searchActivity = SearchHistory::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($search) {
                return [
                    'type' => 'search',
                    'title' => 'Search query',
                    'description' => $search->query,
                    'status' => $search->search_type == 'regular' ? 'Query' : 'Lucky',
                    'status_color' => $search->search_type == 'regular' ? 'blue' : 'purple',
                    'user' => $search->user ? $search->user->email : 'Guest',
                    'time' => $search->created_at->diffForHumans(),
                    'created_at' => $search->created_at
                ];
            });

        // You can add more activity types here (logins, system events, etc.)

        // Combine and sort all activities
        $this->recentActivities = $searchActivity->toArray();
    }

    public function render()
    {
        return view('livewire.monitoring.recent-activity');
    }
}
