<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bid extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'group_cart_id',
        'vendor_user_id',
        'price_per_kg_paisa',
        'delivery_fee_paisa',
        'estimated_delivery_at',
        'note',
        'status',
    ];

    protected $casts = [
        'estimated_delivery_at' => 'datetime',
    ];

    /**
     * A bid belongs to one group cart.
     */
    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    /**
     * A bid belongs to one vendor user.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    /**
     * A bid may become one accepted order.
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Show price per kg in taka.
     */
    public function pricePerKgInTaka(): float
    {
        return $this->price_per_kg_paisa / 100;
    }

    /**
     * Show delivery fee in taka.
     */
    public function deliveryFeeInTaka(): float
    {
        return $this->delivery_fee_paisa / 100;
    }

    /**
     * Estimate total bid amount using cart weight.
     */
    public function estimatedTotalPaisa(int $weightGrams): int
    {
        $itemCost = (int) round(($weightGrams / 1000) * $this->price_per_kg_paisa);

        return $itemCost + $this->delivery_fee_paisa;
    }
}