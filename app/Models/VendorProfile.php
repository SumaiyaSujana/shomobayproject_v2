<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'trade_license_file',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * A vendor profile belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}