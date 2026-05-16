@extends('layouts.v_backend')

@section('title', __('text.adoption_data'))

@section('content')

<x-page-header
    :title="__('text.adoption_data')"
    subtitle="Kelola data adopsi sugar glider Anda."
    :createRoute="route('adoption.create')"
/>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Sugar Glider</th>
                    <th class="hidden sm:table-cell">Morph</th>
                    <th class="hidden md:table-cell">Harga</th>
                    <th>Permohonan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($adoptions as $adoption)
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($adoptions->currentPage() - 1) * $adoptions->perPage() + $loop->iteration }}
                        </td>
                        <td class="font-bold text-bark">{{ $adoption->nama }}</td>
                        <td class="hidden sm:table-cell">
                            @if ($adoption->jenis)
                                <span class="badge-sage">{{ $adoption->jenis }}</span>
                            @else <span class="text-bark-muted">—</span> @endif
                        </td>
                        <td class="hidden md:table-cell font-semibold text-bark-light text-sm">
                            Rp {{ number_format($adoption->harga, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('adoption.request', $adoption->id) }}"
                               class="inline-flex items-center gap-2 text-xs font-bold text-honey-dark
                                      bg-honey-50 border border-honey/30 px-3 py-1.5 rounded-full
                                      hover:bg-honey/20 transition-colors">
                                <i class="bi bi-inbox"></i>
                                {{ $adoption->total_permohonan ?? 0 }} Permohonan
                            </a>
                        </td>
                        <td class="text-right">
                            <div class="table-actions flex-wrap">
                                <button type="button"
                                        onclick="confirmAdopted('{{ route('adoption.adopted', $adoption->id) }}', '{{ $adoption->id }}', '{{ $adoption->collection_id }}')"
                                        class="inline-flex items-center gap-1.5 text-sage text-xs font-bold
                                               px-3 py-1.5 rounded-xl border border-sage
                                               hover:bg-sage hover:text-white transition-all duration-200">
                                    <i class="bi bi-house-heart-fill"></i>
                                    <span class="hidden sm:inline">Telah Diadopsi</span>
                                </button>
                                <a href="{{ route('adoption.edit', $adoption->id) }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button"
                                        onclick="confirmDelete('{{ route('adoption.destroy', $adoption->id) }}', '{{ $adoption->nama }}')"
                                        class="btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state
                        message="Belum ada data adopsi."
                        :createRoute="route('adoption.create')"
                        createLabel="Buat Adopsi"
                        colspan="6"
                    />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($adoptions->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $adoptions->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<x-delete-modal title="Hapus Data Adopsi?" subtitle="" />

{{-- Adopted confirmation modal --}}
<div id="adopted-modal" class="be-modal hidden">
    <div class="bg-white rounded-3xl shadow-hover max-w-sm w-full p-6 text-center">
        <div class="w-12 h-12 bg-sage-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-house-heart-fill text-sage text-xl"></i>
        </div>
        <h3 class="font-bold text-bark text-lg mb-2">Tandai Telah Diadopsi?</h3>
        <p class="text-bark-muted text-sm mb-6">
            Sugar Glider ini akan ditandai sebagai telah diadopsi dan status koleksi akan diperbarui.
        </p>
        <div class="flex gap-3">
            <button onclick="closeModal('adopted-modal')" class="btn-secondary flex-1 justify-center">Batal</button>
            <form id="adopted-form" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="adopted-id" name="id" value="">
                <input type="hidden" id="adopted-collection-id" name="collection_id" value="">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-sage text-white px-4 py-3 rounded-full font-bold text-sm hover:bg-sage-dark transition-colors">
                    <i class="bi bi-check-lg"></i> Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden'); m.classList.remove('flex');
}
function confirmAdopted(url, id, collectionId) {
    document.getElementById('adopted-form').action = url;
    document.getElementById('adopted-id').value = id;
    document.getElementById('adopted-collection-id').value = collectionId;
    openModal('adopted-modal');
}
document.getElementById('adopted-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal('adopted-modal');
});
</script>
@endpush

@endsection
