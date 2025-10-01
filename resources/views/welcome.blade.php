<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoCasse Pro - Plateforme de pièces détachées</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1486496572940-2bb2341fdbdf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        footer {
            background-color: #2c3e50;
            color: #fff;
        }
        footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        footer a:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-car me-2"></i>AutoCasse
        </a>
        <div class="navbar-nav ms-auto">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                <a href="{{ route('register') }}" class="nav-link">Inscription</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 mb-4">Trouvez les pièces auto qu'il vous faut</h1>
        <p class="lead mb-4">La plus grande plateforme de pièces détachées d'occasion entre professionnels et particuliers</p>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                Accéder au tableau de bord
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">
                Créer un compte
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                Se connecter
            </a>
        @endauth
    </div>
</section>

<!-- Features -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Pièces détachées</h3>
                <p>Large choix de pièces d'occasion garanties</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-car-crash"></i>
                </div>
                <h3>Vente d'épaves</h3>
                <p>Vendez votre véhicule accidenté au meilleur prix</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Professionnels vérifiés</h3>
                <p>Toutes nos casses sont des professionnels agréés</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Colonne 1 -->
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-car me-2"></i>AutoCasse Pro</h5>
                <p>Votre plateforme de référence pour l’achat et la vente de pièces auto d’occasion et d’épaves.</p>
            </div>

            <!-- Colonne 2 -->
            <div class="col-md-4 mb-4">
                <h5>Liens utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Conditions générales</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>

            <!-- Colonne 3 -->
            <div class="col-md-4 mb-4">
                <h5>Suivez-nous</h5>
                <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="me-3"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#"><i class="fab fa-linkedin fa-lg"></i></a>
            </div>
        </div>

        <hr class="border-light">

        <div class="text-center">
            <p class="mb-0">© {{ date('Y') }} AutoCasse Pro. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
