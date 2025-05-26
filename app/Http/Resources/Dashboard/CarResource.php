<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'specifications' => $this->specifications,
            'highlights' => $this->highlights,
            'options_accessories' => $this->options_accessories,
            'vehicle_status' => $this->vehicle_status,
            'post_status' => $this->post_status,
            'images' => [
                'main' => $this->main_image?->getUrl(),
                'all' => $this->getMedia('images')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => $media->getUrl(),
                        'is_main' => $media->getCustomProperty('is_main', false),
                    ];
                }),
            ],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
