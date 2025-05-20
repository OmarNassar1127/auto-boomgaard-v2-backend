<?php

namespace App\Http\Resources\App;

use App\Http\Resources\App\Traits\CarResourceHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarDetailResource extends JsonResource
{
    use CarResourceHelpers;

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Extract mileage number for frontend sorting/filtering
        $mileageNumber = (int) preg_replace('/[^\d]/', '', $this->mileage ?? '0');
        
        // Get all car images
        $allImages = $this->getMedia('images')->map(function($media) {
            return $media->getUrl();
        })->toArray();
        
        // Parse specifications from JSON to array
        $specs = $this->specifications ?: [];
        
        // Parse options from JSON
        $options = $this->options_accessories['data'] ?? [
            'exterieur' => [],
            'infotainment' => [],
            'interieur_comfort' => [],
            'extra' => []
        ];
        
        return [
            'id' => (string) $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'variant' => $this->buildVariant(),
            'price' => $this->price,
            'taxInfo' => $this->tax_info,
            'mileage' => $this->mileage,
            'year' => $this->year,
            'color' => $this->color,
            'transmission' => $this->transmission,
            'fuel' => $this->fuel,
            'power' => $this->power,
            
            // Specifications
            'firstRegistration' => $specs['first_registration_date'] ?? '',
            'seats' => $specs['seats'] ?? '',
            'torque' => $specs['torque'] ?? '',
            'acceleration' => $specs['acceleration'] ?? '',
            'wheelbase' => $specs['wheelbase'] ?? '',
            'cylinders' => $specs['cylinders'] ?? '',
            'modelYear' => $specs['model_date_from'] ?? '',
            'doors' => $specs['doors'] ?? '',
            'gears' => $specs['gears'] ?? '',
            'topSpeed' => $specs['top_speed'] ?? '',
            'tankCapacity' => $specs['tank_capacity'] ?? '',
            'engineCapacity' => $specs['engine_capacity'] ?? '',
            'weight' => $specs['weight'] ?? '',
            
            // Images
            'images' => $allImages,
            
            // Highlights content
            'highlights' => $this->highlights['content'] ?? '',
            
            // Options and accessories
            'options' => [
                'exterior' => $options['exterieur'] ?? [],
                'infotainment' => $options['infotainment'] ?? [],
                'interior' => $options['interieur_comfort'] ?? [],
                'safety' => $options['extra'] ?? []
            ],
            
            // Status information
            'vehicleStatus' => $this->vehicle_status,
            'postStatus' => $this->post_status,
            'isPromo' => $this->isRecentlyAdded()
        ];
    }
}
