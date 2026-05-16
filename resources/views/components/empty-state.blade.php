@props([
    'message'      => 'Belum ada data.',
    'createRoute'  => null,
    'createLabel'  => 'Tambah Baru',
    'colspan'      => 4,
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center py-16">
        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}" class="w-16 mx-auto mb-3 opacity-30" alt="">
        <p class="text-bark-muted font-semibold">{{ $message }}</p>
        @if ($createRoute)
            <a href="{{ $createRoute }}" class="btn-create mt-4 inline-flex">
                <i class="bi bi-plus-lg"></i> {{ $createLabel }}
            </a>
        @endif
    </td>
</tr>
