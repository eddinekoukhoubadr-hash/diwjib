@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter une livraison</h2>
    <form method="POST" action="{{ route('deliveries.store') }}" id="deliveryForm">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations client</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nom du client *</label>
                            <input type="text" name="client_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse *</label>
                            <div class="input-group">
                                <input type="text" name="address" id="address" class="form-control" required 
                                       placeholder="Commencez à taper une adresse...">
                                <button type="button" class="btn btn-outline-secondary" onclick="searchAddress()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div id="address-suggestions" class="list-group mt-2" style="display: none;"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ville *</label>
                                    <input type="text" name="city" id="city" class="form-control" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Code postal *</label>
                                    <input type="text" name="postal_code" id="postal_code" class="form-control" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Latitude *</label>
                                    <input type="number" step="0.000001" name="latitude" id="latitude" class="form-control" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Longitude *</label>
                                    <input type="number" step="0.000001" name="longitude" id="longitude" class="form-control" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Détails de livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Créneau horaire *</label>
                            <select name="time_slot" class="form-control" required>
                                <option value="">-- Choisir un créneau --</option>
                                <option value="08:00 - 10:00">08:00 - 10:00</option>
                                <option value="10:00 - 12:00">10:00 - 12:00</option>
                                <option value="12:00 - 14:00">12:00 - 14:00</option>
                                <option value="14:00 - 16:00">14:00 - 16:00</option>
                                <option value="16:00 - 18:00">16:00 - 18:00</option>
                                <option value="18:00 - 20:00">18:00 - 20:00</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Poids (kg) *</label>
                            <input type="number" step="0.01" name="weight" class="form-control" required 
                                   min="0.1" max="1000" placeholder="Ex: 2.5">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Statut *</label>
                            <select name="status" class="form-control" required>
                                <option value="en attente" selected>🟡 En attente</option>
                                <option value="en cours">🟠 En cours</option>
                                <option value="livrée">🟢 Livrée</option>
                                <option value="annulée">🔴 Annulée</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes additionnelles</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Informations supplémentaires..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">📍 Localisation sur la carte</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        Cliquez sur la carte pour définir précisément l'emplacement ou utilisez la recherche d'adresse.
                    </small>
                </div>
                <div id="map" style="height: 400px; border-radius: 8px;"></div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Enregistrer la livraison
            </button>
            <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .list-group-item { cursor: pointer; }
    .list-group-item:hover { background-color: #f8f9fa; }
    .leaflet-container { font-family: inherit; }
    .form-control:read-only { background-color: #f8f9fa; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let marker;
let defaultCoords = [33.5731, -7.5898];

function initMap() {
    map = L.map('map').setView(defaultCoords, 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Gestion du clic sur la carte
    map.on('click', function(e) {
        setMarkerPosition(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

  
    marker = L.marker(defaultCoords, {
        draggable: true
    }).addTo(map);

    marker.on('dragend', function() {
        const position = marker.getLatLng();
        reverseGeocode(position.lat, position.lng);
    });
}


function searchAddress() {
    const query = document.getElementById('address').value.trim();
    if (query.length < 3) return;

    const suggestions = document.getElementById('address-suggestions');
    suggestions.innerHTML = '<div class="list-group-item">Recherche en cours...</div>';
    suggestions.style.display = 'block';

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ma&limit=5`)
        .then(response => response.json())
        .then(data => {
            suggestions.innerHTML = '';
            
            if (data.length === 0) {
                suggestions.innerHTML = '<div class="list-group-item text-muted">Aucun résultat trouvé</div>';
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'list-group-item';
                div.innerHTML = `
                    <strong>${item.display_name}</strong>
                    <small class="d-block text-muted">Lat: ${item.lat}, Lon: ${item.lon}</small>
                `;
                div.onclick = () => selectAddress(item);
                suggestions.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur de recherche:', error);
            suggestions.innerHTML = '<div class="list-group-item text-danger">Erreur de recherche</div>';
        });
}

// Sélection d'une adresse
function selectAddress(item) {
    document.getElementById('address-suggestions').style.display = 'none';
    document.getElementById('address').value = item.display_name;
    
    setMarkerPosition(parseFloat(item.lat), parseFloat(item.lon));
    updateAddressFields(item);
    
    map.setView([item.lat, item.lon], 16);
}

// Géocodage inverse
function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            updateAddressFields(data);
        })
        .catch(error => {
            console.error('Erreur de géocodage inverse:', error);
        });
}

// Mise à jour des champs d'adresse
function updateAddressFields(data) {
    const address = data.address || {};
    
    document.getElementById('city').value = address.city || address.town || address.village || '';
    document.getElementById('postal_code').value = address.postcode || '';
    
    // Construction de l'adresse complète
    const fullAddress = data.display_name || '';
    document.getElementById('address').value = fullAddress;
}

// Positionner le marqueur
function setMarkerPosition(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    
    if (marker) {
        marker.setLatLng([lat, lng]);
    }
}

// Recherche automatique lors de la saisie
let searchTimeout;
document.getElementById('address').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(searchAddress, 800);
});

// Fermer les suggestions en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('#address-suggestions') && !e.target.closest('#address')) {
        document.getElementById('address-suggestions').style.display = 'none';
    }
});

// Validation du formulaire
document.getElementById('deliveryForm').addEventListener('submit', function(e) {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('Veuillez sélectionner un emplacement sur la carte ou via la recherche d\'adresse.');
        return;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush