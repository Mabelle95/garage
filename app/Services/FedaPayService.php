<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Str;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class FedaPayService
{
    public function __construct()
    {
        FedaPay::setApiKey(config('fedapay.secret_key'));
        FedaPay::setEnvironment(config('fedapay.environment'));
    }

    public function createTransaction(array $data): array
    {
        try {
            \Log::info('=== DÉBUT CRÉATION TRANSACTION ===');
            \Log::info('Données reçues:', $data);

            // Vérifier la configuration
            if (empty(config('fedapay.secret_key'))) {
                throw new \Exception('Clé secrète FedaPay non configurée');
            }

            // Créer l'enregistrement local
            $transactionId = 'TXN_' . strtoupper(Str::random(10));

            $payment = Payment::create([
                'user_id' => $data['user_id'] ?? null,
                'transaction_id' => $transactionId,
                'amount' => $data['amount'],
                'currency' => 'XOF',
                'description' => $data['description'],
                'customer_email' => $data['customer_email'],
                'customer_firstname' => $data['customer_firstname'],
                'customer_lastname' => $data['customer_lastname'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'status' => 'pending',
            ]);

            \Log::info('Payment créé en base:', ['id' => $payment->id]);

            // Créer la transaction FedaPay
            $fedapayTransaction = Transaction::create([
                'description' => $payment->description,
                'amount' => (int) $payment->amount,
                'currency' => ['iso' => 'XOF'],
                'callback_url' => config('fedapay.callback_url'),
                'customer' => [
                    'firstname' => $payment->customer_firstname,
                    'lastname' => $payment->customer_lastname,
                    'email' => $payment->customer_email,
                    'phone_number' => [
                        'number' => $payment->customer_phone ?? '',
                        'country' => 'bj'
                    ]
                ]
            ]);

            \Log::info('Transaction FedaPay créée:', ['id' => $fedapayTransaction->id]);

            // Générer le token
            $token = $fedapayTransaction->generateToken();

            \Log::info('Token généré:', ['token' => substr($token->token, 0, 20) . '...']);

            // Mettre à jour avec l'ID FedaPay
            $payment->update([
                'fedapay_transaction_id' => $fedapayTransaction->id,
            ]);

            \Log::info('=== FIN CRÉATION TRANSACTION (SUCCÈS) ===');

            return [
                'success' => true,
                'payment' => $payment,
                'token' => $token->token,
                'url' => $token->url,
            ];

        } catch (\FedaPay\Error\ApiConnection $e) {
            \Log::error('Erreur de connexion FedaPay:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Impossible de se connecter à FedaPay. Vérifiez votre connexion internet.',
            ];
        } catch (\FedaPay\Error\InvalidRequest $e) {
            \Log::error('Requête invalide FedaPay:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Paramètres de transaction invalides: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur création transaction:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ];
        }
    }

    public function verifyTransaction(string $transactionId): array
    {
        try {
            $transaction = Transaction::retrieve($transactionId);

            $payment = Payment::where('fedapay_transaction_id', $transactionId)->first();

            if ($payment) {
                $payment->update([
                    'status' => $transaction->status,
                ]);
            }

            return [
                'success' => true,
                'transaction' => $transaction,
                'payment' => $payment,
            ];

        } catch (\Exception $e) {
            \Log::error('Erreur vérification transaction:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
