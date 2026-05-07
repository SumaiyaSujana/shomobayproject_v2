<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'group_cart_id',
        'order_id',
        'amount_paisa',
        'type',
        'status',
        'description',
    ];

    /**
     * A transaction belongs to one wallet.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Show transaction amount in taka.
     */
    public function amountInTaka(): float
    {
        return $this->amount_paisa / 100;
    }
}