<?php

namespace App\Http\Controllers;

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