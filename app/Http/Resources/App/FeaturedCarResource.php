<?php

namespace App\Http\Resources\App;

use App\Http\Resources\App\Traits\CarResourceHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedCarResource extends JsonResource
{
    use CarResourceHelpers;

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Extract mileage number for frontend sorting/filtering
        $mileageNumber = (int) preg_replace('/[^\d]/', '', $this->mileage ?? '0');
        
        return [
            'id' => (string) $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'variant' => $this->buildVariant(),
            'price' => $this->parsePriceToNumber(),
            'image' => $this->getMainImageUrl(),
            'kilometers' => $mileageNumber,
            'year' => (int) $this->year,
            'color' => $this->color,
            'includingVAT' => $this->includesVAT(),
            'isPromo' => $this->isRecentlyAdded(),
        ];
    }
}
