<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - AutoPièces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand { font-weight: bold; }
        .sidebar { min-height: calc(100vh - 56px); }
        .notification-badge { position: absolute; top: 0; right: 0; }
        .card-hover:hover { transform: translateY(-2px); transition: all 0.3s; }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('client.dashboard') }}">
            <i class="fas fa-car"></i> AutoPièces
        </a>

        <div class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('client.profile') }}">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </li>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}"
                           href="{{ route('client.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.recherche-pieces') ? 'active' : '' }}"
                           href="{{ route('client.recherche-pieces') }}">
                            <i class="fas fa-search"></i> Recherche Pièces
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.panier') ? 'active' : '' }}"
                           href="{{ route('client.panier') }}">
                            <i class="fas fa-shopping-cart"></i> Panier
                            <span class="badge bg-primary">{{ auth()->user()->getNombrePiecesPanier() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.commandes') }}">
                            <i class="fas fa-list-alt"></i> Mes Commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.vente-epaves') ? 'active' : '' }}"
                           href="{{ route('client.vente-epaves') }}">
                            <i class="fas fa-car-crash"></i> Vente d'Épaves
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.favoris') }}">
                            <i class="fas fa-heart"></i> Favoris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.notifications') }}">
                            <i class="fas fa-bell"></i> Notifications
                            <span class="badge bg-danger">{{ auth()->user()->getUnreadNotificationsCount() }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Scripts pour les interactions AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter au panier
        document.querySelectorAll('.ajouter-panier').forEach(button => {
            button.addEventListener('click', function() {
                const pieceId = this.dataset.pieceId;
                const quantite = document.querySelector(`#quantite-${pieceId}`)?.value || 1;

                fetch(`/client/panier/ajouter/${pieceId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantite: parseInt(quantite) })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour le compteur du panier
                            document.querySelectorAll('.panier-count').forEach(el => {
                                el.textContent = data.panier_count;
                            });
                            alert('Pièce ajoutée au panier !');
                        }
                    });
            });
        });

        // Gestion des favoris
        document.querySelectorAll('.toggle-favori').forEach(button => {
            button.addEventListener('click', function() {
                const pieceId = this.dataset.pieceId;
                const isFavori = this.classList.contains('active');

                const url = isFavori ?
                    `/client/favoris/retirer/${pieceId}` :
                    `/client/favoris/ajouter/${pieceId}`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.toggle('active');
                            this.classList.toggle('text-danger');
                        }
                    });
            });
        });
    });
</script>


@yield('scripts')
</body>
</html>
