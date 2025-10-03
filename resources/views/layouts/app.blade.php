<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AutoCasse Pro')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #3498db;
        }
        .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .content {
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar col-md-3 col-lg-2 d-md-block bg-dark sidebar">
        <div class="position-sticky pt-3">
            <div class="text-center text-white mb-4">
                <h4>AutoCasse Pro</h4>

            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                    </a>
                </li>

                @auth
                    {{-- Menu Casse --}}
                    @if(auth()->user()->role->value === 'casse')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pieces*') ? 'active' : '' }}" href="{{ route('pieces.index') }}">
                                <i class="fas fa-cog me-2"></i> Gestion des pièces
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('gestion/commandes*') ? 'active' : '' }}" href="{{ route('gestion.commandes.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i> Commandes clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('gestion/stocks*') ? 'active' : '' }}" href="{{ route('gestion.stocks') }}">
                                <i class="fas fa-boxes me-2"></i> Gestion des stocks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('gestion/epaves*') ? 'active' : '' }}" href="{{ route('gestion.epaves.index') }}">
                                <i class="fas fa-boxes me-2"></i> Epaves disponibles
                            </a>
                        </li>
                    @endif

                    {{-- Menu Client --}}
                    @if(auth()->user()->role->value === 'client')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pieces*') ? 'active' : '' }}" href="{{ route('pieces.index') }}">
                                <i class="fas fa-cog me-2"></i> Rechercher pièces
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('panier*') ? 'active' : '' }}" href="{{ route('panier.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i> Mon panier
                                @if(auth()->user()->getNombrePiecesPanier() > 0)
                                    <span class="badge bg-primary ms-2">{{ auth()->user()->getNombrePiecesPanier() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('commandes*') ? 'active' : '' }}" href="{{ route('commandes') }}">
                                <i class="fas fa-list-alt me-2"></i> Mes commandes
                            </a>
                        </li>
                    @endif

                    {{-- Menu Admin --}}
                    @if(auth()->user()->role->value === 'admin')

                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users me-2"></i> Utilisateurs (Casses & Clients)
                            </a>
                        </li>
                        
                    @endif

                    <!-- Menu commun -->
                    <hr class="bg-light my-3">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('profile*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                            <i class="fas fa-user me-2"></i> Mon profil
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main content -->
    <main class="content col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <span class="navbar-text">
                    Bienvenue, <strong>{{ auth()->user()->name }}</strong>
                    <span class="badge bg-{{ auth()->user()->role->value === 'admin' ? 'danger' : (auth()->user()->role->value === 'casse' ? 'success' : 'primary') }} ms-2">
                        {{ ucfirst(auth()->user()->role->value) }}
                    </span>
                </span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <div class="py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
