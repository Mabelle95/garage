<?php


// app/Http/Controllers/Controller.php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Activite;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Réponse JSON standardisée pour succès
     */
    protected function successResponse($data = null, $message = 'Opération réussie', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Réponse JSON standardisée pour erreur
     */
    protected function errorResponse($message = 'Une erreur est survenue', $errors = null, $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Logger une activité utilisateur
     */
    protected function logActivity($type, $description, $data = null)
    {
        if (auth()->check()) {
            Activite::log($type, $description, $data);
        }
    }

    /**
     * Gérer les réponses selon le type de requête (AJAX/HTTP)
     */
    protected function handleResponse(Request $request, $successMessage, $redirectRoute, $data = null)
    {
        if ($request->expectsJson()) {
            return $this->successResponse($data, $successMessage);
        }

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Gérer les erreurs selon le type de requête
     */
    protected function handleError(Request $request, $errorMessage, $redirectBack = true)
    {
        if ($request->expectsJson()) {
            return $this->errorResponse($errorMessage);
        }

        if ($redirectBack) {
            return back()->with('error', $errorMessage);
        }

        return redirect()->route('dashboard')->with('error', $errorMessage);
    }

    /**
     * Pagination personnalisée pour API
     */
    protected function paginateResponse($paginator, $transformer = null)
    {
        $data = $transformer ? $paginator->getCollection()->map($transformer) : $paginator->items();

        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages()
            ]
        ];
    }
}
