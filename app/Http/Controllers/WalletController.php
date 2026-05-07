<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Add demo wallet balance for local testing.
     *
     * In a real deployment, this would be replaced by a payment gateway.
     */
    public function topUp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $validated = $request->validate([
            'amount_taka' => ['required', 'numeric', 'min:10', 'max:100000'],
        ]);

        $amountPaisa = (int) round($validated['amount_taka'] * 100);

        DB::transaction(function () use ($user, $amountPaisa) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'balance_paisa' => 0,
                    'escrow_paisa' => 0,
                ]
            );

            $wallet->increment('balance_paisa', $amountPaisa);

            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount_paisa' => $amountPaisa,
                'type' => 'top_up',
                'status' => 'completed',
                'description' => 'Demo wallet top-up for testing escrow flow.',
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('status', 'wallet-topped-up');
    }
}