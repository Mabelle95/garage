<?php

namespace App\Http\Controllers;

use App\Services\FedaPayService;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function show()
    {
        return view('payment.index');
    }

    public function initiate(Request $request)
    {
        try {
            \Log::info('== REQUÊTE PAIEMENT REÇUE ==');
            \Log::info('Données brutes:', $request->all());

            // Validation
            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'description' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_firstname' => 'required|string|max:100',
                'customer_lastname' => 'required|string|max:100',
                'customer_phone' => 'nullable|string|max:20',
            ]);

            \Log::info('Validation OK');

            $validated['user_id'] = auth()->id();

            // Créer la transaction
            $fedaPayService = new FedaPayService();
            $result = $fedaPayService->createTransaction($validated);

            if ($result['success']) {
                \Log::info('Transaction créée avec succès');

                return response()->json([
                    'success' => true,
                    'token' => $result['token'],
                    'url' => $result['url'],
                    'transaction_id' => $result['payment']->transaction_id,
                ]);
            }

            \Log::error('Échec création transaction:', ['message' => $result['message']]);

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation:', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur inattendue:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            \Log::info('=== CALLBACK REÇU ===');
            \Log::info('Query params:', $request->query());

            $transactionId = $request->query('id');

            if (!$transactionId) {
                return redirect()->route('payment.cancel')
                    ->with('error', 'Transaction introuvable');
            }

            $fedaPayService = new FedaPayService();
            $result = $fedaPayService->verifyTransaction($transactionId);

            if ($result['success'] && $result['payment']) {
                if ($result['payment']->isApproved()) {
                    return redirect()->route('payment.success', $result['payment']->transaction_id);
                }
            }

            return redirect()->route('payment.cancel')
                ->with('error', 'Le paiement a échoué ou a été annulé');

        } catch (\Exception $e) {
            \Log::error('Erreur callback:', ['message' => $e->getMessage()]);
            return redirect()->route('payment.cancel')
                ->with('error', 'Une erreur est survenue');
        }
    }

    public function success($transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();
        return view('payment.success', compact('payment'));
    }

    public function cancel()
    {
        return view('payment.cancel');
    }

    public function webhook(Request $request)
    {
        try {
            \Log::info('=== WEBHOOK REÇU ===');
            \Log::info('Payload:', $request->all());

            $payload = $request->all();
            $transactionId = $payload['entity']['id'] ?? null;

            if ($transactionId) {
                $payment = Payment::where('fedapay_transaction_id', $transactionId)->first();

                if ($payment) {
                    $payment->update([
                        'status' => $payload['entity']['status'] ?? 'pending',
                    ]);
                    \Log::info('Statut mis à jour:', ['status' => $payment->status]);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Erreur webhook:', ['message' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }
}
