<?php

namespace App\Http\Controllers;

use App\Models\VendorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminVendorController extends Controller
{
    /**
     * Show all vendor verification requests to admin.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        $vendors = VendorProfile::with('user')
            ->latest()
            ->get();

        return view('admin.vendors.index', [
            'vendors' => $vendors,
        ]);
    }

    /**
     * Approve a vendor profile.
     */
    public function approve(VendorProfile $vendorProfile): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        $vendorProfile->update([
            'is_verified' => true,
        ]);

        return redirect()
            ->route('admin.vendors.index')
            ->with('status', 'vendor-approved');
    }

    /**
     * Mark a vendor as pending again.
     */
    public function markPending(VendorProfile $vendorProfile): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        $vendorProfile->update([
            'is_verified' => false,
        ]);

        return redirect()
            ->route('admin.vendors.index')
            ->with('status', 'vendor-marked-pending');
    }
}