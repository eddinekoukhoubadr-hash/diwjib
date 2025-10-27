@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier la livraison</h2>
    <form method="POST" action="{{ route('deliveries.update', $delivery) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom du client</label>
            <input type="text" name="client_name" class="form-control" value="{{ $delivery->client_name }}" required>
        </div>

        <div class="mb-3">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ $delivery->address }}" required>
        </div>

        <div class="mb-3">
            <label>Ville</label>
            <input type="text" name="city" class="form-control" value="{{ $delivery->city }}" required>
        </div>

        <div class="mb-3">
            <label>Code postal</label>
            <input type="text" name="postal_code" class="form-control" value="{{ $delivery->postal_code }}" required>
        </div>

        <div class="mb-3">
            <label>Créneau horaire</label>
            <select name="time_slot" class="form-control" required>
                <option value="">-- Choisir un créneau --</option>
                <option value="08:00 - 10:00" {{ $delivery->time_slot == '08:00 - 10:00' ? 'selected' : '' }}>08:00 - 10:00</option>
                <option value="10:00 - 12:00" {{ $delivery->time_slot == '10:00 - 12:00' ? 'selected' : '' }}>10:00 - 12:00</option>
                <option value="12:00 - 14:00" {{ $delivery->time_slot == '12:00 - 14:00' ? 'selected' : '' }}>12:00 - 14:00</option>
                <option value="14:00 - 16:00" {{ $delivery->time_slot == '14:00 - 16:00' ? 'selected' : '' }}>14:00 - 16:00</option>
                <option value="16:00 - 18:00" {{ $delivery->time_slot == '16:00 - 18:00' ? 'selected' : '' }}>16:00 - 18:00</option>
            </select>
        </div>
        

        <div class="mb-3">
            <label>Poids (kg)</label>
            <input type="number" step="0.01" name="weight" class="form-control" value="{{ $delivery->weight }}" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="en attente" {{ $delivery->status == 'en attente' ? 'selected' : '' }}>En attente</option>
                <option value="en cours" {{ $delivery->status == 'en cours' ? 'selected' : '' }}>En cours</option>
                <option value="livrée" {{ $delivery->status == 'livrée' ? 'selected' : '' }}>Livrée</option>
            </select>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
