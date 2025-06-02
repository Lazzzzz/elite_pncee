<div>
    <!-- Modal PDF Viewer -->
    @if ($showPdfViewer)
        <div class="fixed inset-0 z-50 overflow-hidden bg-black bg-opacity-75" wire:click="closePdfViewer">
            <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
                <div class="relative w-full bg-white rounded-lg shadow-xl max-w-7xl" wire:click.stop
                    style="height: 90vh; max-height: 90vh;">

                    <!-- Bouton de fermeture -->
                    <button wire:click="closePdfViewer"
                        class="absolute top-0 right-0 z-20 p-2 mt-2 mr-2 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                        aria-label="Close PDF viewer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Contenu PDF -->
                    <div class="relative w-full h-full">
                        <div id="pdf-loading"
                            class="absolute inset-0 z-10 flex items-center justify-center bg-gray-100 rounded-lg">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 mx-auto mb-4 border-b-2 border-blue-500 rounded-full animate-spin">
                                </div>
                                <p class="text-gray-600">Chargement du PDF...</p>
                            </div>
                        </div>
                        <div wire:ignore class="w-full h-full">
                            <iframe id="pdf-iframe"
                                src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode($currentPdfUrl) }}#toolbar=0&navpanes=0&scrollbar=0"
                                width="100%" height="100%" class="border-0 rounded-lg" style="min-height: 100%;"
                                onload="document.getElementById('pdf-loading').style.display='none'">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Script pour gérer la modal PDF -->
        @push('scripts')
            <script>
                // Gestionnaire pour la touche Escape
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        const modal = document.querySelector('.fixed.inset-0.z-50');
                        if (modal) {
                            @this.call('closePdfViewer');
                        }
                    }
                });

                // Désactiver le clic droit sur l'iframe PDF
                document.addEventListener('DOMContentLoaded', function() {
                    const iframe = document.getElementById('pdf-iframe');
                    if (iframe) {
                        iframe.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            return false;
                        });

                        // Désactiver la sélection de texte
                        iframe.style.userSelect = 'none';
                        iframe.style.webkitUserSelect = 'none';
                        iframe.style.mozUserSelect = 'none';
                        iframe.style.msUserSelect = 'none';
                    }
                });

                // Charger quand Livewire met à jour
                document.addEventListener('livewire:updated', function() {
                    const iframe = document.getElementById('pdf-iframe');
                    if (iframe) {
                        iframe.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            return false;
                        });
                        iframe.style.userSelect = 'none';
                        iframe.style.webkitUserSelect = 'none';
                        iframe.style.mozUserSelect = 'none';
                        iframe.style.msUserSelect = 'none';
                    }
                });
            </script>
        @endpush
    @endif

    <div class="flex flex-col items-center justify-center pt-10">
        <div class="mb-8">
            <h1 class="font-light text-7xl">
                <span class="text-blue-500 logo-letter">E</span>
                <span class="text-red-500 logo-letter">l</span>
                <span class="text-yellow-500 logo-letter">i</span>
                <span class="text-blue-500 logo-letter">t</span>
                <span class="text-green-500 logo-letter">e</span>
                <span class="text-red-500 logo-letter">Search</span>
            </h1>
        </div>

        <div class="w-full max-w-2xl search-container">
            <div class="relative">
                <input wire:model.live="query" type="text" class="search-input"
                    placeholder="Rechercher parmis les rapports">
                <div class="absolute right-3 top-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex justify-center mt-8 space-x-4 search-buttons">
                <button wire:click="search" class="search-button" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="search">Rechercher</span>
                    <span wire:loading wire:target="search">Recherche en cours...</span>
                </button>
            </div>

            <!-- Warning message for load limit -->
            @if (session()->has('warning'))
                <div class="p-4 mt-4 text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Results -->
            <div class="mt-8">
                @if (!empty($results))
                    @if (isset($results[0]) && is_string($results[0]))
                        <p class="text-red-500">{{ $results[0] }}</p>
                    @else
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-semibold text-gray-700">Résultats :</h2>
                        </div>

                        <div class="space-y-2 search-results-container">
                            @foreach ($results as $index => $result)
                                <div wire:click.prevent="logPdfViewAndOpen({{ $result['rapport_id'] }}, '{{ addslashes($result['nom_rapport']) }}', '{{ $result['url_fichier'] }}')"
                                    class="flex items-center justify-between p-3 transition-shadow duration-200 bg-white rounded-md shadow cursor-pointer search-result-item hover:shadow-lg">
                                    <div class="flex-1 min-w-0">
                                        @if (isset($result['url_fichier']) && isset($result['rapport_id']))
                                            <span class="block font-medium text-blue-600 truncate hover:text-blue-800">
                                                {{ $result['nom_rapport'] }}
                                            </span>
                                        @elseif (isset($result['nom_rapport']))
                                            <span
                                                class="block text-gray-600 truncate">{{ $result['nom_rapport'] }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0 ml-2 text-gray-400 hover:text-gray-600">
                                        <!-- Eye icon from Heroicons -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @elseif ($searchPerformed && $this->query !== '')
                    <p class="text-gray-500">Aucun résultat trouvé pour "{{ $this->query }}".</p>
                @endif
            </div>
        </div>
    </div>

    <!-- All Reports Section - Only show when no search is performed -->
    @if ($allReportsData && !$searchPerformed && empty(trim($query)))
        <div class="px-4 mx-auto mt-16 mb-8 max-w-7xl sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Tous les Rapports</h2>
                <p class="mt-2 text-gray-600">Parcourez tous les rapports disponibles</p>

                <div class="flex items-center justify-center gap-2 mt-4">
                    <label for="perPage" class="text-sm font-medium text-gray-700">
                        Éléments par page :
                    </label>
                    <select wire:model.live="perPage" id="perPage"
                        class="block px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>



            <!-- Results Info -->
            @if ($allReportsData['rapports']->total() > 0)
                <div class="mb-6 bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-sm text-gray-700">
                            Affichage des lignes
                            {{ ($allReportsData['rapports']->currentPage() - 1) * $allReportsData['rapports']->perPage() + 1 }}
                            -
                            {{ min($allReportsData['rapports']->currentPage() * $allReportsData['rapports']->perPage(), $allReportsData['rapports']->total()) }}
                            (total de {{ number_format($allReportsData['rapports']->total()) }}, traitement en
                            {{ number_format($allReportsData['executionTime'], 4) }} seconde(s).)
                        </p>
                    </div>
                </div>
            @endif

            <!-- Reports Table -->
            <div class="overflow-hidden bg-white rounded-lg shadow">
                @if ($allReportsData['rapports']->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100"
                                        wire:click="sortBy('nom_rapport')">
                                        <div class="flex items-center space-x-1">
                                            <span>Nom du Rapport</span>
                                            @if ($sortBy === 'nom_rapport')
                                                @if ($sortDirection === 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100"
                                        wire:click="sortBy('date_rapport')">
                                        <div class="flex items-center space-x-1">
                                            <span>Date</span>
                                            @if ($sortBy === 'date_rapport')
                                                @if ($sortDirection === 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($allReportsData['rapports'] as $rapport)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-md" title="{{ $rapport->nom_rapport }}">
                                                {{ $rapport->nom_rapport }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $rapport->date_rapport ? \Carbon\Carbon::parse($rapport->date_rapport)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <button
                                                wire:click.prevent="logPdfViewAndOpen({{ $rapport->rapport_id }}, '{{ addslashes($rapport->nom_rapport) }}', '{{ route('pdf.proxy', ['rapport_id' => $rapport->rapport_id, 'filename' => $rapport->nom_rapport]) }}')"
                                                class="inline-flex items-center px-3 py-1 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Voir
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                        {{ $allReportsData['rapports']->links() }}
                    </div>
                @else
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rapport trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucun rapport disponible pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
