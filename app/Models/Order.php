<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'delivery_coordinator_user_id',
        'item_amount_paisa',
        'delivery_fee_paisa',
        'total_amount_paisa',
        'coordinator_discount_paisa',
        'coordinator_selected_at',
        'status',
    ];

    protected $casts = [
        'coordinator_selected_at' => 'datetime',
    ];

    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by_user_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    public function deliveryCoordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_coordinator_user_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function substitutionRequest(): HasOne
    {
        return $this->hasOne(SubstitutionRequest::class);
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }

    public function totalAmountInTaka(): float
    {
        return $this->total_amount_paisa / 100;
    }

    public function coordinatorDiscountInTaka(): float
    {
        return $this->coordinator_discount_paisa / 100;
    }
}