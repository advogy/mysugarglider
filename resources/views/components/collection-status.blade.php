@props(['status'])

@php
$s = (int) $status;
@endphp

@if ($s === 1)
    <span class="inline-flex items-center bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-full">Privat</span>
@elseif ($s === 2)
    <span class="badge-sage">Publik</span>
@elseif ($s === 3)
    <span class="badge-honey">Adopsi</span>
@elseif ($s === 4)
    <span class="inline-flex items-center bg-gray-800 text-gray-100 text-xs font-bold px-2 py-1 rounded-full">Mati</span>
@elseif ($s === 5)
    <span class="badge-done">Selesai</span>
@else
    <span class="text-bark-muted">—</span>
@endif
