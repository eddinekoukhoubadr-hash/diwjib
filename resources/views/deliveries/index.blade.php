@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des livraisons</h2>

    <a href="{{ route('deliveries.create') }}" class="btn btn-success mb-3">+ Ajouter livraison</a>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('deliveries.import.form') }}" class="btn btn-outline-success">📂 Importer CSV</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Client</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Poids</th>
                <th>Créneau</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deliveries as $delivery)
                <tr>
                    <td>{{ $delivery->client_name }}</td>
                    <td>{{ $delivery->address }}</td>
                    <td>{{ $delivery->city }}</td>
                    <td>{{ $delivery->weight }} kg</td>
                    <td>{{ $delivery->time_slot }}</td>
                    <td>{{ $delivery->status }}</td>
                    <td>
                        <a href="{{ route('deliveries.show', $delivery->_id) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route('deliveries.edit', $delivery->_id) }}" class="btn btn-warning btn-sm">Éditer</a>

                        <form method="POST" action="{{ route('deliveries.destroy', $delivery->_id) }}" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Suppr.</button>
                        </form>

                        <!-- Bouton Optimiser trajet -->
                        <a href="{{ route('deliveries.optimize.single', $delivery->_id) }}" class="btn btn-primary btn-sm">
                            Optimiser trajet
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h4>🗺️ Carte des Livraisons</h4>
    <div id="map" style="height: 600px;"></div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([33.589886, -7.603869], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        @foreach ($deliveries as $delivery)
            @if ($delivery->latitude && $delivery->longitude)
                L.marker([{{ $delivery->latitude }}, {{ $delivery->longitude }}])
                    .addTo(map)
                    .bindPopup(`<strong>{{ addslashes($delivery->client_name) }}</strong><br>{{ addslashes($delivery->address) }}, {{ addslashes($delivery->city) }}`);
            @endif
        @endforeach
    </script>

</div>
@endsection
