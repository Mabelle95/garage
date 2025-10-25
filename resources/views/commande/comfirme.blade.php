@extends('layouts.app')

@section('title', 'Passer la commande')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg p-4 rounded-4">
        <h3 class="text-center text-success mb-4">Détails de la commande</h3>


    <table class="table table-bordered table-striped">
        <tr>
            <th>Destination</th>
            <td>{{ $payWay }}</td>
        </tr>
        <tr>
            <th>Total à payer</th>
            <td>{{ $total }} FCFA</td>
        </tr>
        <tr>
            <th>Net à payer</th>
            <td>{{ $montant }} FCFA</td>
        </tr>
    </table>

    <div class="alert alert-info text-center mt-3">
        Le reste à payer à la livraison est :
        <strong>{{ $total - $montant }} FCFA</strong>
    </div>

    <div class="text-center my-4">
        <p class="mb-2">Composez directement le numéro ci-dessous :</p>
        <a href="tel:{{ $shortCode }}" class="btn btn-outline-success rounded-circle p-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="green"
                class="bi bi-telephone-outbound-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5" />
            </svg>
            <span class="ms-2 fw-bold">{{ $shortCode }}</span>
        </a>
    </div>

    <form action="{{ route('commandes.store') }}" method="post" class="text-center mt-4">
        @csrf
        <input type="hidden" name="adresse_livraison" value='{{ $client['adresse_livraison'] }}'>
        <input type="hidden" name="telephone_livraison" value='{{ $client['telephone_livraison'] }}'>
        <input type="hidden" name="mode_paiement" value='{{ $client['mode_paiement'] }}'>

        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
            Confirmer le paiement
        </button>
    </form>
</div>


</div>
@endsection
