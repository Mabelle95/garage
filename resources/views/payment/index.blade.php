<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paiement Mobile Money</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus { outline: none; border-color: #3b82f6; }
        .btn { width: 100%; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-weight: 500; }
        .btn:hover { background: #2563eb; }
        .btn:disabled { background: #9ca3af; cursor: not-allowed; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .loading { display: none; text-align: center; margin-top: 20px; }
        .loading.active { display: block; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #3b82f6; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .error { display: none; padding: 15px; background: #fee; border: 1px solid #fcc; color: #c33; border-radius: 5px; margin-top: 15px; }
        .error.active { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h2>💳 Paiement Mobile Money</h2>

        <form id="payment-form">
            <div class="form-group">
                <label>Montant (FCFA) *</label>
                <input type="number" name="amount" id="amount" required min="100" value="1000">
            </div>

            <div class="form-group">
                <label>Description *</label>
                <input type="text" name="description" id="description" required value="Achat de produit">
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="customer_email" id="customer_email" required value="test@example.com">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="customer_firstname" id="customer_firstname" required value="John">
                </div>
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="customer_lastname" id="customer_lastname" required value="Doe">
                </div>
            </div>

            <div class="form-group">
                <label>Téléphone (optionnel)</label>
                <input type="tel" name="customer_phone" id="customer_phone" placeholder="+229XXXXXXXX">
            </div>

            <button type="submit" class="btn">Payer maintenant</button>
        </form>

        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Initialisation du paiement...</p>
        </div>

        <div id="error-message" class="error"></div>
    </div>

    <script>
        const form = document.getElementById('payment-form');
        const loading = document.getElementById('loading');
        const errorDiv = document.getElementById('error-message');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            console.log('=== SOUMISSION DU FORMULAIRE ===');

            // Récupérer les données
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            console.log('Données du formulaire:', data);

            // Afficher le loader
            loading.classList.add('active');
            errorDiv.classList.remove('active');
            submitBtn.disabled = true;

            try {
                console.log('Envoi de la requête...');

                const response = await fetch('{{ route('payment.initiate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Statut de la réponse:', response.status);

                const result = await response.json();
                console.log('Résultat:', result);

                if (result.success && result.url) {
                    console.log('Redirection vers:', result.url);
                    window.location.href = result.url;
                } else {
                    throw new Error(result.message || 'Erreur lors de l\'initialisation du paiement');
                }

            } catch (error) {
                console.error('ERREUR:', error);
                errorDiv.textContent = error.message;
                errorDiv.classList.add('active');
                submitBtn.disabled = false;
            } finally {
                loading.classList.remove('active');
            }
        });
    </script>
</body>
</html>
