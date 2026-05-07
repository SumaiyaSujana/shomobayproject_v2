<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDisputeController extends Controller
{
    /**
     * Show all disputes for admin review.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        $disputes = Dispute::with([
                'order.groupCart.groceryItem',
                'user',
                'vendor.vendorProfile',
                'resolvedBy',
            ])
            ->latest()
            ->get();

        return view('admin-disputes.index', [
            'disputes' => $disputes,
        ]);
    }

    /**
     * Resolve or reject a dispute.
     */
    public function update(Request $request, Dispute $dispute): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        if ($dispute->status !== Dispute::STATUS_OPEN) {
            return back()->withErrors([
                'dispute' => 'This dispute has already been reviewed.',
            ]);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:resolved,rejected'],
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $dispute->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'],
            'resolved_by_user_id' => $user->id,
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('admin.disputes.index')
            ->with('status', 'dispute-updated');
    }
}