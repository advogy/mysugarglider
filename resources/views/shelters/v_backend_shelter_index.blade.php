@extends('layouts.v_backend')

@section('title', __('text.shelter_data'))

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-bark">{{ __('text.shelter_data') }}</h2>
        <p class="text-bark-muted text-sm mt-0.5">Kelola data kandang Anda.</p>
    </div>
    <a href="{{ route('shelter.create') }}" class="btn-create self-start">
        <i class="bi bi-plus-lg"></i> {{ __('text.add_new') }}
    </a>
</div>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="w-16">Foto</th>
                    <th>Nama</th>
                    <th class="hidden sm:table-cell">Kode</th>
                    <th class="hidden md:table-cell">Alamat</th>
                    <th class="hidden lg:table-cell">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shelters as $shelter)
                    <tr>
                        <td>
                            <div class="w-11 h-11 rounded-xl overflow-hidden bg-sage-100">
                                @if ($shelter->gambar)
                                    <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="bi bi-house-heart text-sage/40"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="font-bold text-bark">{{ $shelter->nama }}</td>
                        <td class="hidden sm:table-cell font-mono text-xs text-bark-muted">{{ $shelter->kode }}</td>
                        <td class="hidden md:table-cell text-bark-light text-sm">{{ $shelter->alamat ?? '—' }}</td>
                        <td class="hidden lg:table-cell">
                            @if ($shelter->status == '1')
                                <span class="badge-sage">{{ __('text.open') }}</span>
                            @else
                                <span class="badge-done">{{ __('text.close') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('shelter.edit', $shelter->id) }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button type="button" onclick="confirmDelete('{{ route('shelter.destroy', $shelter->id) }}', '{{ $shelter->nama }}')" class="btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}" class="w-16 mx-auto mb-3 opacity-30" alt="">
                            <p class="text-bark-muted font-semibold">Belum ada kandang.</p>
                            <a href="{{ route('shelter.create') }}" class="btn-create mt-4 inline-flex">
                                <i class="bi bi-plus-lg"></i> Tambah Kandang
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($shelters->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $shelters->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.4)">
    <div class="bg-white rounded-3xl shadow-hover max-w-sm w-full p-6">
        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-trash text-red-500 text-xl"></i>
        </div>
        <h3 class="font-bold text-bark text-center text-lg mb-2">Hapus Data?</h3>
        <p class="text-bark-muted text-sm text-center mb-2">Anda akan menghapus:</p>
        <p id="delete-name" class="font-bold text-bark text-center mb-6"></p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="btn-secondary flex-1 justify-center">Batal</button>
            <form id="delete-form" method="POST" class="flex-1">
                @csrf <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-red-500 text-white px-4 py-3 rounded-full font-bold text-sm hover:bg-red-600 transition-colors">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(url, name) {
    document.getElementById('delete-name').textContent = name;
    document.getElementById('delete-form').action = url;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-modal').classList.add('flex');
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
}
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush

@endsection
