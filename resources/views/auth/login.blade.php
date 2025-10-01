<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fond flou pour l'effet backdrop-blur */
        .backdrop-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        body{
            color: #000;
        }
    </style>
</head>
<body class="bg-dark">

<div class="d-flex justify-content-center align-items-center vh-100 position-relative">
    <!-- Overlay semi-transparent -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

    <!-- Formulaire centré -->
    <div class="position-relative bg-white bg-opacity-20 backdrop-blur rounded-4 shadow p-5" style="width: 100%; max-width: 400px; z-index: 1;">
        <h2 class="text-center text-black mb-4">Connexion</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label text-black">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label text-black">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Se souvenir de moi -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label text-black" for="remember_me">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Liens et bouton -->
            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-black text-decoration-underline small">
                        Mot de passe oublié ?
                    </a>
                @endif
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
