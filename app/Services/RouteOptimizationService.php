<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

class RouteOptimizationService
{
    /**
     * Build an optimized delivery route for a vendor's escrow-held orders.
     */
    public function optimizedStopsForVendor(User $vendor): Collection
    {
        $orders = Order::with([
                'groupCart.groceryItem',
                'groupCart.contributions.user',
                'substitutionRequest',
            ])
            ->where('vendor_user_id', $vendor->id)
            ->where('status', Order::STATUS_ESCROW_HELD)
            ->latest()
            ->get();

        $stops = $orders->map(function (Order $order) {
            $coordinates = $this->parseCoordinates($order->groupCart?->location_coordinates);

            return [
                'order' => $order,
                'cart' => $order->groupCart,
                'latitude' => $coordinates['latitude'] ?? null,
                'longitude' => $coordinates['longitude'] ?? null,
                'distance_from_previous_km' => null,
                'sequence' => null,
            ];
        })->values();

        if ($stops->isEmpty()) {
            return collect();
        }

        $currentLocation = $this->startingLocation($stops);
        $remainingStops = $stops;
        $optimizedStops = collect();

        while ($remainingStops->isNotEmpty()) {
            $nearestIndex = null;
            $nearestDistance = null;

            foreach ($remainingStops as $index => $stop) {
                $distance = $this->distanceFromCurrentLocation($currentLocation, $stop);

                if ($nearestDistance === null || $distance < $nearestDistance) {
                    $nearestDistance = $distance;
                    $nearestIndex = $index;
                }
            }

            $nextStop = $remainingStops->get($nearestIndex);
            $nextStop['distance_from_previous_km'] = $nearestDistance ?? 0;
            $nextStop['sequence'] = $optimizedStops->count() + 1;

            $optimizedStops->push($nextStop);

            if ($nextStop['latitude'] !== null && $nextStop['longitude'] !== null) {
                $currentLocation = [
                    'latitude' => $nextStop['latitude'],
                    'longitude' => $nextStop['longitude'],
                ];
            }

            $remainingStops->forget($nearestIndex);
            $remainingStops = $remainingStops->values();
        }

        return $optimizedStops;
    }

    /**
     * Create route summary for the vendor route page.
     */
    public function routeSummary(Collection $stops): array
    {
        return [
            'total_orders' => $stops->count(),
            'total_buildings' => $stops
                ->pluck('cart.apartment_building')
                ->filter()
                ->unique()
                ->count(),
            'total_weight_kg' => $stops->sum(function (array $stop) {
                return ($stop['cart']?->current_weight_grams ?? 0) / 1000;
            }),
            'total_order_value_paisa' => $stops->sum(function (array $stop) {
                return $stop['order']->total_amount_paisa;
            }),
            'estimated_route_distance_km' => $stops->sum(function (array $stop) {
                return $stop['distance_from_previous_km'] ?? 0;
            }),
        ];
    }

    /**
     * Use first valid delivery coordinate as demo starting point.
     */
    private function startingLocation(Collection $stops): array
    {
        $firstStopWithCoordinates = $stops->first(function (array $stop) {
            return $stop['latitude'] !== null && $stop['longitude'] !== null;
        });

        if ($firstStopWithCoordinates) {
            return [
                'latitude' => $firstStopWithCoordinates['latitude'],
                'longitude' => $firstStopWithCoordinates['longitude'],
            ];
        }

        return [
            'latitude' => 23.8103,
            'longitude' => 90.4125,
        ];
    }

    /**
     * Calculate distance from the current point to one stop.
     */
    private function distanceFromCurrentLocation(array $currentLocation, array $stop): float
    {
        if ($stop['latitude'] === null || $stop['longitude'] === null) {
            return 9999;
        }

        return $this->haversineDistance(
            $currentLocation['latitude'],
            $currentLocation['longitude'],
            $stop['latitude'],
            $stop['longitude']
        );
    }

    /**
     * Parse location string like "23.8103,90.4125".
     */
    private function parseCoordinates(?string $coordinates): ?array
    {
        if (!$coordinates) {
            return null;
        }

        $parts = array_map('trim', explode(',', $coordinates));

        if (count($parts) !== 2) {
            return null;
        }

        if (!is_numeric($parts[0]) || !is_numeric($parts[1])) {
            return null;
        }

        return [
            'latitude' => (float) $parts[0],
            'longitude' => (float) $parts[1],
        ];
    }

    /**
     * Calculate distance between two coordinates.
     */
    private function haversineDistance(
        float $latitudeOne,
        float $longitudeOne,
        float $latitudeTwo,
        float $longitudeTwo
    ): float {
        $earthRadiusKm = 6371;

        $latitudeDifference = deg2rad($latitudeTwo - $latitudeOne);
        $longitudeDifference = deg2rad($longitudeTwo - $longitudeOne);

        $a = sin($latitudeDifference / 2) * sin($latitudeDifference / 2)
            + cos(deg2rad($latitudeOne))
            * cos(deg2rad($latitudeTwo))
            * sin($longitudeDifference / 2)
            * sin($longitudeDifference / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadiusKm * $c, 2);
    }
}