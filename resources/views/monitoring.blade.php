@extends('layouts.main')

@section('content')
    <main class="py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Stats Cards -->
                <div class="stats-card">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Utilisateurs</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalUsers }}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-4 bg-gray-50 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Voir tout (à faire)</a>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Recherches Aujourd'hui</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $searchesToday }}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-4 bg-gray-50 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Voir analyses
                                (à faire)</a>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Temps de Réponse Moyen</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $avgResponseTime }}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-4 bg-gray-50 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Voir performance
                                (à faire)</a>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Admins Actifs</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $activeAdmins }}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-4 bg-gray-50 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Gérer les
                                comptes (à faire)</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rapport Filter Section - Livewire Component -->
            <div class="mt-8">
                @livewire('rapport-filter')
            </div>

            <!-- Search History Table - Livewire Component -->
            <div class="mt-8">
                @livewire('monitoring.search-history')
            </div>

        </div>
    </main>
@endsection
