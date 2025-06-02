<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'EliteSearch') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    @yield('styles')
</head>

<body class="min-h-screen font-sans antialiased bg-gray-50">
    <!-- Header for authenticated users -->
    @auth
        <div class="bg-white shadow-sm">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-10">
                    <div class="flex items-center">
                        <a href="{{ route('search') }}" class="flex items-center">
                            <span class="text-2xl font-light">
                                <span class="text-blue-500">E</span>
                                <span class="text-red-500">l</span>
                                <span class="text-yellow-500">i</span>
                                <span class="text-blue-500">t</span>
                                <span class="text-green-500">e</span>
                                <span class="text-red-500">Search</span>
                            </span>
                            @if (request()->routeIs('monitoring'))
                                <span class="ml-2 text-gray-600">Admin</span>
                            @endif
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('all-reports') }}"
                            class="text-blue-600 hover:text-blue-800 transition duration-300 font-medium {{ request()->routeIs('all-reports') ? 'font-bold' : '' }}">
                            Voir tous les rapports
                        </a>
                        @if (auth()->check() && auth()->user()->is_admin)
                            <a href="{{ route('monitoring') }}"
                                class="text-blue-600 hover:text-blue-800 transition duration-300 font-medium {{ request()->routeIs('monitoring') ? 'font-bold' : '' }}">
                                Suivi
                            </a>
                            <a href="{{ route('user-management') }}"
                                class="text-blue-600 hover:text-blue-800 transition duration-300 font-medium {{ request()->routeIs('user-management') ? 'font-bold' : '' }}">
                                Gestion des Utilisateurs
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-gray-600 transition duration-300 hover:text-gray-900">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- Main content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')

</body>

</html>
