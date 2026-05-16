@php
    $ancestor  = $id ? ($ancestorMap[$id] ?? null) : null;
    $isOwn     = $ancestor && $ancestor->user_id === Auth::id();
    $isVisible = $ancestor && in_array((int) $ancestor->cl_status, [
        \App\Enums\CollectionStatus::PUBLIK->value,
        \App\Enums\CollectionStatus::ADOPSI->value,
    ]);
    $genderSymbol = $gender === 'm' ? '♂' : '♀';
    $genderClass  = $gender === 'm' ? 'text-blue-400' : 'text-rose-400';
@endphp

<td rowspan="{{ $rows }}" class="{{ $bg }} border border-cream-dark px-3 py-2 align-middle">
    @if ($id && $nama)
        @if ($isOwn)
            <a href="{{ route('sugarglider.backend.show', $id) }}" class="hover:underline">
                <span class="{{ $genderClass }}">{{ $genderSymbol }}</span>
                {{ $nama }}<br>
                <span class="text-bark-muted text-xs">{{ $jenis ?? '—' }}</span>
            </a>
        @elseif ($isVisible)
            <a href="{{ route('sugarglider.show', $id) }}" class="hover:underline" target="_blank" rel="noopener">
                <span class="{{ $genderClass }}">{{ $genderSymbol }}</span>
                {{ $nama }}<br>
                <span class="text-bark-muted text-xs">{{ $jenis ?? '—' }}</span>
            </a>
        @else
            <span class="{{ $genderClass }}">{{ $genderSymbol }}</span>
            {{ $nama }}<br>
            <span class="text-bark-muted text-xs">{{ $jenis ?? '—' }}</span><br>
            <span class="inline-block text-xs bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded mt-0.5">privat</span>
        @endif
    @else
        <span class="text-bark-muted text-xs italic">{{ $genderSymbol }} Tidak diketahui</span>
    @endif
</td>
