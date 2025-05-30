<?php

namespace App\Livewire;

use App\Models\Rapport;
use Livewire\Component;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;

class Search extends Component
{
    public $query = '';
    public $results = [];
    public $searchPerformed = false;
    public $showPdfViewer = false;
    public $currentPdfUrl = '';
    public $currentPdfTitle = '';

    protected $listeners = ['keydown.escape' => 'closePdfViewer'];

    public function updatedQuery()
    {
        $this->searchPerformed = false;
    }

    public function search()
    {
        $this->searchPerformed = true;
        $startTime = microtime(true);

        mt_srand(crc32($this->query));
        $randomDelay = mt_rand(5, 10);
        if (!Auth::user()->is_admin) {
            sleep($randomDelay);
        }


        // Extraire la base sans la version (en enlevant la dernière partie après le dernier point)
        $queryParts = explode('.', $this->query);
        if (count($queryParts) < 5) {
            $this->results = ['Format de requête invalide'];
            return;
        }

        if (count($queryParts) > 5) {
            array_pop($queryParts); // Supprime la version
        }

        $searchKey = implode('.', $queryParts);

        // Construire une regex qui matche exactement la base ou base.version
        $escapedKey = preg_quote($searchKey, '/'); // Sécurise les points
        $regex = "^" . $escapedKey . "(\\.[0-9]+)?$";

        // Requête avec REGEXP
        $rapports = Rapport::where('validation', '1')
            ->where(function ($query) {
                $query->where('rapport_parent', 0)
                    ->orWhereNull('rapport_parent');
            })
            ->whereRaw("nom_rapport REGEXP ?", [$regex])
            ->select('rapport_id', 'nom_rapport')
            ->get();

        if ($rapports->count() > 1) {
            $rapports = $rapports->sort(function ($a, $b) {
                $countA = count(explode('.', $a->nom_rapport));
                $countB = count(explode('.', $b->nom_rapport));

                if ($countB !== $countA) {
                    return $countB <=> $countA; // Sort by count descending
                }

                return $b->rapport_id <=> $a->rapport_id; // Then by rapport_id descending
            })->values()->take(1); // values() re-indexes the collection after sorting
        }

        $this->results = $rapports->map(function ($rapport) {
            return [
                'rapport_id' => $rapport->rapport_id,
                'nom_rapport' => $rapport->nom_rapport,
                'url_fichier' => route('pdf.proxy', ['rapport_id' => $rapport->rapport_id, 'filename' => $rapport->nom_rapport]),
            ];
        })->toArray();
        $executionTime = microtime(true) - $startTime;

        SearchHistory::create([
            'query' => $this->query,
            'user_id' => Auth::id(),
            'search_type' => 'search',
            'results' => $this->results,
            'execution_time' => $executionTime,
        ]);
    }


    public function lucky()
    {
        $this->searchPerformed = true;
        $startTime = microtime(true);
        if (!Auth::user()->is_admin) {

            sleep(rand(1, 5)); // Simulation de délai
        }
        // Récupérer un rapport aléatoire qui respecte les conditions
        $rapport = Rapport::where('validation', '1')
            ->where(function ($query) {
                $query->where('rapport_parent', 0)
                    ->orWhereNull('rapport_parent');
            })
            ->inRandomOrder()
            ->first();

        if ($rapport) {
            $this->results = [
                [
                    'rapport_id' => $rapport->id,
                    'nom_rapport' => $rapport->nom_rapport,
                    'url_fichier' => route('pdf.proxy', ['rapport_id' => $rapport->id, 'filename' => $rapport->nom_rapport]),
                ]
            ];
        } else {
            $this->results = ['Aucun rapport trouvé'];
        }

        // Calcul du temps d'exécution
        $executionTime = microtime(true) - $startTime;

        // Enregistrement de l'historique
        SearchHistory::create([
            'query' => 'Feeling Lucky', // Changed query to reflect the action
            'user_id' => Auth::id(),
            'search_type' => 'feeling_lucky',
            'results' => $this->results,
            'execution_time' => $executionTime,
        ]);
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
        return view('livewire.search');
    }
}
