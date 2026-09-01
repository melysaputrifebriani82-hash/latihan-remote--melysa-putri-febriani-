<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::updateOrCreate(
            ['email' => 'siswa@schoolmarketplace.test'],
            [
                'name' => 'Siswa Penjual',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'pembeli@schoolmarketplace.test'],
            [
                'name' => 'Pembeli Contoh',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'email_verified_at' => now(),
            ]
        );

        $categories = collect([
            ['name' => 'Kerajinan Tangan', 'slug' => 'kerajinan-tangan', 'description' => 'Produk kerajinan buatan siswa.'],
            ['name' => 'Makanan dan Minuman', 'slug' => 'makanan-minuman', 'description' => 'Kreasi kuliner siswa.'],
            ['name' => 'Desain dan Digital', 'slug' => 'desain-digital', 'description' => 'Karya desain dan produk digital siswa.'],
        ])->mapWithKeys(function (array $category): array {
            $model = Category::updateOrCreate(['slug' => $category['slug']], $category);

            return [$category['slug'] => $model];
        });

        $products = [
            ['name' => 'Gelang Manik Pelangi', 'slug' => 'gelang-manik-pelangi', 'category' => 'kerajinan-tangan', 'description' => 'Gelang manik warna-warni buatan tangan.', 'price' => 15000, 'stock' => 12],
            ['name' => 'Brownies Cokelat', 'slug' => 'brownies-cokelat', 'category' => 'makanan-minuman', 'description' => 'Brownies cokelat lembut untuk camilan sekolah.', 'price' => 20000, 'stock' => 8],
            ['name' => 'Template Poster Kegiatan', 'slug' => 'template-poster-kegiatan', 'category' => 'desain-digital', 'description' => 'Template poster kegiatan sekolah yang dapat diedit.', 'price' => 10000, 'stock' => 25],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'seller_id' => $seller->id,
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'status' => 'approved',
                ]
            );
        }
    }
}
