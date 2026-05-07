<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NeighborProfileController extends Controller
{
    /**
     * Show the neighbor profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $neighborProfile = $user->neighborProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return view('profiles.neighbor-edit', [
            'user' => $user,
            'neighborProfile' => $neighborProfile,
        ]);
    }

    /**
     * Update the neighbor profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $validated = $request->validate([
            'apartment_building' => ['required', 'string', 'max:255'],
            'location_coordinates' => ['nullable', 'string', 'max:255'],
        ]);

        $neighborProfile = $user->neighborProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $neighborProfile->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('status', 'neighbor-profile-updated');
    }
}