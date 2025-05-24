<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * CarListResource - Optimized resource for car listings
 * Only includes essential data and main image for performance
 */
class CarListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'tax_info' => $this->tax_info,
            'mileage' => $this->mileage,
            'formatted_mileage' => $this->formatted_mileage,
            'year' => $this->year,
            'color' => $this->color,
            'transmission' => $this->transmission,
            'fuel' => $this->fuel,
            'power' => $this->power,
            'formatted_power' => $this->formatted_power,
            'vehicle_status' => $this->vehicle_status,
            'post_status' => $this->post_status,
            'main_image' => $this->main_image?->getUrl(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
