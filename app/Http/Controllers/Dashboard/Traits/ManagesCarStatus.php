<?php

namespace App\Http\Controllers\Dashboard\Traits;

use App\Http\Resources\Dashboard\CarResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ManagesCarStatus
{
    /**
     * Toggle publish status of a car (draft/published).
     */
    public function togglePublishStatus(Request $request, Car $car): JsonResponse
    {
        $request->validate([
            'post_status' => 'required|in:draft,published',
        ]);

        $car->update([
            'post_status' => $request->post_status,
        ]);

        $message = $request->post_status === 'published' 
            ? 'Car published successfully.' 
            : 'Car moved to draft successfully.';

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => $message,
        ]);
    }

    /**
     * Update vehicle status of a car (sold/listed/reserved/upcoming).
     */
    public function updateVehicleStatus(Request $request, Car $car): JsonResponse
    {
        $request->validate([
            'vehicle_status' => 'required|in:sold,listed,reserved,upcoming',
        ]);

        $car->update([
            'vehicle_status' => $request->vehicle_status,
        ]);

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Vehicle status updated successfully.',
        ]);
    }
}
