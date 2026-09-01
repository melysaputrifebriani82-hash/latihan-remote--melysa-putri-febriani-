<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'product_id', 'order_id', 'rating', 'comment'];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Review $review): void {
            if (! User::whereKey($review->buyer_id)->where('role', 'buyer')->exists()) {
                throw ValidationException::withMessages([
                    'buyer_id' => 'Ulasan hanya dapat dibuat oleh pengguna dengan role buyer.',
                ]);
            }

            if ($review->rating < 1 || $review->rating > 5) {
                throw ValidationException::withMessages([
                    'rating' => 'Rating harus bernilai antara 1 sampai 5.',
                ]);
            }
        });
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
