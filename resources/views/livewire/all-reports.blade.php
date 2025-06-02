<div class="min-h-screen bg-gray-50 py-8">
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
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tous les Rapports</h1>
            <p class="mt-2 text-gray-600">Consultez tous les rapports du système</p>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-end">
                    <div class="w-48">
                        <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">
                            Éléments par page
                        </label>
                        <select wire:model.live="perPage" id="perPage"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        @if ($rapports->total() > 0)
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <p class="text-sm text-gray-700">
                        Affichage des lignes {{ ($rapports->currentPage() - 1) * $rapports->perPage() + 1 }} -
                        {{ min($rapports->currentPage() * $rapports->perPage(), $rapports->total()) }}
                        (total de {{ number_format($rapports->total()) }}, traitement en
                        {{ number_format($executionTime, 4) }} seconde(s).)
                    </p>
                </div>
            </div>
        @endif

        <!-- Reports Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if ($rapports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
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
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
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
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($rapports as $rapport)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="max-w-md" title="{{ $rapport->nom_rapport }}">
                                            {{ $rapport->nom_rapport }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rapport->date_rapport ? \Carbon\Carbon::parse($rapport->date_rapport)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click.prevent="logPdfViewAndOpen({{ $rapport->rapport_id }}, '{{ addslashes($rapport->nom_rapport) }}', '{{ route('pdf.proxy', ['rapport_id' => $rapport->rapport_id, 'filename' => $rapport->nom_rapport]) }}')"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $rapports->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
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
</div>
