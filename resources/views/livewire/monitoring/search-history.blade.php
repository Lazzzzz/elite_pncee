<div wire:poll.10s="loadSearchHistory">
    <div class="overflow-hidden bg-white shadow sm:rounded-lg fade-in">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium leading-6 text-gray-900">Historique de Recherche</h2>
            <p class="max-w-2xl mt-1 text-sm text-gray-500">Requêtes de recherche récentes des utilisateurs.</p>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Utilisateur
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Requête
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Heure
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Exécution
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSearches as $search)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $search->user ? $search->user->name : 'Invité' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $search->user ? $search->user->email : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $search->query }}</div>
                            </td>
                            @php
                                $type = $search->search_type;
                                $colors = [
                                    'search' => 'bg-blue-100 text-blue-800',
                                    'feeling_lucky' => 'bg-green-100 text-green-800',
                                    'pdf_view' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $badgeClass = $colors[$type] ?? 'bg-gray-100 text-gray-800';
                            @endphp

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $search->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ number_format($search->execution_time, 2) }}s
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                Aucun historique de recherche trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
