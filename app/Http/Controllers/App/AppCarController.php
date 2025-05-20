<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\FeaturedCarResource;
use App\Http\Resources\App\CarListResource;
use App\Http\Resources\App\CarDetailResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;

class AppCarController extends Controller
{
    /**
     * Get featured cars for the public website.
     */
    public function featured(): JsonResponse
    {
        // Get featured cars based on:
        // 1. Published status
        // 2. Listed vehicle status  
        // 3. Most recent first
        // 4. Limit to 6 cars
        $featuredCars = Car::query()
            ->where('post_status', 'published')
            ->where('vehicle_status', 'listed')
            ->with('media') // Load images
            ->latest('created_at')
            ->limit(6)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => FeaturedCarResource::collection($featuredCars),
        ]);
    }

    /**
     * Get all cars for public listings.
     */
    public function index(): JsonResponse
    {
        // Get all published and listed cars
        $cars = Car::query()
            ->where('post_status', 'published')
            ->where('vehicle_status', 'listed')
            ->with('media')
            ->latest('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => CarListResource::collection($cars),
        ]);
    }

    /**
     * Get single car details for public view.
     */
    public function show(Car $car): JsonResponse
    {
        // Check if car is published and available
        if ($car->post_status !== 'published') {
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found',
            ], 404);
        }
        
        // Load all media associated with the car
        $car->load('media');
        
        return response()->json([
            'status' => 'success',
            'data' => new CarDetailResource($car),
        ]);
    }
}
