<?php


// app/Http/Controllers/Api/LocationController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCassesNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:1|max:200'
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 50; // km par défaut

        $casses = User::where('role', 'casse')
            ->where('actif', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius])
            ->select([
                'id', 'nom_entreprise', 'ville', 'adresse',
                'latitude', 'longitude', 'telephone', 'description'
            ])
            ->get()
            ->map(function($casse) use($lat, $lng) {
                // Calculer la distance exacte
                $distance = $this->calculateDistance($lat, $lng, $casse->latitude, $casse->longitude);
                $casse->distance = round($distance, 2);
                return $casse;
            })
            ->sortBy('distance');

        return response()->json($casses);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}
