<x-seller-layout title="Produk Saya">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Karya dan produk Anda</h3>
            <p class="mt-1 text-sm text-gray-500">Kelola produk yang akan ditampilkan di School Marketplace.</p>
        </div>
        <a href="{{ route('seller.products.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">+ Tambah Produk</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        @if ($products->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="font-semibold text-gray-800">Belum ada produk</p>
                <p class="mt-1 text-sm text-gray-500">Unggah karya pertama Anda untuk dikirim ke review admin.</p>
                <a href="{{ route('seller.products.create') }}" class="mt-5 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Tambah Produk</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                        <tr><th class="px-5 py-3">Produk</th><th class="px-5 py-3">Kategori</th><th class="px-5 py-3">Harga</th><th class="px-5 py-3">Stok</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Dibuat</th><th class="px-5 py-3 text-right">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-5 py-4"><div class="flex min-w-48 items-center gap-3">@if ($product->image)<img src="{{ Storage::disk('public')->url($product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover">@else<div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-xs font-bold text-indigo-600">SM</div>@endif<div><a href="{{ route('seller.products.show', $product) }}" class="font-semibold text-gray-900 hover:text-indigo-600">{{ $product->name }}</a>@if ($product->status === 'rejected')<p class="mt-1 text-xs text-rose-600">Produk ditolak — lihat alasan</p>@endif</div></div></td>
                                <td class="px-5 py-4">{{ $product->category->name }}</td>
                                <td class="px-5 py-4">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">{{ $product->stock }}</td>
                                <td class="px-5 py-4"><x-product-status-badge :status="$product->status" /></td>
                                <td class="px-5 py-4 whitespace-nowrap text-gray-500">{{ $product->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4"><div class="flex justify-end gap-3"><a href="{{ route('seller.products.show', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-800">Lihat</a><a href="{{ route('seller.products.edit', $product) }}" class="font-medium text-gray-700 hover:text-gray-900">Edit</a><form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.');">@csrf @method('DELETE')<button type="submit" class="font-medium text-rose-600 hover:text-rose-800">Hapus</button></form></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-4">{{ $products->links() }}</div>
        @endif
    </div>
</x-seller-layout>
