@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Détails de la livraison</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Client :</strong> {{ $delivery->client_name }}</li>
        <li class="list-group-item"><strong>Adresse :</strong> {{ $delivery->address }}</li>
        <li class="list-group-item"><strong>Ville :</strong> {{ $delivery->city }}</li>
        <li class="list-group-item"><strong>Code postal :</strong> {{ $delivery->postal_code }}</li>
        <li class="list-group-item"><strong>Créneau :</strong> {{ $delivery->time_slot }}</li>
        <li class="list-group-item"><strong>Poids :</strong> {{ $delivery->weight }} kg</li>
        <li class="list-group-item"><strong>Status :</strong> {{ $delivery->status }}</li>
        <li class="list-group-item"><strong>Ajoutée le :</strong> {{ $delivery->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary mt-3">⬅ Retour</a>
</div>
@endsection
