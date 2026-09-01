<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MarketplaceModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_models_have_the_expected_relationships(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $category = Category::create(['name' => 'Kerajinan', 'slug' => 'kerajinan']);
        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Gelang Manik',
            'slug' => 'gelang-manik',
            'description' => 'Produk uji.',
            'price' => 15000,
            'stock' => 4,
        ]);
        $order = Order::create(['buyer_id' => $buyer->id, 'total_amount' => 15000]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 15000,
            'quantity' => 1,
            'subtotal' => 15000,
        ]);
        $review = Review::create([
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Bagus.',
        ]);

        $this->assertTrue($seller->products->contains($product));
        $this->assertTrue($buyer->orders->contains($order));
        $this->assertTrue($category->products->contains($product));
        $this->assertTrue($product->orderItems->contains($orderItem));
        $this->assertTrue($order->reviews->contains($review));
        $this->assertSame($seller->id, $product->seller->id);
        $this->assertSame($buyer->id, $order->buyer->id);
        $this->assertSame($product->id, $orderItem->product->id);
    }

    public function test_products_require_a_seller_and_orders_require_a_buyer(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Digital', 'slug' => 'digital']);

        try {
            Product::create([
                'seller_id' => $buyer->id,
                'category_id' => $category->id,
                'name' => 'Produk Tidak Valid',
                'slug' => 'produk-tidak-valid',
                'description' => 'Produk uji.',
                'price' => 10000,
                'stock' => 1,
            ]);
            $this->fail('Produk dengan buyer sebagai seller seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('seller_id', $exception->errors());
        }

        try {
            Order::create(['buyer_id' => $seller->id, 'total_amount' => 10000]);
            $this->fail('Pesanan dengan seller sebagai buyer seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('buyer_id', $exception->errors());
        }

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Produk Valid',
            'slug' => 'produk-valid',
            'description' => 'Produk uji.',
            'price' => 10000,
            'stock' => 1,
        ]);
        $order = Order::create(['buyer_id' => $buyer->id, 'total_amount' => 10000]);

        try {
            Review::create([
                'buyer_id' => $seller->id,
                'product_id' => $product->id,
                'order_id' => $order->id,
                'rating' => 5,
            ]);
            $this->fail('Ulasan dengan seller sebagai buyer seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('buyer_id', $exception->errors());
        }
    }
}
