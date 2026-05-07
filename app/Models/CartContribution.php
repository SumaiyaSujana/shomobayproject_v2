<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_cart_id',
        'user_id',
        'quantity_grams',
        'estimated_amount_paisa',
        'qr_claim_token',
        'claimed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    /**
     * A contribution belongs to one group cart.
     */
    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    /**
     * A contribution belongs to one neighbor user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Show contribution quantity in kg.
     */
    public function quantityInKg(): float
    {
        return $this->quantity_grams / 1000;
    }

    /**
     * Show estimated amount in taka.
     */
    public function estimatedAmountInTaka(): float
    {
        return $this->estimated_amount_paisa / 100;
    }

    /**
     * Check whether this share has already been claimed.
     */
    public function isClaimed(): bool
    {
        return $this->claimed_at !== null;
    }
}