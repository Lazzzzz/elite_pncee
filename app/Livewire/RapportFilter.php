<?php

namespace App\Livewire;

use App\Models\Rapport;
use App\Models\RapportFiltered;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RapportFilter extends Component
{
    public $isProcessing = false;
    public $progress = 0;
    public $message = '';
    public $processedCount = 0;
    public $totalCount = 0;
    public $filteredCount = 0;

    public function filterRapports()
    {
        $this->isProcessing = true;
        $this->progress = 0;
        $this->message = 'Début du traitement...';
        $this->processedCount = 0;
        $this->totalCount = 0;
        $this->filteredCount = 0;

        try {
            // Clear existing filtered reports
            RapportFiltered::truncate();
            $this->message = 'Table des rapports filtrés vidée.';

            // Get all valid reports
            $allRapports = Rapport::where('validation', '1')
                ->where(function ($query) {
                    $query->where('rapport_parent', 0)
                        ->orWhereNull('rapport_parent');
                })
                ->select('rapport_id', 'nom_rapport')
                ->get();

            $this->totalCount = $allRapports->count();
            $this->message = "Trouvé {$this->totalCount} rapports à traiter.";

            if ($this->totalCount === 0) {
                $this->message = "Aucun rapport trouvé correspondant aux critères.";
                $this->progress = 100;
                return;
            }

            // Group reports by base name (without version)
            $groupedRapports = [];
            $invalidFormatCount = 0;

            foreach ($allRapports as $rapport) {
                $nomParts = explode('.', $rapport->nom_rapport);

                // Skip if less than 5 parts (invalid format)
                if (count($nomParts) < 5) {
                    $invalidFormatCount++;
                    continue;
                }

                // Extract base without version
                if (count($nomParts) > 5) {
                    array_pop($nomParts); // Remove version
                }

                $baseKey = implode('.', $nomParts);

                if (!isset($groupedRapports[$baseKey])) {
                    $groupedRapports[$baseKey] = [];
                }

                $groupedRapports[$baseKey][] = $rapport;
            }

            $validRapports = $this->totalCount - $invalidFormatCount;
            $this->message = "Rapports groupés en " . count($groupedRapports) . " groupes. ($invalidFormatCount rapports ignorés pour format invalide)";

            // Process each group to find the latest version
            $filteredRapports = [];
            $processed = 0;

            foreach ($groupedRapports as $baseKey => $rapports) {
                if (count($rapports) > 1) {
                    // Apply the same sorting logic as search
                    usort($rapports, function ($a, $b) {
                        $countA = count(explode('.', $a->nom_rapport));
                        $countB = count(explode('.', $b->nom_rapport));

                        if ($countB !== $countA) {
                            return $countB <=> $countA; // Sort by count descending
                        }

                        return $b->rapport_id <=> $a->rapport_id; // Then by rapport_id descending
                    });

                    // Take only the first (latest) one
                    $filteredRapports[] = $rapports[0];
                } else {
                    // Only one report for this base, include it
                    $filteredRapports[] = $rapports[0];
                }

                $processed++;
                $this->progress = ($processed / count($groupedRapports)) * 80; // Reserve 20% for insertion
                $this->processedCount = $processed;
            }

            $this->filteredCount = count($filteredRapports);
            $this->message = "Traitement terminé. {$this->filteredCount} rapports uniques identifiés. Insertion en base...";

            // Insert filtered reports into RapportFiltered table in batches
            $insertData = [];
            foreach ($filteredRapports as $rapport) {
                $insertData[] = [
                    'rapport_id' => $rapport->rapport_id,
                ];
            }

            // Insert in batches to avoid memory issues
            $batchSize = 1000;
            $chunks = array_chunk($insertData, $batchSize);
            $insertedBatches = 0;

            foreach ($chunks as $chunk) {
                RapportFiltered::insert($chunk);
                $insertedBatches++;
                $this->progress = 80 + (($insertedBatches / count($chunks)) * 20);
            }

            $this->progress = 100;
            $this->message = "Traitement terminé avec succès! {$this->filteredCount} rapports uniques filtrés et sauvegardés dans la base de données.";

            // Log the operation
            Log::info("Rapport filtering completed", [
                'total_rapports' => $this->totalCount,
                'filtered_rapports' => $this->filteredCount,
                'invalid_format' => $invalidFormatCount,
                'groups_processed' => count($groupedRapports)
            ]);
        } catch (\Exception $e) {
            $this->message = "Erreur lors du traitement: " . $e->getMessage();
            Log::error("Rapport filtering error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isProcessing = false;
        }
    }

    public function getFilteredCount()
    {
        return RapportFiltered::count();
    }

    public function render()
    {
        $currentFilteredCount = $this->getFilteredCount();

        return view('livewire.rapport-filter', [
            'currentFilteredCount' => $currentFilteredCount
        ]);
    }
}
