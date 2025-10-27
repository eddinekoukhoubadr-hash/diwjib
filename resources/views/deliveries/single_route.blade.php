@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trajet optimisé vers la livraison</h1>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Client :</strong> {{ $delivery->client_name }}</p>
                    <p><strong>Adresse :</strong> {{ $delivery->address }}, {{ $delivery->city }}</p>
                    <p><strong>Distance estimée :</strong> <span class="text-primary">{{ number_format($distanceKm, 2) }} km</span></p>
                    <p><strong>Durée estimée :</strong> <span class="text-primary">{{ round($durationSeconds / 60) }} min</span></p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-success btn-lg" onclick="openGoogleMaps()">
                        <i class="fas fa-map-marked-alt"></i> Ouvrir dans Google Maps
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">🚗 Options de navigation</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <button class="btn btn-outline-primary w-100" onclick="openGoogleMapsNavigation()">
                        <i class="fas fa-car"></i><br>
                        Navigation Google Maps
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-success w-100" onclick="openWaze()">
                        <i class="fab fa-waze"></i><br>
                        Ouvrir dans Waze
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-info w-100" onclick="shareLocation()">
                        <i class="fas fa-share-alt"></i><br>
                        Partager l'emplacement
                    </button>
                </div>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Ces options ouvriront l'application de navigation sur votre appareil.
                </small>
            </div>
        </div>
    </div>

    <div id="map" style="height: 600px; border: 2px solid #007bff; border-radius: 8px;" class="mt-4"></div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux livraisons
        </a>
        <button class="btn btn-outline-primary" onclick="copyCoordinates()">
            <i class="fas fa-copy"></i> Copier les coordonnées
        </button>
        <button class="btn btn-outline-warning" onclick="openGoogleMaps()">
            <i class="fas fa-external-link-alt"></i> Google Maps
        </button>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Coordonnées
const depot = @json([$depot['latitude'], $depot['longitude']]);
const deliveryCoords = @json([$delivery->latitude, $delivery->longitude]);
const geojsonRoute = @json($geojson);
const deliveryData = @json($delivery);

// Ouvrir Google Maps avec l'itinéraire
function openGoogleMaps() {
    const depotLatLng = `${depot[0]},${depot[1]}`;
    const deliveryLatLng = `${deliveryCoords[0]},${deliveryCoords[1]}`;
    
    // URL Google Maps avec itinéraire
    const googleMapsUrl = `https://www.google.com/maps/dir/${depotLatLng}/${deliveryLatLng}/`;
    
    // Ouvrir dans un nouvel onglet
    window.open(googleMapsUrl, '_blank');
}

// Navigation Google Maps (mobile friendly)
function openGoogleMapsNavigation() {
    const depotLatLng = `${depot[0]},${depot[1]}`;
    const deliveryLatLng = `${deliveryCoords[0]},${deliveryCoords[1]}`;
    
    // URL pour la navigation Google Maps
    const googleMapsUrl = `https://www.google.com/maps/dir/${depotLatLng}/${deliveryLatLng}/`;
    
    // Essayer d'ouvrir l'app mobile
    window.location.href = `comgooglemapsurl://maps/dir/${depotLatLng}/${deliveryLatLng}/`;
    
    // Fallback après un court délai
    setTimeout(() => {
        window.open(googleMapsUrl, '_blank');
    }, 500);
}

// Ouvrir dans Waze
function openWaze() {
    const wazeUrl = `https://waze.com/ul?ll=${deliveryCoords[0]},${deliveryCoords[1]}&navigate=yes`;
    window.open(wazeUrl, '_blank');
}

// Partager l'emplacement
function shareLocation() {
    const shareText = `Livraison pour ${deliveryData.client_name}\nAdresse: ${deliveryData.address}, ${deliveryData.city}\nCoordonnées: ${deliveryCoords[0]}, ${deliveryCoords[1]}\n\nItinéraire: https://www.google.com/maps/dir/${depot[0]},${depot[1]}/${deliveryCoords[0]},${deliveryCoords[1]}/`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Livraison - ' + deliveryData.client_name,
            text: shareText,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(shareText).then(() => {
            showNotification('Informations copiées pour partage !', 'success');
        });
    }
}

// Copier les coordonnées dans le clipboard
function copyCoordinates() {
    const text = `Dépôt: ${depot[0]}, ${depot[1]}\nLivraison: ${deliveryCoords[0]}, ${deliveryCoords[1]}`;
    
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Coordonnées copiées dans le presse-papier !', 'success');
    }).catch(function(err) {
        console.error('Erreur lors de la copie: ', err);
        showNotification('Erreur lors de la copie', 'error');
    });
}

// Afficher une notification
function showNotification(message, type) {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${type} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    notification.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <span>${message}</span>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 3 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Initialisation de la carte Leaflet
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView(depot, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors',
    }).addTo(map);

    // Marqueurs personnalisés
    const depotIcon = L.divIcon({
        html: '<div style="background-color: #dc3545; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
        className: 'depot-icon',
        iconSize: [25, 25]
    });

    const deliveryIcon = L.divIcon({
        html: '<div style="background-color: #28a745; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
        className: 'delivery-icon',
        iconSize: [25, 25]
    });

    // Ajouter les marqueurs avec popup interactif
    const depotMarker = L.marker(depot, {icon: depotIcon}).addTo(map)
        .bindPopup(`
            <div style="min-width: 200px;">
                <strong>🏢 Dépôt de départ</strong><br>
                <small>Coordonnées: ${depot[0].toFixed(6)}, ${depot[1].toFixed(6)}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-primary w-100" onclick="openGoogleMaps()">
                        <i class="fas fa-map-marked-alt"></i> Itinéraire complet
                    </button>
                </div>
            </div>
        `)
        .openPopup();
    
    const deliveryMarker = L.marker(deliveryCoords, {icon: deliveryIcon}).addTo(map)
        .bindPopup(`
            <div style="min-width: 200px;">
                <strong>📦 ${deliveryData.client_name}</strong><br>
                ${deliveryData.address}<br>
                <small>Coordonnées: ${deliveryCoords[0].toFixed(6)}, ${deliveryCoords[1].toFixed(6)}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-success w-100" onclick="openGoogleMaps()">
                        <i class="fas fa-external-link-alt"></i> Google Maps
                    </button>
                </div>
            </div>
        `);

    // Afficher l'itinéraire
    if (geojsonRoute && geojsonRoute.coordinates && geojsonRoute.coordinates.length > 1) {
        const routeLine = L.polyline(
            geojsonRoute.coordinates.map(coord => [coord[1], coord[0]]),
            {
                color: '#007bff',
                weight: 6,
                opacity: 0.8,
                lineJoin: 'round'
            }
        ).addTo(map);

        // Ajuster la vue pour voir tout l'itinéraire
        map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
    } else {
        // Ligne droite en cas d'échec
        const straightLine = L.polyline([depot, deliveryCoords], {
            color: 'orange',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
        
        map.fitBounds(straightLine.getBounds(), { padding: [50, 50] });
    }

    // Ajouter un contrôle pour centrer sur le dépôt
    const customControl = L.control({position: 'topright'});
    customControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
        div.innerHTML = `
            <a href="#" title="Centrer sur le dépôt" style="
                display: block; 
                background: white; 
                padding: 8px; 
                border-radius: 4px;
                border: 2px solid rgba(0,0,0,0.2);
                text-decoration: none;
            ">
                <i class="fas fa-home" style="color: #dc3545;"></i>
            </a>
        `;
        div.onclick = function(e) {
            e.preventDefault();
            map.setView(depot, 15);
            depotMarker.openPopup();
            return false;
        };
        return div;
    };
    customControl.addTo(map);
});
</script>

<style>
.leaflet-control a {
    text-decoration: none;
    color: #333 !important;
}
.leaflet-control a:hover {
    background: #f8f9fa !important;
}
.custom-notification {
    animation: slideIn 0.3s ease-out;
}
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.btn { transition: all 0.2s ease; }
.btn:hover { transform: translateY(-2px); }
</style>
@endpush