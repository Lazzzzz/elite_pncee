<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filtrage des Rapports</h3>
            <div class="text-sm text-gray-500">
                <span class="font-medium">{{ $currentFilteredCount }}</span> rapports filtrés actuellement en base
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-3">
                    <strong>Fonctionnalité :</strong> Cette action applique la même logique que la recherche à tous les
                    rapports valides de la base de données. Elle identifie automatiquement les versions les plus
                    récentes de chaque rapport en supprimant les doublons et sauvegarde les résultats dans une table
                    optimisée.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-500">
                    <div>• Extraction des rapports validés</div>
                    <div>• Regroupement par nom de base</div>
                    <div>• Sélection de la version la plus récente</div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex-1">
                    @if ($isProcessing)
                        <div class="space-y-3">
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300 flex items-center justify-center text-white text-xs font-medium"
                                    style="width: {{ $progress }}%">
                                    @if ($progress > 15)
                                        {{ number_format($progress, 0) }}%
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Progression:</span> {{ number_format($progress, 1) }}%
                                </div>
                                <div>
                                    <span class="font-medium">Traités:</span> {{ $processedCount }} /
                                    {{ $totalCount }}
                                </div>
                                @if ($filteredCount > 0)
                                    <div>
                                        <span class="font-medium">Filtrés:</span> {{ $filteredCount }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <button wire:click="filterRapports" wire:loading.attr="disabled" @disabled($isProcessing)
                    class="ml-4 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">

                    <span wire:loading.remove wire:target="filterRapports">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z">
                            </path>
                        </svg>
                        Lancer le Filtrage
                    </span>

                    <span wire:loading wire:target="filterRapports" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Traitement...
                    </span>
                </button>
            </div>

            @if ($message)
                <div
                    class="mt-4 p-4 rounded-md {{ $isProcessing ? 'bg-blue-50 border border-blue-200' : ($progress == 100 ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200') }}">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @if ($isProcessing)
                                <svg class="h-5 w-5 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            @elseif($progress == 100)
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p
                                class="text-sm {{ $isProcessing ? 'text-blue-800' : ($progress == 100 ? 'text-green-800' : 'text-gray-800') }}">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
