@props(['title' => 'Seller Center'])

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600">School Marketplace</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>
            </div>
            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">Penjual</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:flex-row lg:px-8">
            <aside class="w-full rounded-xl bg-white p-3 shadow-sm lg:w-56 lg:self-start">
                <nav class="space-y-1 text-sm font-medium">
                    <a href="{{ route('seller.dashboard') }}" @class(['block rounded-lg px-3 py-2', 'bg-indigo-50 text-indigo-700' => request()->routeIs('seller.dashboard'), 'text-gray-600 hover:bg-gray-50' => !request()->routeIs('seller.dashboard')])>Dashboard</a>
                    <a href="{{ route('seller.products.index') }}" @class(['block rounded-lg px-3 py-2', 'bg-indigo-50 text-indigo-700' => request()->routeIs('seller.products.*'), 'text-gray-600 hover:bg-gray-50' => !request()->routeIs('seller.products.*')])>Produk Saya</a>
                    <a href="{{ route('seller.products.create') }}" class="block rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-50">Tambah Produk</a>
                    <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Pesanan <span class="text-xs">(segera hadir)</span></span>
                    <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-50">Profil</a>
                </nav>
            </aside>

            <section class="min-w-0 flex-1">
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                @endif

                {{ $slot }}
            </section>
        </div>
    </div>
</x-app-layout>
