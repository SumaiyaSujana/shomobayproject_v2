<?php

namespace App\Services;

use App\Models\GroceryItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeasonalityAlertService
{
    /**
     * Build seasonality alerts for active grocery items.
     */
    public function alerts(): Collection
    {
        $currentMonth = (int) now()->month;

        return GroceryItem::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function (GroceryItem $item) use ($currentMonth) {
                return $this->buildAlert($item, $currentMonth);
            });
    }

    /**
     * Build one alert card for a grocery item.
     */
    private function buildAlert(GroceryItem $item, int $currentMonth): array
    {
        $seasonData = $this->matchSeasonData($item->name);
        $isInSeason = in_array($currentMonth, $seasonData['months'], true);
        $startsSoon = $this->startsSoon($currentMonth, $seasonData['months']);

        $marketPrice = $item->market_price_per_kg_paisa;
        $wholesalePrice = $item->wholesale_price_per_kg_paisa;

        $savingsPercentage = 0;

        if ($marketPrice > 0 && $marketPrice > $wholesalePrice) {
            $savingsPercentage = round((($marketPrice - $wholesalePrice) / $marketPrice) * 100, 1);
        }

        if ($isInSeason) {
            $status = 'In Season';
            $badgeColor = 'green';
            $message = 'This item is in a favorable seasonal window. It is a good time to create a group cart and negotiate vendor bids.';
        } elseif ($startsSoon) {
            $status = 'Season Starts Soon';
            $badgeColor = 'yellow';
            $message = 'This item may become more available soon. Prepare demand early so vendors can plan supply.';
        } else {
            $status = 'Out of Peak Season';
            $badgeColor = 'red';
            $message = 'This item is outside its stronger seasonal window. Bulk buying can still help, but price volatility may be higher.';
        }

        return [
            'item' => $item,
            'status' => $status,
            'badge_color' => $badgeColor,
            'message' => $message,
            'season_months' => $this->formatMonths($seasonData['months']),
            'market_price_paisa' => $marketPrice,
            'wholesale_price_paisa' => $wholesalePrice,
            'savings_percentage' => $savingsPercentage,
        ];
    }

    /**
     * Match item name to a simple Bangladesh-focused seasonal calendar.
     */
    private function matchSeasonData(string $itemName): array
    {
        $name = Str::lower($itemName);

        $calendar = [
            'rice' => [
                'months' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            ],
            'potato' => [
                'months' => [12, 1, 2, 3],
            ],
            'onion' => [
                'months' => [12, 1, 2, 3, 4],
            ],
            'tomato' => [
                'months' => [11, 12, 1, 2, 3],
            ],
            'green chili' => [
                'months' => [6, 7, 8, 9, 10],
            ],
            'chili' => [
                'months' => [6, 7, 8, 9, 10],
            ],
            'mango' => [
                'months' => [5, 6, 7],
            ],
            'banana' => [
                'months' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            ],
            'eggplant' => [
                'months' => [11, 12, 1, 2, 3],
            ],
            'brinjal' => [
                'months' => [11, 12, 1, 2, 3],
            ],
            'cauliflower' => [
                'months' => [11, 12, 1, 2],
            ],
            'cabbage' => [
                'months' => [11, 12, 1, 2],
            ],
        ];

        foreach ($calendar as $keyword => $data) {
            if (Str::contains($name, $keyword)) {
                return $data;
            }
        }

        return [
            'months' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        ];
    }

    /**
     * Check whether the next seasonal month is near.
     */
    private function startsSoon(int $currentMonth, array $seasonMonths): bool
    {
        foreach ($seasonMonths as $month) {
            $distance = $month - $currentMonth;

            if ($distance < 0) {
                $distance += 12;
            }

            if ($distance > 0 && $distance <= 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Format month numbers for UI.
     */
    private function formatMonths(array $months): string
    {
        $monthNames = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        return collect($months)
            ->map(fn (int $month) => $monthNames[$month])
            ->join(', ');
    }
}