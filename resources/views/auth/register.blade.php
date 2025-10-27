<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - DIWJIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #7B241C 0%, #922B21 100%);
            font-family: "Poppins", sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 6px 30px rgba(0,0,0,0.1);
            padding: 40px 35px;
        }
        .register-logo {
            display: block;
            margin: 0 auto 25px auto;
            width: 140px;
            height: auto;
        }
        h1 {
            text-align: center;
            font-size: 1.8rem;
            color: #7B241C;
            font-weight: 700;
            margin-bottom: 25px;
        }
        label {
            font-weight: 500;
            color: #444;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
        }
        .btn-register {
            background-color: #7B241C;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            transition: 0.3s;
        }
        .btn-register:hover {
            background-color: #922B21;
            transform: translateY(-1px);
        }
        .login-link {
            color: #7B241C;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .login-link:hover {
            color: #922B21;
        }
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
        @media (max-width: 480px) {
            .register-card {
                padding: 30px 25px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <a href="/">
            <img src="{{ asset('diwjib.png') }}" alt="Logo DIWJIB" class="register-logo" />
        </a>
        <h1>Créer un compte</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control" />
            </div>
            <div class="mb-3">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control" />
            </div>
            <div class="mb-3">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required class="form-control" />
            </div>
            <div class="mb-3">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control" />
            </div>
            <button type="submit" class="btn-register">S'inscrire</button>
        </form>

        <div class="text-center mt-3">
            <span>Déjà inscrit ? </span>
            <a href="{{ route('login') }}" class="login-link">Connectez-vous</a>
        </div>
    </div>
</body>
</html>
