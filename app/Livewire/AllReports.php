<?php

namespace App\Livewire;

use App\Models\Rapport;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;

class AllReports extends Component
{
    use WithPagination;

    public $sortBy = 'rapport_id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showPdfViewer = false;
    public $currentPdfUrl = '';
    public $currentPdfTitle = '';

    protected $queryString = [
        'sortBy' => ['except' => 'rapport_id'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 25],
    ];

    protected $listeners = ['keydown.escape' => 'closePdfViewer'];

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function logPdfViewAndOpen($rapportId, $nomRapport, $urlFichier)
    {
        if (Auth::check()) {
            SearchHistory::create([
                'query' => 'PDF View: ' . $nomRapport,
                'user_id' => Auth::id(),
                'search_type' => 'pdf_view',
                'results' => [
                    'viewed_rapport_id' => $rapportId,
                    'viewed_nom_rapport' => $nomRapport,
                    'url' => $urlFichier
                ],
                'execution_time' => 0,
            ]);
        }

        // Utiliser la route proxy pour éviter les problèmes de CORS
        $this->currentPdfUrl = route('pdf.proxy', ['rapport_id' => $rapportId, 'filename' => $nomRapport]);
        $this->currentPdfTitle = $nomRapport;
        $this->showPdfViewer = true;
    }

    public function closePdfViewer()
    {
        $this->showPdfViewer = false;
        $this->currentPdfUrl = '';
        $this->currentPdfTitle = '';
    }

    public function render()
    {
        $startTime = microtime(true);

        $rapports = Rapport::query()
            ->where('validation', '1')
            ->where(function ($q) {
                $q->where('rapport_parent', 0)
                    ->orWhereNull('rapport_parent');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $executionTime = microtime(true) - $startTime;

        return view('livewire.all-reports', [
            'rapports' => $rapports,
            'executionTime' => $executionTime
        ]);
    }
}
