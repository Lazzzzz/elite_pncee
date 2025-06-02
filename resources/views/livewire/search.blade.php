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
</div>
