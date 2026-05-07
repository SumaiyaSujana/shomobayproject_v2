<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance_paisa',
        'escrow_paisa',
    ];

    /**
     * A wallet belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A wallet has many transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Show balance in taka.
     */
    public function balanceInTaka(): float
    {
        return $this->balance_paisa / 100;
    }

    /**
     * Show escrow amount in taka.
     */
    public function escrowInTaka(): float
    {
        return $this->escrow_paisa / 100;
    }
}