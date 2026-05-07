<?php

namespace App\Http\Controllers;

use App\Services\SeasonalityAlertService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeasonalityAlertController extends Controller
{
    /**
     * Show grocery seasonality alerts.
     */
    public function index(SeasonalityAlertService $seasonalityAlertService): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor() || $user->isVendor() || $user->isAdmin(), 403);

        return view('seasonality-alerts.index', [
            'alerts' => $seasonalityAlertService->alerts(),
        ]);
    }
}