<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Traits\ManagesCarImages;
use App\Http\Controllers\Dashboard\Traits\ManagesCarStatus;
use App\Http\Requests\Dashboard\CarStoreRequest;
use App\Http\Requests\Dashboard\CarUpdateRequest;
use App\Http\Resources\Dashboard\CarResource;
use App\Http\Resources\Dashboard\CarListCollection;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DashboardCarController extends Controller
{
    use ManagesCarImages, ManagesCarStatus;

    /**
     * Display a listing of the resource.
     * Uses optimized CarListCollection for performance.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Car::query();

        // Filter by vehicle status
        if ($request->has('vehicle_status')) {
            $query->where('vehicle_status', $request->vehicle_status);
        }

        // Filter by post status
        if ($request->has('post_status')) {
            $query->where('post_status', $request->post_status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%");
            });
        }

        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');

        // Paginate results
        $cars = $query->paginate($request->get('per_page', 15));

        return response()->json(new CarListCollection($cars));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarStoreRequest $request): JsonResponse
    {
        $car = Car::create($request->validated());

        return response()->json([
            'data' => new CarResource($car),
            'message' => 'Car created successfully.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * Uses full CarResource with all images and details.
     */
    public function show(Car $car): JsonResponse
    {
        return response()->json([
            'data' => new CarResource($car),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarUpdateRequest $request, Car $car): JsonResponse
    {
        $car->update($request->validated());

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Car updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car): JsonResponse
    {
        $car->delete();

        return response()->json([
            'message' => 'Car deleted successfully.',
        ]);
    }
}
