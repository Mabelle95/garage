<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement réussi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paiement réussi !</h2>
            <p class="text-gray-600 mb-6">Votre transaction a été effectuée avec succès.</p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Transaction ID:</span>
                    <span class="font-medium">{{ $payment->transaction_id }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Montant:</span>
                    <span class="font-medium">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Statut:</span>
                    <span class="font-medium text-green-600">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>

            <a href="{{ url('/') }}" class="inline-block bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
