<x-seller-layout title="Tambah Produk">
    <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
        <h3 class="text-lg font-semibold text-gray-900">Unggah karya baru</h3>
        <p class="mt-1 text-sm text-gray-500">Produk baru akan berstatus Menunggu Review sebelum tampil di marketplace.</p>
        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="mt-7">@include('seller.products.form', ['submitLabel' => 'Kirim untuk Review'])</form>
    </div>
</x-seller-layout>
