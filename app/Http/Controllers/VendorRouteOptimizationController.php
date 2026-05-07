<?php

namespace App\Http\Controllers;

use App\Services\RouteOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorRouteOptimizationController extends Controller
{
    /**
     * Show optimized delivery route for vendor orders.
     */
    public function index(RouteOptimizationService $routeOptimizationService): View|RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        if (!$user->vendorProfile?->is_verified) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'vendor-not-verified');
        }

        $stops = $routeOptimizationService->optimizedStopsForVendor($user);

        return view('vendor-route-optimization.index', [
            'stops' => $stops,
            'summary' => $routeOptimizationService->routeSummary($stops),
        ]);
    }
}