<?php

namespace App\Http\Resources\App\Traits;

trait CarResourceHelpers
{
    /**
     * Build variant name from available data.
     */
    private function buildVariant(): string
    {
        $parts = array_filter([
            $this->power,
            $this->fuel,
            $this->transmission,
        ]);

        return implode(' ', $parts) ?: 'Standard';
    }

    /**
     * Parse price string to number.
     */
    private function parsePriceToNumber(): int
    {
        // Remove all non-digit characters and convert to integer
        return (int) preg_replace('/[^\d]/', '', $this->price ?? '0');
    }

    /**
     * Determine if car includes VAT based on tax_info.
     */
    private function includesVAT(): bool
    {
        // Check if tax_info contains "incl" (for "incl. BTW")
        return str_contains(strtolower($this->tax_info ?? ''), 'incl');
    }

    /**
     * Check if car is recently added (within last 14 days).
     */
    private function isRecentlyAdded(): bool
    {
        return $this->created_at >= now()->subDays(14);
    }

    /**
     * Get the main image URL or fallback to first image.
     */
    private function getMainImageUrl(): ?string
    {
        // Get main image using the model's accessor
        $mainImage = $this->main_image?->getUrl();
        
        // Fallback to first image if no main image is set
        return $mainImage ?: $this->getFirstMediaUrl('images');
    }
}
