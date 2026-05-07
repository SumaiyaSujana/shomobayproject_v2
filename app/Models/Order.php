<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    public const STATUS_ESCROW_HELD = 'escrow_held';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'group_cart_id',
        'bid_id',
        'accepted_by_user_id',
        'vendor_user_id',
        'item_amount_paisa',
        'delivery_fee_paisa',
        'total_amount_paisa',
        'status',
    ];

    /**
     * The group cart converted into this order.
     */
    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    /**
     * The accepted vendor bid.
     */
    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    /**
     * The neighbor who accepted the bid.
     */
    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by_user_id');
    }

    /**
     * The vendor supplying this order.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    /**
     * Show total amount in taka.
     */
    public function totalAmountInTaka(): float
    {
        return $this->total_amount_paisa / 100;
    }
}