@props(['status'])

@php
    $styles = [
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'inactive' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
    ];
    $labels = ['pending' => 'Menunggu Review', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'inactive' => 'Tidak Aktif'];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $styles[$status] ?? $styles['inactive'] }}">
    {{ $labels[$status] ?? $status }}
</span>
