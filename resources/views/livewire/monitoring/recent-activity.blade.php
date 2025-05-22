<div wire:poll.10s="loadRecentActivities">
    <div class="overflow-hidden bg-white shadow sm:rounded-lg fade-in">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium leading-6 text-gray-900">Activité Récente</h2>
            <p class="max-w-2xl mt-1 text-sm text-gray-500">Événements système et actions utilisateur.</p>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($recentActivities as $activity)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-blue-600 truncate">{{ $activity['title'] }}</p>
                            <div class="flex flex-shrink-0 ml-2">
                                <p
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $activity['status_color'] }}-100 text-{{ $activity['status_color'] }}-800">
                                    {{ $activity['status'] }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    "{{ $activity['description'] }}"
                                </p>
                            </div>
                            <div class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0">
                                <p>
                                    {{ $activity['time'] }}
                                </p>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 text-sm text-center text-gray-500 sm:px-6">
                        Aucune activité récente trouvée
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
