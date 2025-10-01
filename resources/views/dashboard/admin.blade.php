@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Dashboard Admin</h1>

    {{-- Statistiques générales --}}
    <div class="row mb-5">
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white p-3">
                <h5>Total Users</h5>
                <h3>{{ $stats['total_users'] }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white p-3">
                <h5>Total Casses</h5>
                <h3>{{ $stats['total_casses'] }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-dark p-3">
                <h5>Total Clients</h5>
                <h3>{{ $stats['total_clients'] }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white p-3">
                <h5>Total Véhicules</h5>
                <h3>{{ $stats['total_vehicles'] }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white p-3">
                <h5>Total Pièces</h5>
                <h3>{{ $stats['total_pieces'] }}</h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-dark text-white p-3">
                <h5>Chiffre d'affaires</h5>
                <h3>{{ number_format($stats['chiffre_affaires'], 2, ',', ' ') }}FCFA</h3>
            </div>
        </div>
    </div>

    {{-- Graphique pyramidal commandes par mois --}}
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card p-3">
                <h4 class="card-title mb-3">Commandes par mois</h4>
                <canvas id="commandesChart" height="150"></canvas>
            </div>
        </div>
    </div>

    {{-- Derniers utilisateurs et commandes --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 mb-4">
                <h5 class="mb-3">Derniers utilisateurs</h5>
                <ul class="list-group">
                    @foreach($recentUsers as $user)
                        <li class="list-group-item">
                            {{ $user->name }} - {{ $user->email }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 mb-4">
                <h5 class="mb-3">Dernières commandes</h5>
                <ul class="list-group">
                    @foreach($recentCommandes as $commande)
                        <li class="list-group-item">
                            #{{ $commande->id }} - Client: {{ $commande->client->name ?? 'Inconnu' }} - Total: {{ number_format($commande->total, 2, ',', ' ') }}FCFA
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('commandesChart').getContext('2d');

const labels = @json($commandesParMois->pluck('mois'));
const commandesData = @json($commandesParMois->pluck('total'));

// Créer pyramide : côté gauche négatif, côté droit positif
const leftSide = commandesData.map(val => -val);
const rightSide = commandesData;

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Côté gauche',
                data: leftSide,
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            },
            {
                label: 'Côté droit',
                data: rightSide,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }
        ]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        scales: {
            x: {
                stacked: true,
                ticks: {
                    callback: function(value) {
                        return Math.abs(value);
                    }
                }
            },
            y: {
                stacked: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>
@endsection
