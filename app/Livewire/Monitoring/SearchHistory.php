<?php

namespace App\Livewire\Monitoring;

use App\Models\SearchHistory as SearchHistoryModel;
use Livewire\Component;

class SearchHistory extends Component
{
    public $recentSearches = [];

    public function mount()
    {
        $this->loadSearchHistory();
    }

    public function loadSearchHistory()
    {
        $this->recentSearches = SearchHistoryModel::with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.monitoring.search-history');
    }
}
