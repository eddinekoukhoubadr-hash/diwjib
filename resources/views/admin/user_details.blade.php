@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Détails du client</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Nom : {{ $user->name }}</h5>
            <p>Email : {{ $user->email }}</p>
        </div>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="p-3 bg-secondary text-white rounded">
                <h4>{{ $total }}</h4>
                <p>Total livraisons</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-success text-white rounded">
                <h4>{{ $livree }}</h4>
                <p>Livraisons réussies</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-primary text-white rounded">
                <h4>{{ $en_cours }}</h4>
                <p>Livraisons en cours</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-dark text-white rounded">
                <h4>{{ $en_attente }}</h4>
                <p>Livraisons en attente</p>
            </div>
        </div>
    </div>

    <h4>Liste des livraisons</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Client</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Poids</th>
                <th>Créneau</th>
                <th>Status</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
