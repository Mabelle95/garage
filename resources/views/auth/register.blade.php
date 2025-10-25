<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - AutoCasse Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            display: flex;
            height: 100vh;
        }

        .left-section {
            flex: 1;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1486496572940-2bb2341fdbdf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem;
        }

        .left-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .left-section p {
            font-size: 1.2rem;
            text-align: center;
            max-width: 500px;
            line-height: 1.8;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .left-section .brand {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .right-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            padding: 2rem;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
        }

        .btn-primary {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13,110,253,0.3);
        }

        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
            }

            .left-section {
                min-height: 40vh;
            }

            .left-section h1 {
                font-size: 2rem;
            }

            .left-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <!-- Section gauche avec image -->
    <div class="left-section">
        <div class="brand">
            <i class="fas fa-car me-2"></i>AutoCasse Pro
        </div>
        <h1>Rejoignez-nous</h1>
        <p>
            La plus grande plateforme de pièces détachées d'occasion.
            Connectez-vous avec des professionnels vérifiés et trouvez
            les pièces qu'il vous faut au meilleur prix.
        </p>
        <div class="mt-4">
            <i class="fas fa-cogs fa-2x me-3"></i>
            <i class="fas fa-car-crash fa-2x me-3"></i>
            <i class="fas fa-shield-alt fa-2x"></i>
        </div>
    </div>

    <!-- Section droite avec formulaire -->
    <div class="right-section">
        <div class="form-container">
            <h2 class="text-center">Créer un compte</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nom -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-2"></i>Nom complet
                    </label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name') }}" placeholder="Votre nom complet" required autofocus>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Adresse Email
                    </label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email') }}" placeholder="exemple@email.com" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type de compte -->
                <div class="mb-3">
                    <label for="role" class="form-label">
                        <i class="fas fa-user-tag me-2"></i>Type de compte
                    </label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Sélectionnez votre type de compte</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ old('role') == $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Mot de passe
                    </label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Minimum 8 caractères" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                    </label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
                </div>

                <!-- Bouton submit -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Créer mon compte
                    </button>
                </div>

                <!-- Lien connexion -->
                <div class="text-center">
                    <span class="text-muted">Déjà inscrit ?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
