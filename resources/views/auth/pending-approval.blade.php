@extends('layouts.guest')

@section('content')
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-clock fa-4x text-warning"></i>
        </div>

        <h2 class="mb-3">Compte en attente d'approbation</h2>

        <p class="text-muted mb-4">
            Votre compte a été créé avec succès !<br>
            Il est actuellement en attente d'approbation par un administrateur.
        </p>

        <div class="alert alert-info">
            <i class="fas fa-envelope"></i>
            Vous recevrez un email à <strong>{{ session('email') }}</strong> une fois votre compte validé.
        </div>

        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
@endsection
