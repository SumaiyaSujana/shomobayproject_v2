<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SubstitutionRequest;
use App\Models\SubstitutionVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstitutionVoteController extends Controller
{
    /**
     * Neighbor contributor votes on a substitution request.
     */
    public function vote(Request $request, SubstitutionRequest $substitutionRequest): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $substitutionRequest->load([
            'order.groupCart.contributions',
            'groupCart',
        ]);

        if ($substitutionRequest->status !== SubstitutionRequest::STATUS_PENDING) {
            return back()->withErrors([
                'substitution' => 'Voting is closed for this substitution request.',
            ]);
        }

        if ($substitutionRequest->order->status !== Order::STATUS_ESCROW_HELD) {
            return back()->withErrors([
                'substitution' => 'Voting is only allowed before delivery or refund.',
            ]);
        }

        $hasContribution = $substitutionRequest->order->groupCart->contributions
            ->contains('user_id', $user->id);

        if (!$hasContribution) {
            return back()->withErrors([
                'substitution' => 'Only neighbors who contributed to this cart can vote.',
            ]);
        }

        $validated = $request->validate([
            'vote' => ['required', 'in:approve,reject'],
        ]);

        SubstitutionVote::updateOrCreate(
            [
                'substitution_request_id' => $substitutionRequest->id,
                'user_id' => $user->id,
            ],
            [
                'vote' => $validated['vote'],
            ]
        );

        $this->updateSubstitutionStatus($substitutionRequest);

        return redirect()
            ->route('group-carts.show', $substitutionRequest->groupCart)
            ->with('status', 'substitution-vote-saved');
    }

    /**
     * Approve or reject once a majority is reached.
     */
    private function updateSubstitutionStatus(SubstitutionRequest $substitutionRequest): void
    {
        $substitutionRequest->load([
            'votes',
            'order.groupCart.contributions',
        ]);

        $totalContributors = $substitutionRequest->order
            ->groupCart
            ->contributions
            ->pluck('user_id')
            ->unique()
            ->count();

        if ($totalContributors <= 0) {
            return;
        }

        $approveVotes = $substitutionRequest->votes
            ->where('vote', SubstitutionVote::VOTE_APPROVE)
            ->count();

        $rejectVotes = $substitutionRequest->votes
            ->where('vote', SubstitutionVote::VOTE_REJECT)
            ->count();

        if ($approveVotes > ($totalContributors / 2)) {
            $substitutionRequest->update([
                'status' => SubstitutionRequest::STATUS_APPROVED,
            ]);

            return;
        }

        if ($rejectVotes > ($totalContributors / 2)) {
            $substitutionRequest->update([
                'status' => SubstitutionRequest::STATUS_REJECTED,
            ]);
        }
    }
}