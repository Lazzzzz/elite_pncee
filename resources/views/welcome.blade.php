<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <header class="pb-3 mb-4 border-bottom">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end">
                    @auth
                        <a href="{{ url('/search') }}" class="btn btn-outline-secondary me-2">Recherche</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link me-2">Connexion</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">S'inscrire</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="py-5 container-fluid">
                <h1 class="display-5 fw-bold">Bienvenue sur Laravel</h1>
                <p class="col-md-8 fs-4">Laravel possède un écosystème incroyablement riche. Nous vous suggérons de
                    commencer par les éléments suivants.</p>
                <div class="mb-4">
                    <div class="my-3 d-flex align-items-center">
                        <div class="text-white rounded-circle bg-primary d-flex align-items-center justify-content-center me-3"
                            style="width: 30px; height: 30px;">1</div>
                        <span>
                            Lisez la
                            <a href="https://laravel.com/docs" target="_blank"
                                class="link-primary fw-bold">Documentation</a>
                        </span>
                    </div>
                    <div class="my-3 d-flex align-items-center">
                        <div class="text-white rounded-circle bg-primary d-flex align-items-center justify-content-center me-3"
                            style="width: 30px; height: 30px;">2</div>
                        <span>
                            Regardez les tutoriels vidéo sur
                            <a href="https://laracasts.com" target="_blank" class="link-primary fw-bold">Laracasts</a>
                        </span>
                    </div>
                </div>
                <a href="https://cloud.laravel.com" target="_blank" class="btn btn-dark btn-lg" type="button">Déployer
                    maintenant</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
