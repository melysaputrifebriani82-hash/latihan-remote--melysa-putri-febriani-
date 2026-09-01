<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'total_amount', 'status'];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (Order $order): void {
            if (! User::whereKey($order->buyer_id)->where('role', 'buyer')->exists()) {
                throw ValidationException::withMessages([
                    'buyer_id' => 'Pesanan hanya dapat dibuat oleh pengguna dengan role buyer.',
                ]);
            }
        });
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
