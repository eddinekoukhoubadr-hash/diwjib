@extends('layouts.admin')

@section('content')
<div class="container py-4">

    {{-- Vérification du code admin --}}
    @if (!session('admin_confirmed'))
        <div class="alert alert-warning">
            🔐 Veuillez entrer le <strong>Code Admin</strong> pour accéder au tableau de bord.
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.confirm.code') }}">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Code Admin :</label>
                <input type="password" name="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirmer</button>
        </form>
    @else
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('admin.logout.code') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Quitter le mode admin</button>
            </form>
        </div>

        <h1 class="mb-4 text-dark">Tableau de bord Admin</h1>

        {{-- Statistiques générales --}}
        <div class="row mb-4">
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $totalUsers }}</h3>
                    <p class="fw-semibold mb-0 text-muted">Utilisateurs inscrits</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $activeUsers }}</h3>
                    <p class="fw-semibold mb-0 text-muted">Utilisateurs actifs</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $blockedUsers }}</h3>
                    <p class="fw-semibold mb-0 text-muted">Utilisateurs bloqués</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $totalDeliveries }}</h3>
                    <p class="fw-semibold mb-0 text-muted">Total livraisons</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $deliveriesStatusCount['en attente'] ?? 0 }}</h3>
                    <p class="fw-semibold mb-0 text-muted">En attente</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $deliveriesStatusCount['en cours'] ?? 0 }}</h3>
                    <p class="fw-semibold mb-0 text-muted">En cours</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="p-4 rounded shadow-sm bg-light text-center">
                    <h3 class="text-secondary">{{ $deliveriesStatusCount['livrée'] ?? 0 }}</h3>
                    <p class="fw-semibold mb-0 text-muted">Livrée</p>
                </div>
            </div>
        </div>

        {{-- Graphique des livraisons --}}
        <h4 class="text-dark mb-3 mt-4">📊 Livraisons par jour (7 derniers jours)</h4>
        <canvas id="deliveriesChart" height="100"></canvas>

        {{-- Carte Leaflet --}}
        <h4 class="text-dark mb-3 mt-5">🗺️ Carte des livraisons</h4>
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0" style="height: 500px;">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>

        {{-- Export PDF --}}
        <div class="text-end mt-3">
            <a href="{{ route('admin.export.pdf') }}" class="btn btn-outline-primary">📄 Export PDF des livraisons</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if (session('admin_confirmed'))
        // Chart.js
        const ctx = document.getElementById('deliveriesChart').getContext('2d');
        const labels = {!! json_encode($deliveriesPerDay->keys()) !!};
        const data = {!! json_encode($deliveriesPerDay->values()) !!};

        if (labels.length && data.length) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Livraisons',
                        data: data,
                        backgroundColor: '#3f51b5',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Leaflet map avec clustering
        const map = L.map('map').setView([33.5899, -7.6039], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = L.markerClusterGroup();
        const deliveries = @json($deliveries);

        deliveries.forEach(delivery => {
            if (delivery.latitude && delivery.longitude) {
                const popupContent = `
                    <strong>${delivery.client_name}</strong><br>
                    ${delivery.address}, ${delivery.city}<br>
                    <small>Status: ${delivery.status}</small>
                `;
                markers.addLayer(L.marker([delivery.latitude, delivery.longitude]).bindPopup(popupContent));
            }
        });

        map.addLayer(markers);

        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    @endif
});
</script>
@endpush
