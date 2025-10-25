@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 text-center">Dashboard Admin</h1>

        {{-- Statistiques générales --}}
        <div class="row mb-5 justify-content-center text-center">
            <div class="col-6 col-md-2 mb-3 d-flex">
                <div class="card flex-fill bg-success text-white p-3 shadow-sm rounded-3">
                    <h5>Total Casses</h5>
                    <h3>{{ $stats['total_casses'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-3 d-flex">
                <div class="card flex-fill bg-warning text-dark p-3 shadow-sm rounded-3">
                    <h5>Total Clients</h5>
                    <h3>{{ $stats['total_clients'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-3 d-flex">
                <div class="card flex-fill bg-info text-white p-3 shadow-sm rounded-3">
                    <h5>Total Véhicules</h5>
                    <h3>{{ $stats['total_vehicles'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-3 d-flex">
                <div class="card flex-fill bg-secondary text-white p-3 shadow-sm rounded-3">
                    <h5>Total Pièces</h5>
                    <h3>{{ $stats['total_pieces'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Graphique commandes & clients par mois --}}
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card p-3 shadow-sm rounded-3">
                    <h4 class="card-title mb-3 text-center">Commandes & Clients par mois</h4>
                    <div style="height:400px;">
                        <canvas id="commandesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Derniers utilisateurs et commandes --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3 mb-4 shadow-sm rounded-3">
                    <h5 class="mb-3">Derniers utilisateurs</h5>
                    <ul class="list-group">
                        @foreach ($recentUsers as $user)
                            <li class="list-group-item">
                                {{ $user->name }} - {{ $user->email }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 mb-4 shadow-sm rounded-3">
                    <h5 class="mb-3">Dernières commandes</h5>
                    <ul class="list-group">
                        @foreach ($recentCommandes as $commande)
                            <li class="list-group-item">
                                #{{ $commande->id }} - Client: {{ $commande->client->name ?? 'Inconnu' }} - Total:
                                {{ number_format($commande->total, 2, ',', ' ') }} FCFA
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

        const labels_chart = @json($labels_chart);
        const dataValues = @json($data);
        const dataClientValues = @json($dataClients);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels_chart,
                datasets: [
                    {
                        label: 'Nombre de commandes',
                        data: dataValues,
                        borderColor: 'rgba(54, 162, 235, 1)', // bleu
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Nombre de clients',
                        data: dataClientValues,
                        borderColor: 'rgba(255, 105, 180, 1)', // rose
                        backgroundColor: 'rgba(255, 105, 180, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 0 // enlève marges internes
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Commandes & Clients par mois' }
                },
                scales: {
                    x: {
                        offset: false, // colle les points aux bords
                        grid: { drawTicks: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { drawTicks: false }
                    }
                }
            }
        });
    </script>
@endsection
