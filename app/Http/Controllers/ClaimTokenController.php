<?php

namespace App\Http\Controllers;

use App\Models\CartContribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClaimTokenController extends Controller
{
    /**
     * Show all claim tokens for the logged-in neighbor.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $contributions = CartContribution::with([
                'groupCart.groceryItem',
                'groupCart.order.vendor.vendorProfile',
            ])
            ->where('user_id', $user->id)
            ->whereHas('groupCart.order')
            ->latest()
            ->get();

        foreach ($contributions as $contribution) {
            $this->ensureClaimTokenExists($contribution);
        }

        $contributions->load([
            'groupCart.groceryItem',
            'groupCart.order.vendor.vendorProfile',
        ]);

        return view('claim-tokens.index', [
            'contributions' => $contributions,
        ]);
    }

    /**
     * Show a single digital claim token.
     */
    public function show(CartContribution $cartContribution): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $cartContribution->load([
            'user',
            'groupCart.creator',
            'groupCart.groceryItem',
            'groupCart.order.vendor.vendorProfile',
        ]);

        abort_unless(
            $cartContribution->user_id === $user->id
                || $cartContribution->groupCart->created_by_user_id === $user->id,
            403
        );

        $this->ensureClaimTokenExists($cartContribution);

        return view('claim-tokens.show', [
            'cartContribution' => $cartContribution->fresh([
                'user',
                'groupCart.creator',
                'groupCart.groceryItem',
                'groupCart.order.vendor.vendorProfile',
            ]),
        ]);
    }

    /**
     * Mark a neighbor's share as claimed.
     */
    public function claim(CartContribution $cartContribution): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $cartContribution->load('groupCart');

        abort_unless(
            $cartContribution->user_id === $user->id
                || $cartContribution->groupCart->created_by_user_id === $user->id,
            403
        );

        $this->ensureClaimTokenExists($cartContribution);

        if ($cartContribution->claimed_at) {
            return redirect()
                ->route('claim-tokens.show', $cartContribution)
                ->with('status', 'already-claimed');
        }

        $cartContribution->update([
            'claimed_at' => now(),
        ]);

        return redirect()
            ->route('claim-tokens.show', $cartContribution)
            ->with('status', 'item-claimed');
    }

    /**
     * Generate a unique claim token if missing.
     */
    private function ensureClaimTokenExists(CartContribution $cartContribution): void
    {
        if ($cartContribution->qr_claim_token) {
            return;
        }

        do {
            $token = 'SHOMO-' . Str::upper(Str::random(10));
        } while (CartContribution::where('qr_claim_token', $token)->exists());

        $cartContribution->update([
            'qr_claim_token' => $token,
        ]);
    }
}