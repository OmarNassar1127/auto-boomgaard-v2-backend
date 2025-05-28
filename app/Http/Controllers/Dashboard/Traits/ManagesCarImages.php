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
            'main_image_index' => 'nullable|integer|min:0',
        ]);

        $uploadedImages = [];
        $mainImageIndex = (int) $request->input('main_image_index', 0); // Cast to integer
        $images = $request->file('images');

        if (!$images || !is_array($images)) {
            return response()->json([
                'message' => 'No images provided.',
            ], Response::HTTP_BAD_REQUEST);
        }

        foreach ($images as $index => $image) {
            if ($image->isValid()) {
                $mediaAdder = $car->addMedia($image)
                    ->usingName("Car image " . ($index + 1))
                    ->usingFileName($image->getClientOriginalName());

                // Set custom properties for main image
                $isMainImage = ($index === $mainImageIndex);
                
                if ($isMainImage) {
                    $mediaAdder->withCustomProperties(['is_main' => true]);
                } else {
                    $mediaAdder->withCustomProperties(['is_main' => false]);
                }

                $media = $mediaAdder->toMediaCollection('images');
                $uploadedImages[] = $media;
            }
        }

        // If this is the first upload and no images were set as main, set first as main
        if (count($uploadedImages) > 0) {
            $hasMain = collect($uploadedImages)->some(function ($media) {
                return $media->getCustomProperty('is_main', false);
            });
            
            if (!$hasMain) {
                $uploadedImages[0]->setCustomProperty('is_main', true);
                $uploadedImages[0]->save();
            }
        }

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => count($uploadedImages) . ' images uploaded successfully.',
        ], Response::HTTP_CREATED);
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

        // Remove main flag from all images
        $car->getMedia('images')->each(function ($item) {
            $item->setCustomProperty('is_main', false);
            $item->save();
        });

        // Set this image as main
        $media->setCustomProperty('is_main', true);
        $media->save();

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
        if ($wasMain) {
            $remainingImages = $car->getMedia('images');
            if ($remainingImages->count() > 0) {
                $firstImage = $remainingImages->first();
                $firstImage->setCustomProperty('is_main', true);
                $firstImage->save();
            }
        }

        return response()->json([
            'data' => new CarResource($car->refresh()),
            'message' => 'Image deleted successfully.',
        ]);
    }
}
