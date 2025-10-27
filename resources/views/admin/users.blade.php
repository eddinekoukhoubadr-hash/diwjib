@extends('layouts.admin')

@section('content')
<div class="container">

    @if(session('admin_confirmed'))

        <h2 class="mb-4">👥 Gestion des utilisateurs</h2>

        {{-- 🔍 Formulaire de recherche --}}
        <form method="GET" action="{{ route('admin.users') }}" class="mb-4 row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Recherche par nom :</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Entrez un nom">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>

        {{-- 🧾 Tableau des utilisateurs --}}
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">Afficher</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form method="POST" action="{{ route('admin.user.delete', $user->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        {{-- 🔐 Demande du code admin --}}
        <div class="alert alert-warning">
            🔐 Veuillez entrer le <strong>Code Admin</strong> pour accéder à cette page.
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.confirm.code') }}">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Code Admin :</label>
                <input type="password" name="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirmer</button>
        </form>
    @endif

</div>
@endsection
