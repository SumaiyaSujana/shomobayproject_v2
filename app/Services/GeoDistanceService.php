<?php

namespace App\Services;

class GeoDistanceService
{
    /**
     * Maximum allowed cart distance in kilometers.
     */
    public const MAX_DISTANCE_KM = 1.0;

    /**
     * Parse coordinates from "latitude,longitude" format.
     *
     * Example: "23.8103,90.4125"
     */
    public function parseCoordinates(?string $coordinates): ?array
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
     * Calculate distance between two coordinates using the Haversine formula.
     */
    public function distanceInKm(
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude
    ): float {
        $earthRadiusKm = 6371;

        $latitudeDifference = deg2rad($toLatitude - $fromLatitude);
        $longitudeDifference = deg2rad($toLongitude - $fromLongitude);

        $a = sin($latitudeDifference / 2) * sin($latitudeDifference / 2)
            + cos(deg2rad($fromLatitude))
            * cos(deg2rad($toLatitude))
            * sin($longitudeDifference / 2)
            * sin($longitudeDifference / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }

    /**
     * Check whether a cart is within the allowed 1 km radius.
     */
    public function isWithinOneKm(?string $userCoordinates, ?string $cartCoordinates): bool
    {
        $userLocation = $this->parseCoordinates($userCoordinates);
        $cartLocation = $this->parseCoordinates($cartCoordinates);

        if (!$userLocation || !$cartLocation) {
            return false;
        }

        $distance = $this->distanceInKm(
            $userLocation['latitude'],
            $userLocation['longitude'],
            $cartLocation['latitude'],
            $cartLocation['longitude']
        );

        return $distance <= self::MAX_DISTANCE_KM;
    }

    /**
     * Get formatted distance between user and cart.
     */
    public function formattedDistance(?string $userCoordinates, ?string $cartCoordinates): string
    {
        $userLocation = $this->parseCoordinates($userCoordinates);
        $cartLocation = $this->parseCoordinates($cartCoordinates);

        if (!$userLocation || !$cartLocation) {
            return 'Distance unavailable';
        }

        $distance = $this->distanceInKm(
            $userLocation['latitude'],
            $userLocation['longitude'],
            $cartLocation['latitude'],
            $cartLocation['longitude']
        );

        return number_format($distance, 2) . ' km away';
    }
}