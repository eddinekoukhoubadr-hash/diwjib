<h2>Trajet Livraison</h2>
<p><strong>Dépôt :</strong> {{ $depot['address'] }}, {{ $depot['city'] }}</p>
<p><strong>Client :</strong> {{ $delivery->client_name }}</p>
<p><strong>Adresse :</strong> {{ $delivery->address }}, {{ $delivery->city }}</p>
<p><strong>Créneau :</strong> {{ $delivery->time_slot }}</p>
<p><strong>Distance estimée :</strong> {{ round($distance, 2) }} km</p>
