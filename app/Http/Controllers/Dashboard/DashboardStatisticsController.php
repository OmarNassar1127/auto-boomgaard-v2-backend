<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsController extends Controller
{
    /**
     * Get all dashboard statistics in one endpoint.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currentYear = now()->year;
        
        // Total cars
        $totalCars = Car::count();
        
        // Get all cars that are not sold (for portfolio calculations)
        $portfolioCars = Car::where('vehicle_status', '!=', 'sold')
            ->where('price', '!=', '')
            ->whereNotNull('price')
            ->get();
        
        // Parse prices once for efficiency
        $portfolioCarPrices = $portfolioCars
            ->map(function ($car) {
                return $this->parsePrice($car->price);
            })
            ->filter(function ($price) {
                return $price > 0;
            });
        
        // Current portfolio value (total value of cars not sold)
        $currentPortfolioValue = $portfolioCarPrices->sum();
        
        // Cars sold this year
        $carsSoldThisYear = Car::where('vehicle_status', 'sold')
            ->whereYear('updated_at', $currentYear)
            ->count();
        
        // Average price in current portfolio
        $averagePrice = $portfolioCarPrices->avg();

        return response()->json([
            'data' => [
                'total_cars' => [
                    'label' => 'Totaal Auto\'s',
                    'value' => $totalCars,
                    'icon' => 'car',
                    'color' => 'blue'
                ],
                'current_portfolio' => [
                    'label' => 'Huidige Voorraad',
                    'value' => $currentPortfolioValue ? $this->formatCurrency($currentPortfolioValue) : '€0',
                    'icon' => 'package',
                    'color' => 'green'
                ],
                'cars_sold_this_year' => [
                    'label' => 'Verkocht Dit Jaar',
                    'value' => $carsSoldThisYear,
                    'icon' => 'trophy',
                    'color' => 'purple'
                ],
                'average_price' => [
                    'label' => 'Gemiddelde Prijs',
                    'value' => $averagePrice ? $this->formatCurrency($averagePrice) : '€0',
                    'icon' => 'euro',
                    'color' => 'orange'
                ]
            ],
            'message' => 'Statistics retrieved successfully.'
        ]);
    }
    
    /**
     * Parse a price string and return a float value.
     * 
     * @param string $priceString
     * @return float
     */
    private function parsePrice(string $priceString): float
    {
        // Remove all non-numeric characters except commas and dots
        $price = preg_replace('/[^0-9,.]/', '', $priceString);
        
        // Handle European format (comma as decimal separator)
        // If the price contains both comma and dot, assume dot is thousands separator
        if (strpos($price, ',') !== false && strpos($price, '.') !== false) {
            // Format: €54.990,00 -> remove dots, replace comma with dot
            $price = str_replace('.', '', $price);
            $price = str_replace(',', '.', $price);
        } elseif (strpos($price, ',') !== false) {
            // Format: €54990,00 -> replace comma with dot
            $price = str_replace(',', '.', $price);
        }
        // If only dots, assume they are thousands separators unless it's the last 3 characters
        elseif (strpos($price, '.') !== false) {
            $lastDotPos = strrpos($price, '.');
            // If the dot is followed by exactly 2 digits, it's a decimal separator
            if (strlen($price) - $lastDotPos == 3) {
                // Format: €54990.00 -> keep as is
            } else {
                // Format: €54.990 -> remove dots
                $price = str_replace('.', '', $price);
            }
        }
        
        return floatval($price);
    }
    
    /**
     * Format a number as currency in Dutch format.
     * 
     * @param float $amount
     * @return string
     */
    private function formatCurrency(float $amount): string
    {
        return '€' . number_format($amount, 0, ',', '.');
    }
}
