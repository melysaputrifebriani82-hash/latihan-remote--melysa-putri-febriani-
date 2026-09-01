<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (! User::whereKey($product->seller_id)->where('role', 'seller')->exists()) {
                throw ValidationException::withMessages([
                    'seller_id' => 'Produk hanya dapat dimiliki oleh pengguna dengan role seller.',
                ]);
            }

            $user = auth()->user();

            if ($user?->isSeller() && $product->exists) {
                if ($product->isDirty('seller_id') || $product->seller_id !== $user->id) {
                    throw ValidationException::withMessages([
                        'seller_id' => 'Penjual tidak dapat mengubah pemilik produk.',
                    ]);
                }

                if ($product->isDirty('status') && $product->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'status' => 'Penjual tidak dapat mengubah status produk.',
                    ]);
                }
            }
        });
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
