<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubstitutionRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'order_id',
        'group_cart_id',
        'vendor_user_id',
        'original_item_name',
        'substitute_item_name',
        'reason',
        'status',
    ];

    /**
     * The order connected to this substitution request.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The group cart connected to this substitution request.
     */
    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    /**
     * The vendor who proposed the substitution.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    /**
     * Neighbor votes for this substitution.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(SubstitutionVote::class);
    }

    /**
     * Count approval votes.
     */
    public function approveVotesCount(): int
    {
        return $this->votes
            ->where('vote', SubstitutionVote::VOTE_APPROVE)
            ->count();
    }

    /**
     * Count rejection votes.
     */
    public function rejectVotesCount(): int
    {
        return $this->votes
            ->where('vote', SubstitutionVote::VOTE_REJECT)
            ->count();
    }
}