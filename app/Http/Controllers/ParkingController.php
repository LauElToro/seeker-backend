<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use App\Services\DistanceService;

class ParkingController extends Controller
{
    public function index()
    {
        return response()->json(Parking::all());
    }

    public function show($id)
    {
        $parking = Parking::find($id);
        if (!$parking) {
            return response()->json(['error' => 'Parking not found'], 404);
        }
        return response()->json($parking);
    }

    public function store(Request $request)
{
    logger('Entrando al método store');

    $data = $request->validate([
        'name' => 'required|string',
        'address' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $parking = Parking::create($data);
    return response()->json($parking, 201);
}

    public function nearest(Request $request)
    {
        $data = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $closest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach (Parking::all() as $parking) {
            $distance = DistanceService::calculateDistance(
                $data['latitude'],
                $data['longitude'],
                $parking->latitude,
                $parking->longitude
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closest = $parking;
            }
        }

        if ($minDistance > 500 && $closest !== null) {
            RequestLog::create([
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'distance_meters' => (int) $minDistance,
            ]);
        }

        return response()->json([
            'parking' => $closest,
            'distance_meters' => round($minDistance),
            'exceeds_500' => $minDistance > 500,
        ]);
    }
}
