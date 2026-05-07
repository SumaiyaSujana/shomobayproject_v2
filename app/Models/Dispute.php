<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'order_id',
        'group_cart_id',
        'user_id',
        'vendor_user_id',
        'reason',
        'refund_requested_paisa',
        'status',
        'resolved_by_user_id',
        'admin_note',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * The order connected to this dispute.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The group cart connected to this dispute.
     */
    public function groupCart(): BelongsTo
    {
        return $this->belongsTo(GroupCart::class);
    }

    /**
     * The neighbor who submitted the dispute.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The vendor involved in the dispute.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    /**
     * The admin who resolved or rejected the dispute.
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    /**
     * Show requested refund in taka.
     */
    public function refundRequestedInTaka(): float
    {
        return $this->refund_requested_paisa / 100;
    }
}