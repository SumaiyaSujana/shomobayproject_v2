<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorProfileController extends Controller
{
    /**
     * Show the vendor profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        $vendorProfile = $user->vendorProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return view('profiles.vendor-edit', [
            'user' => $user,
            'vendorProfile' => $vendorProfile,
        ]);
    }

    /**
     * Update the vendor profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'trade_license_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $vendorProfile = $user->vendorProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $updateData = [
            'business_name' => $validated['business_name'],
        ];

        if ($request->hasFile('trade_license_file')) {
            $updateData['trade_license_file'] = $request
                ->file('trade_license_file')
                ->store('trade-licenses', 'public');

            $updateData['is_verified'] = false;
        }

        $vendorProfile->update($updateData);

        return redirect()
            ->route('dashboard')
            ->with('status', 'vendor-profile-updated');
    }
}