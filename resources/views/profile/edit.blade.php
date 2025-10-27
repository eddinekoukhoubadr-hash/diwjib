@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Mon Profil</h3>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Infos utilisateur (non modifiables) --}}
    <div class="card mb-4">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>
            </form>
        </div>
    </div>

    {{-- Formulaire de changement de mot de passe --}}
    <div class="card">
        <div class="card-header">Changer le mot de passe</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.updatePassword') }}">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
