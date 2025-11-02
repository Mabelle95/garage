<?php 
require_once('D:\STAGE\gara-master\vendor\autoload.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Intégrer Feda Checkout à mon site</title>
  <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
</head>
<body>
  <button class="pay-btn"
  data-transaction-amount="1000"
  data-transaction-description="Acheter mon produit"
  data-customer-email="leldamabellekoye95@gmail.com"
  data-customer-lastname="KOYE"
  data-customer-firstname="Leleda ma belle"
  data-currency-iso="XOF"
  data-customer-phone_number-country="TG"
  data-customer-phone_number-number="90992020"
  >Payer 1000 FCFA</button>

  <button class="pay-btn"
  data-transaction-amount="2000"
  data-transaction-description="Acheter mon produit"
  data-customer-email="janetay@gmail.com"
  data-customer-lastname="Tay">Payer 2000 FCFA</button>
  
  <script type="text/javascript">
        FedaPay.init('.pay-btn', { 
            public_key: 'pk_sandbox_7t_necBHPl5BShI5UTFONH3g' 
        });
  </script>
</body>
</html>

{{-- <!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paiement FedaPay - Casse Auto</title>
  <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
  
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      padding: 20px;
    }
    
    .payment-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      padding: 40px;
      max-width: 500px;
      width: 100%;
    }
    
    .payment-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .payment-header h1 {
      color: #333;
      font-size: 28px;
      margin-bottom: 10px;
    }
    
    .payment-header p {
      color: #666;
      font-size: 16px;
    }
    
    .product-card {
      background: #f8f9fa;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 20px;
      border: 2px solid #e9ecef;
      transition: all 0.3s ease;
    }
    
    .product-card:hover {
      border-color: #667eea;
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }
    
    .product-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .product-name {
      font-weight: bold;
      color: #333;
      font-size: 18px;
    }
    
    .product-price {
      font-size: 24px;
      font-weight: bold;
      color: #667eea;
    }
    
    .product-description {
      color: #666;
      font-size: 14px;
      margin-bottom: 15px;
    }
    
    .pay-btn {
      width: 100%;
      padding: 15px 30px;
      font-size: 18px;
      font-weight: bold;
      color: white;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .pay-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    
    .pay-btn:active {
      transform: translateY(0);
    }
    
    .payment-methods {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e9ecef;
    }
    
    .payment-method {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #666;
    }
    
    .secure-badge {
      text-align: center;
      margin-top: 20px;
      color: #28a745;
      font-size: 14px;
    }
    
    .secure-badge i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <div class="payment-header">
      <h1>🚗 Casse Auto</h1>
      <p>Paiement sécurisé avec FedaPay</p>
    </div>
    
    <!-- Produit 1 -->
    <div class="product-card">
      <div class="product-info">
        <span class="product-name">Pièce détachée Standard</span>
        <span class="product-price">1000 FCFA</span>
      </div>
      <p class="product-description">
        Pièce automobile de qualité standard avec garantie
      </p>
      <button class="pay-btn"
        data-transaction-amount="1000"
        data-transaction-description="Achat de pièce détachée standard"
        data-customer-email="leledamabellekoye95@gmail.com"
        data-customer-lastname="KOYE"
        data-customer-firstname="Leleda ma belle"
        data-currency-iso="XOF"
        data-customer-phone_number-country="TG"
        data-customer-phone_number-number="90992020">
        💳 Payer 1000 FCFA
      </button>
    </div>

    <!-- Produit 2 -->
    <div class="product-card">
      <div class="product-info">
        <span class="product-name">Pièce détachée Premium</span>
        <span class="product-price">2000 FCFA</span>
      </div>
      <p class="product-description">
        Pièce automobile premium avec garantie étendue
      </p>
      <button class="pay-btn"
        data-transaction-amount="2000"
        data-transaction-description="Achat de pièce détachée premium"
        data-customer-email="janetay@gmail.com"
        data-customer-lastname="Tay"
        data-customer-firstname="Jane"
        data-currency-iso="XOF"
        data-customer-phone_number-country="TG"
        data-customer-phone_number-number="90992021">
        💳 Payer 2000 FCFA
      </button>
    </div>

    <!-- Méthodes de paiement acceptées -->
    <div class="payment-methods">
      <div class="payment-method">
        <span>📱 Mobile Money</span>
      </div>
      <div class="payment-method">
        <span>💳 Carte bancaire</span>
      </div>
      <div class="payment-method">
        <span>🏦 Virement</span>
      </div>
    </div>

    <!-- Badge sécurisé -->
    <div class="secure-badge">
      🔒 Paiement 100% sécurisé par FedaPay
    </div>
  </div>
  
  <script type="text/javascript">
    // Initialiser FedaPay avec la clé publique
    FedaPay.init('.pay-btn', { 
      public_key: 'pk_sandbox_7t_necBHPl5BShI5UTFONH3g',
      // Callback en cas de succès
      onComplete: function(response) {
        console.log('Paiement réussi:', response);
        alert('✅ Paiement effectué avec succès !\nTransaction ID: ' + response.id);
        
        // Rediriger vers une page de confirmation
        // window.location.href = '/payment-success?transaction=' + response.id;
      },
      // Callback en cas d'erreur
      onError: function(error) {
        console.error('Erreur de paiement:', error);
        alert('❌ Erreur lors du paiement. Veuillez réessayer.');
      },
      // Callback si l'utilisateur ferme le popup
      onCancel: function() {
        console.log('Paiement annulé par l\'utilisateur');
        alert('⚠️ Paiement annulé');
      }
    });
  </script>
</body>
</html> --}}