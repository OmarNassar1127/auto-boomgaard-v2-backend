<?php

namespace App\Http\Controllers\Dashboard\Traits;

use App\Http\Resources\Dashboard\CarResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ManagesCarImages
{
    /**
     * Upload images for a car.
     */
    public function uploadImages(Request $request, Car $car): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB max
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $media = $car->addMediaFromRequest('images')
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('images');
                });

            if (is_array($media)) {
                $uploadedImages = array_merge($uploadedImages, $media);
            } else {
                $uploadedImages[] = $media;
            }
        }

        // If this is the first image uploaded, set it as main
        if ($car->getMedia('images')->count() === count($uploadedImages)) {
            $car->setMainImage($uploadedImages[0]);
        }

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Images uploaded successfully.',
        ]);
    }

    /**
     * Set main image for a car.
     */
    public function setMainImage(Car $car, Media $media): JsonResponse
    {
        // Verify the media belongs to this car
        if ($media->model_id !== $car->id || $media->model_type !== Car::class) {
            return response()->json([
                'message' => 'Image not found for this car.',
            ], Response::HTTP_NOT_FOUND);
        }

        $car->setMainImage($media);

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Main image set successfully.',
        ]);
    }

    /**
     * Delete an image from a car.
     */
    public function deleteImage(Car $car, Media $media): JsonResponse
    {
        // Verify the media belongs to this car
        if ($media->model_id !== $car->id || $media->model_type !== Car::class) {
            return response()->json([
                'message' => 'Image not found for this car.',
            ], Response::HTTP_NOT_FOUND);
        }

        $wasMain = $media->getCustomProperty('is_main', false);
        $media->delete();

        // If we deleted the main image, set another image as main if available
        if ($wasMain && $car->getMedia('images')->count() > 0) {
            $car->setMainImage($car->getMedia('images')->first());
        }

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Image deleted successfully.',
        ]);
    }
}
