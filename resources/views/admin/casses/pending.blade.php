@extends('layouts.app')

@section('title', 'Casses en attente d\'approbation')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-clock text-warning"></i>
                Casses en attente d'approbation
            </h1>
        </div>

        @if($pendingCasses->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Aucune casse en attente d'approbation.
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date d'inscription</th>
                                <th>Localisation</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pendingCasses as $casse)
                                <tr>
                                    <td>
                                        <strong>{{ $casse->name }}</strong>
                                    </td>
                                    <td>{{ $casse->email }}</td>
                                    <td>{{ $casse->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($casse->latitude && $casse->longitude)
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                            {{ number_format($casse->latitude, 4) }},
                                            {{ number_format($casse->longitude, 4) }}
                                        @else
                                            <span class="text-muted">Non renseignée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form method="POST"
                                                  action="{{ route('admin.casses.approve', $casse->id) }}"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        onclick="return confirm('Approuver cette casse ?')">
                                                    <i class="fas fa-check"></i> Approuver
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('admin.casses.reject', $casse->id) }}"
                                                  class="d-inline ms-1">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Rejeter et supprimer cette casse ?')">
                                                    <i class="fas fa-times"></i> Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $pendingCasses->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
