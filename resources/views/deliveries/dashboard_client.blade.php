@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de bord Client</h1>


    <div class="row mb-4 justify-content-center text-center">

        <div class="col-md-3 mb-3">
            <div class="p-4 rounded text-white" style="background-color: #28a745; cursor: pointer;" id="filter-delivered" title="Livraisons réussies">
                <h3>{{ $countDelivered }}</h3>
                <p class="mb-0 fw-bold">Livraisons réussies</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="p-4 rounded text-white" style="background-color: #007bff; cursor: pointer;" id="filter-inprogress" title="Livraisons en cours">
                <h3>{{ $countInProgress }}</h3>
                <p class="mb-0 fw-bold">Livraisons en cours</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="p-4 rounded text-white" style="background-color: #6c757d; cursor: pointer;" id="filter-pending" title="Livraisons en attente">
                <h3>{{ $countPending }}</h3>
                <p class="mb-0 fw-bold">Livraisons en attente</p>
            </div>
        </div>

    </div>

  
    <div class="table-responsive mb-4">
        <table class="table table-striped" id="deliveriesTable">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Poids (kg)</th>
                    <th>Créneau</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveries as $delivery)
                <tr data-status="{{ $delivery->status }}">
                    <td>{{ $delivery->client_name }}</td>
                    <td>{{ $delivery->address }}</td>
                    <td>{{ $delivery->city }}</td>
                    <td>{{ $delivery->weight }}</td>
                    <td>{{ $delivery->time_slot }}</td>
                    <td>
                        @if($delivery->status === 'en cours')
                            <span class="badge bg-primary">En cours</span>
                        @elseif($delivery->status === 'livrée')
                            <span class="badge bg-success">Livrée</span>
                        @elseif($delivery->status === 'en attente')
                            <span class="badge bg-secondary">En attente</span>
                        @else
                            <span class="badge bg-light text-dark">{{ $delivery->status }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   
    <h4 class="mt-5">📍 Vos Livraisons sur la carte</h4>
    <div id="map" style="height: 500px;" class="mb-4"></div>
</div>
@endsection

@push('scripts')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
   
    const map = L.map('map').setView([33.589886, -7.603869], 12); // Casablanca par défaut

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    @foreach ($deliveries as $delivery)
        L.marker([{{ $delivery->latitude }}, {{ $delivery->longitude }}])
            .addTo(map)
            .bindPopup(`<strong>{{ $delivery->client_name }}</strong><br>{{ $delivery->address }}, {{ $delivery->city }}`);
    @endforeach

   
    const bounds = L.latLngBounds([
        @foreach ($deliveries as $delivery)
            [{{ $delivery->latitude }}, {{ $delivery->longitude }}],
        @endforeach
    ]);
    map.fitBounds(bounds, { padding: [50, 50] });

   
    const cityData = @json($cities);
    const ctx = document.getElementById('cityChart') ? document.getElementById('cityChart').getContext('2d') : null;
    if(ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(cityData),
                datasets: [{
                    label: 'Nombre de livraisons par ville',
                    data: Object.values(cityData),
                    backgroundColor: '#3f51b5'
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


    const rows = document.querySelectorAll('#deliveriesTable tbody tr');

    function filterByStatus(status) {
        rows.forEach(row => {
            if(status === 'all' || row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('filter-delivered').addEventListener('click', () => filterByStatus('livrée'));
    document.getElementById('filter-inprogress').addEventListener('click', () => filterByStatus('en cours'));
    document.getElementById('filter-pending').addEventListener('click', () => filterByStatus('en attente'));
});
</script>
@endpush
