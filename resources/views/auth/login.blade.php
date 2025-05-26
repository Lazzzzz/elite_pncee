@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="login-card">
            <h1 class="mb-4 text-center text-blue-500 font-bold text-2xl">Connexion</h1>

            @if (session('error'))
                <div class="mb-3 bg-red-100 text-red-700 p-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" required autofocus>
                    @error('email')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input id="remember" type="checkbox" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn btn-primary w-full">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
@endsection
