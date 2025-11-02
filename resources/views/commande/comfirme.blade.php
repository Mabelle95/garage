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
                <a href="tel:{{ $shortCode }}" class="btn btn-outline-success rounded -rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="green"
                        class="bi bi-telephone-outbound-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5" />
                    </svg>
                    <span class="ms-2 fw-bold">{{ $shortCode }}</span>
                </a>
            </div>

            <form action="{{ route('commandes.store') }}" method="post" -onsubmit="mettreEnAttente()" class="text-center mt-4">
                @csrf
                <input type="hidden" id="adresse_livraison" name="adresse_livraison" value='{{ $client['adresse_livraison'] }}'>
                <input type="hidden" id="telephone_livraison" name="telephone_livraison" value='{{ $client['telephone_livraison'] }}'>
                <input type="hidden" id="mode_paiement" name="mode_paiement" value='{{ $client['mode_paiement'] }}'>
                <input type="hidden" id="casse" name="casse" value='{{ $casse->id }}'>

                {{-- methode 1 : traditionnel --}}
                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                    Confirmer le paiement
                </button>

                {{-- methode 2 : payement en ligne (fedapay) --}}
                {{-- <button class="btn btn-success px-4 py-2 rounded-pill pay-btn w-25 m-auto"
                    data-transaction-amount="{{ $montant }}" data-transaction-description="Acheter mon produit"
                    data-customer-email="leldamabellekoye95@gmail.com" data-customer-lastname="KOYE"
                    data-customer-firstname="Leleda ma belle" data-currency-iso="XOF"
                    data-customer-phone_number-country="TG" data-customer-phone_number-number="90992020"
                    data-currency-code="XOF">
                    Confirmer le paiement
                </button> --}}
            </form>
{{-- 
            <button class="btn btn-success px-4 py-2 rounded-pill pay-btn w-25 m-auto"
                data-transaction-amount="{{ $montant }}" data-transaction-description="Acheter mon produit"
                data-customer-email="leldamabellekoye95@gmail.com" data-customer-lastname="KOYE"
                data-customer-firstname="Leleda ma belle" data-currency-iso="XOF" data-customer-phone_number-country="TG"
                data-customer-phone_number-number="90992020" data-currency-code="XOF">
                Confirmer le paiement
            </button> --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function mettreEnAttente() {
            event.preventDefault();

            console.log("Votre commande est en cours de traitement. Veuillez patienter...");

            const formData = new FormData();

            formData.adresse_livraison = document.getElementById('adresse_livraison').value;
            formData.telephone_livraison = document.getElementById('telephone_livraison').value;
            formData.mode_paiement = document.getElementById('mode_paiement').value;
            formData.casse = document.getElementById('casse').value;

            console.log(formData);

            fetch("{{ route('commandes.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Succès :", data);
                // Vous pouvez rediriger l'utilisateur ou afficher un message de succès ici
            })
            .catch((error) => {
                console.error("Erreur :", error);
            });
        }
    </script>
@endsection