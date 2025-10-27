<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - DIWJIB</title>
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
        .login-card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 6px 30px rgba(0,0,0,0.1);
            padding: 40px 35px;
        }
        .login-logo {
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
        .btn-login {
            background-color: #7B241C;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #922B21;
            transform: translateY(-1px);
        }
        .forgot-link {
            color: #7B241C;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .forgot-link:hover {
            color: #922B21;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #ddd;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <a href="/">
            <img src="{{ asset('diwjib.png') }}" alt="Logo DIWJIB" class="login-logo" />
        </a>
        <h1>Connexion</h1>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" />
            </div>
            <div class="mb-3">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required class="form-control" />
            </div>
            <div class="mb-3 form-check">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input" />
                <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
            </div>

            @if (Route::has('password.request'))
                <div class="mb-3 text-end">
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                </div>
            @endif

            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @error('captcha')
                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-login">Se connecter</button>

            <div class="divider">ou</div>

            <div class="text-center">
                <span>Pas encore de compte ? </span>
                <a href="{{ route('register') }}" class="forgot-link">Créer un compte</a>
            </div>
        </form>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
