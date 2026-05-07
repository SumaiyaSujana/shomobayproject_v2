<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Send users to their own dashboard according to role.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('dashboards.admin', [
                'user' => $user,
                'totalUsers' => User::count(),
                'totalNeighbors' => User::where('role', User::ROLE_NEIGHBOR)->count(),
                'totalVendors' => User::where('role', User::ROLE_VENDOR)->count(),
                'pendingVendors' => VendorProfile::where('is_verified', false)->count(),
            ]);
        }

        if ($user->isVendor()) {
            return view('dashboards.vendor', [
                'user' => $user,
                'vendorProfile' => $user->vendorProfile,
                'wallet' => $user->wallet,
            ]);
        }

        return view('dashboards.neighbor', [
            'user' => $user,
            'neighborProfile' => $user->neighborProfile,
            'wallet' => $user->wallet,
        ]);
    }
}