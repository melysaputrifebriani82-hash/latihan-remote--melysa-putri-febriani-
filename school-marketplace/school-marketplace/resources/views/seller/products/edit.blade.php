<x-seller-layout title="Edit Produk">
    <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
        <h3 class="text-lg font-semibold text-gray-900">Perbarui produk</h3>
        @if ($product->status === 'rejected')<div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><p class="font-semibold">Produk ditolak</p><p class="mt-1">{{ $product->rejection_reason ?: 'Admin belum memberikan alasan penolakan.' }}</p></div>@elseif ($product->status === 'approved')<p class="mt-2 text-sm text-amber-700">Perubahan data produk akan mengirimnya kembali untuk review admin.</p>@endif
        <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" class="mt-7">@method('PUT') @include('seller.products.form', ['submitLabel' => 'Simpan Perubahan'])</form>
    </div>
</x-seller-layout>
