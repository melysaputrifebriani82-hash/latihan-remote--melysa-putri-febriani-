<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_a_product_with_a_pending_status_and_image(): void
    {
        Storage::fake('public');
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Kerajinan', 'slug' => 'kerajinan']);

        $response = $this->actingAs($seller)->post(route('seller.products.store'), [
            'name' => 'Gelang Manik',
            'category_id' => $category->id,
            'description' => 'Gelang buatan tangan.',
            'price' => 15000,
            'stock' => 5,
            'status' => 'approved',
            'image' => UploadedFile::fake()->image('gelang.jpg', 600, 600),
        ]);

        $product = Product::first();

        $response->assertRedirect(route('seller.products.index'));
        $this->assertSame('pending', $product->status);
        $this->assertSame($seller->id, $product->seller_id);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'status' => 'pending']);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_seller_can_edit_an_approved_product_but_it_returns_to_pending(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $otherSeller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Digital', 'slug' => 'digital']);
        $product = $this->productFor($seller, $category, ['status' => 'approved']);

        $response = $this->actingAs($seller)->put(route('seller.products.update', $product), [
            'name' => 'Poster Sekolah Baru',
            'category_id' => $category->id,
            'description' => 'Deskripsi yang diperbarui.',
            'price' => 20000,
            'stock' => 8,
            'seller_id' => $otherSeller->id,
            'status' => 'approved',
        ]);

        $product->refresh();

        $response->assertRedirect(route('seller.products.index'));
        $this->assertSame('pending', $product->status);
        $this->assertSame($seller->id, $product->seller_id);
        $this->assertSame('Poster Sekolah Baru', $product->name);
    }

    public function test_seller_cannot_manage_products_owned_by_another_seller(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $otherSeller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner']);
        $otherProduct = $this->productFor($otherSeller, $category);

        $this->actingAs($seller)->get(route('seller.products.edit', $otherProduct))->assertForbidden();
        $this->actingAs($seller)->delete(route('seller.products.destroy', $otherProduct))->assertForbidden();
        $this->assertDatabaseHas('products', ['id' => $otherProduct->id]);
    }

    public function test_seller_can_delete_their_own_product_and_its_image(): void
    {
        Storage::fake('public');
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Seni', 'slug' => 'seni']);
        $imagePath = 'products/produk-hapus.jpg';
        Storage::disk('public')->put($imagePath, 'gambar-uji');
        $product = $this->productFor($seller, $category, ['image' => $imagePath]);

        $response = $this->actingAs($seller)->delete(route('seller.products.destroy', $product));

        $response->assertRedirect(route('seller.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_buyer_cannot_access_seller_product_routes_and_rejected_reason_is_visible(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::create(['name' => 'Karya', 'slug' => 'karya']);
        $product = $this->productFor($seller, $category, [
            'status' => 'rejected',
            'rejection_reason' => 'Mohon gunakan foto produk yang lebih jelas.',
        ]);

        $this->actingAs($buyer)->get(route('seller.products.index'))->assertForbidden();
        $this->actingAs($seller)->get(route('seller.products.show', $product))
            ->assertOk()
            ->assertSee('Produk ditolak')
            ->assertSee('Mohon gunakan foto produk yang lebih jelas.');
    }

    private function productFor(User $seller, Category $category, array $attributes = []): Product
    {
        return Product::create(array_merge([
            'seller_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Poster Sekolah',
            'slug' => 'poster-sekolah-'.uniqid(),
            'description' => 'Produk untuk pengujian.',
            'price' => 15000,
            'stock' => 5,
            'status' => 'pending',
        ], $attributes));
    }
}
