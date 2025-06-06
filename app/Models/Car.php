<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Car extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand',
        'model',
        'price',
        'tax_info',
        'mileage',
        'year',
        'color',
        'transmission',
        'fuel',
        'power',
        'specifications',
        'highlights',
        'options_accessories',
        'vehicle_status',
        'post_status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specifications' => 'array',
        'highlights' => 'array',
        'options_accessories' => 'array',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'year' => 'integer',
        'power' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // When a car is deleted, also delete its media
        static::deleting(function (Car $car) {
            $car->clearMediaCollection('images');
        });
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
            ->useDisk('public');
    }

    /**
     * Get the main image for the car.
     */
    public function getMainImageAttribute(): ?Media
    {
        return $this->getMedia('images')
            ->filter(function (Media $media) {
                return $media->getCustomProperty('is_main', false);
            })
            ->first();
    }

    /**
     * Get all images except the main image.
     */
    public function getOtherImagesAttribute()
    {
        return $this->getMedia('images')
            ->filter(function (Media $media) {
                return !$media->getCustomProperty('is_main', false);
            });
    }

    /**
     * Set a media item as the main image.
     */
    public function setMainImage(Media $media): void
    {
        // Remove main flag from all images
        $this->getMedia('images')->each(function (Media $image) {
            $image->setCustomProperty('is_main', false);
            $image->save();
        });

        // Set this image as main
        $media->setCustomProperty('is_main', true);
        $media->save();
    }

    /**
     * Get formatted price for display.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price, 2, ',', '.');
    }

    /**
     * Get formatted mileage for display.
     */
    public function getFormattedMileageAttribute(): string
    {
        return number_format($this->mileage, 0, ',', '.') . ' km';
    }

    /**
     * Get formatted power for display.
     */
    public function getFormattedPowerAttribute(): string
    {
        return $this->power . ' pk';
    }

    /**
     * Mutator for price to handle Dutch formatting input.
     */
    public function setPriceAttribute($value): void
    {
        if (is_string($value)) {
            // Remove currency symbol and format to decimal
            $cleanValue = preg_replace('/[^\d,.]/', '', $value);
            $cleanValue = str_replace('.', '', $cleanValue); // Remove thousands separator
            $cleanValue = str_replace(',', '.', $cleanValue); // Replace comma with dot
            $this->attributes['price'] = (float) $cleanValue;
        } else {
            $this->attributes['price'] = $value;
        }
    }
}
