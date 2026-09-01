<x-seller-layout title="Dashboard Penjual">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Selamat datang, {{ auth()->user()->name }}</h3>
        <p class="mt-1 text-sm text-gray-500">Pantau karya dan performa penjualan Anda di satu tempat.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            ['Total Produk', $stats['total_products'], 'text-indigo-600'],
            ['Menunggu Review', $stats['pending_products'], 'text-amber-600'],
            ['Disetujui', $stats['approved_products'], 'text-emerald-600'],
            ['Ditolak', $stats['rejected_products'], 'text-rose-600'],
            ['Total Pesanan', $stats['total_orders'], 'text-sky-600'],
            ['Pendapatan', 'Rp'.number_format($stats['revenue'], 0, ',', '.'), 'text-violet-600'],
        ] as [$label, $value, $color])
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-bold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-xl border border-dashed border-indigo-200 bg-indigo-50 p-5">
        <h4 class="font-semibold text-indigo-950">Siap mengunggah karya baru?</h4>
        <p class="mt-1 text-sm text-indigo-800">Produk akan diperiksa admin sebelum dapat ditampilkan di marketplace.</p>
        <a href="{{ route('seller.products.create') }}" class="mt-4 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Tambah Produk</a>
    </div>
</x-seller-layout>
