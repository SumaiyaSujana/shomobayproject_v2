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
}