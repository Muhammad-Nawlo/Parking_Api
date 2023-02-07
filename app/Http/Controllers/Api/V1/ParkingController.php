<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Parking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ParkingPriceService;
use App\Http\Resources\ParkingResource;
/**
 * @group Parking
 */
class ParkingController extends Controller
{
    public function start(Request $request)
    {
        $parkingData = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
        ]);
        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');

        return ParkingResource::make($parking);
    }
    public function show(Parking $parking)
    {
        return ParkingResource::make($parking);
    }
    public function stop(Parking $parking)
    {
        $parking->update([
            'end_time' => now(),
            'total_price' => ParkingPriceService::calculatePrice($parking->zone_id, $parking->start_time),
        ]);

        return ParkingResource::make($parking);
    }
}
