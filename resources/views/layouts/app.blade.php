<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DIWJIB - @yield('title', 'Accueil')</title>
    <link rel="icon" type="image/png" href="{{ asset('diwjib.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    


    <!-- Leaflet CSS (global car carte souvent utilisée) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        nav.navbar {
            background-color: #fff;
            box-shadow: 0 2px 6px rgb(123 36 28 / 0.15);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: #7B241C !important;
            font-size: 1.5rem;
        }
        .navbar-brand img {
            height: 100px;
            margin-right: 10px;
        }

        .nav-link {
            color: #7B241C !important;
            font-weight: 600;
            margin-left: 10px;
            transition: color 0.3s ease;
        }
        .nav-link:hover, .nav-link:focus {
            color: #A02C1F !important;
            text-decoration: underline;
        }

        button.nav-link.btn-link {
            cursor: pointer;
            color: #7B241C !important;
            font-weight: 600;
        }
        button.nav-link.btn-link:hover {
            color: #A02C1F !important;
            text-decoration: underline;
        }

        main.container {
            margin-top: 40px;
            margin-bottom: 40px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg px-4">
        <a class="navbar-brand" href="{{ url('/deliveries') }}">
            <img src="{{ asset('diwjib.png') }}" alt="DIWJIB Logo" />
            
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    @if(auth()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('deliveries.client.dashboard') }}">Mon Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('deliveries.index') }}">Livraisons</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('profile.edit') }}">
    <i class="bi bi-person-circle"></i> Profil
</a>

                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button class="nav-link btn btn-link" type="submit">Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle (Popper + Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS (global) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
