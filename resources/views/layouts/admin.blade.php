<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Admin - DIWJIB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    @stack('styles')
</head>
<body style="background-color: #f4f4f4">

    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand fw-bold text-white" href="{{ route('admin.dashboard') }}"><img src="{{ asset('diwjib.png') }}" alt="Logo DIWJIB" class="logo" style="height: 70px;"></a>
        <ul class="navbar-nav flex-row">
                @if(session('admin_confirmed'))
                    <li class="nav-item me-3">
                        <a class="nav-link text-white" href="{{ route('admin.users') }}">Gestion utilisateurs</a>
                    </li>
                @endif
        </ul>

    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    @stack('scripts')
</body>
</html>
