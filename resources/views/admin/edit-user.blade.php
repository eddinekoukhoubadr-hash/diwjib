@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifier l'utilisateur</h2>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="name" class="form-label">Nom :</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
