<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubstitutionVote extends Model
{
    use HasFactory;

    public const VOTE_APPROVE = 'approve';
    public const VOTE_REJECT = 'reject';

    protected $fillable = [
        'substitution_request_id',
        'user_id',
        'vote',
    ];

    /**
     * A vote belongs to one substitution request.
     */
    public function substitutionRequest(): BelongsTo
    {
        return $this->belongsTo(SubstitutionRequest::class);
    }

    /**
     * The neighbor who voted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}