<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Vehicle
 */
class VehicleController extends Controller
{

    public function index()
    {
      return  VehicleResource::collection(Vehicle::all());
    }

    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::query()->create($request->only('plate_number'));
        return VehicleResource::make($vehicle);
    }

    public function show(Vehicle $vehicle)
    {
        return VehicleResource::make($vehicle);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->only('plate_number'));
        return response()->json(VehicleResource::make($vehicle), Response::HTTP_ACCEPTED);
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return response()->noContent();
    }
}
