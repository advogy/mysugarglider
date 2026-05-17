@extends('layouts.v_backend')

@section('title', 'Kelola Testimoni')

@section('content')

<x-page-header
    title="Kelola Testimoni"
    subtitle="Review dan setujui testimoni dari pengguna sebelum ditampilkan di halaman publik."
/>

{{-- PENDING --}}
<div class="be-card mb-6">
    <div class="p-5 border-b border-cream-dark flex items-center justify-between">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
            Menunggu Review
            @if ($pending->isNotEmpty())
                <span class="ml-1 text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $pending->count() }}</span>
            @endif
        </h3>
    </div>

    @if ($pending->isEmpty())
        <div class="p-8 text-center text-bark-muted text-sm">Tidak ada testimoni yang menunggu review.</div>
    @else
        <div class="divide-y divide-cream-dark">
            @foreach ($pending as $t)
            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-bark mb-0.5">{{ $t->author }}</p>
                        <p class="text-xs text-bark-muted mb-3">{{ $t->user?->email ?? '—' }} · {{ $t->created_at->diffForHumans() }}</p>
                        <p class="text-sm text-bark-light italic leading-relaxed">"{{ $t->quote }}"</p>
                    </div>
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <form action="{{ route('admin.testimonial.approve', $t) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="durasi" placeholder="mis. 1 Tahun bersama"
                                   class="text-xs border border-cream-dark rounded-2xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sage bg-white w-44">
                            <button type="submit" class="btn-create text-xs px-3 py-2 flex-shrink-0">
                                <i class="bi bi-check-lg"></i> Setujui
                            </button>
                        </form>
                        <div class="flex gap-2">
                            <button type="button"
                                    onclick="openEditModal({{ $t->id }}, {{ json_encode($t->author) }}, {{ json_encode($t->quote) }}, {{ json_encode($t->durasi) }}, {{ $t->urutan ?? 0 }})"
                                    class="btn-edit flex-1 justify-center">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form action="{{ route('admin.testimonial.reject', $t) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn-delete w-full justify-center">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- APPROVED --}}
<div class="be-card mb-6">
    <div class="p-5 border-b border-cream-dark">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-sage inline-block"></span>
            Sudah Tayang
            @if ($approved->isNotEmpty())
                <span class="ml-1 text-xs font-bold bg-sage/10 text-sage-dark px-2 py-0.5 rounded-full">{{ $approved->count() }}</span>
            @endif
        </h3>
    </div>
    @if ($approved->isEmpty())
        <div class="p-8 text-center text-bark-muted text-sm">Belum ada testimoni yang tayang.</div>
    @else
        <div class="divide-y divide-cream-dark">
            @foreach ($approved as $t)
            <div class="p-5 flex items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <p class="text-sm font-bold text-bark">{{ $t->author }}</p>
                        @if ($t->durasi)<span class="text-sm font-normal text-bark-muted">· {{ $t->durasi }}</span>@endif
                        @if ($t->urutan)
                            <span class="text-xs text-bark-muted ml-1">#{{ $t->urutan }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-bark-muted mb-2">{{ $t->user?->email ?? '(pre-seeded)' }}</p>
                    <p class="text-sm text-bark-light italic leading-relaxed">"{{ $t->quote }}"</p>
                </div>
                <div class="table-actions">
                    <button type="button"
                            onclick="openEditModal({{ $t->id }}, {{ json_encode($t->author) }}, {{ json_encode($t->quote) }}, {{ json_encode($t->durasi) }}, {{ $t->urutan ?? 0 }})"
                            class="btn-edit">
                        <i class="bi bi-pencil"></i>
                        <span class="hidden sm:inline">Edit</span>
                    </button>
                    <form action="{{ route('admin.testimonial.reject', $t) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-delete" title="Cabut dari tayang">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- REJECTED --}}
@if ($rejected->isNotEmpty())
<div class="be-card">
    <div class="p-5 border-b border-cream-dark">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
            Ditolak
            <span class="ml-1 text-xs font-bold bg-red-50 text-red-400 px-2 py-0.5 rounded-full">{{ $rejected->count() }}</span>
        </h3>
    </div>
    <div class="divide-y divide-cream-dark">
        @foreach ($rejected as $t)
        <div class="p-5 flex items-start gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-bark">{{ $t->author }}</p>
                <p class="text-xs text-bark-muted mb-2">{{ $t->user?->email ?? '—' }}</p>
                <p class="text-sm text-bark-muted italic line-clamp-2">"{{ $t->quote }}"</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="button"
                        onclick="openEditModal({{ $t->id }}, {{ json_encode($t->author) }}, {{ json_encode($t->quote) }}, {{ json_encode($t->durasi) }}, {{ $t->urutan ?? 0 }})"
                        class="btn-edit">
                    <i class="bi bi-pencil"></i>
                    <span class="hidden sm:inline">Edit</span>
                </button>
                <form action="{{ route('admin.testimonial.approve', $t) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <input type="text" name="durasi" placeholder="Durasi"
                           class="text-xs border border-cream-dark rounded-2xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sage bg-white w-36">
                    <button type="submit" class="btn-create text-xs px-3 py-2 flex-shrink-0">
                        Setujui Ulang
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Modal Edit Testimoni --}}
<div id="edit-modal" class="be-modal hidden" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b border-cream-dark">
            <h3 class="font-bold text-bark">Edit Testimoni</h3>
            <button type="button" onclick="closeEditModal()" class="text-bark-muted hover:text-bark transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Penulis</label>
                    <input type="text" name="author" id="edit-author" class="input-field" required>
                </div>
                <div>
                    <label class="form-label">Testimoni</label>
                    <textarea name="quote" id="edit-quote" rows="4" class="input-field" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Durasi</label>
                        <input type="text" name="durasi" id="edit-durasi" class="input-field" placeholder="mis. 1 Tahun bersama">
                    </div>
                    <div>
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit-urutan" class="input-field" min="0" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-cream-dark flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-create">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id, author, quote, durasi, urutan) {
    const baseUrl = '{{ url("admin/testimonials") }}';
    document.getElementById('edit-form').action = baseUrl + '/' + id;
    document.getElementById('edit-author').value = author;
    document.getElementById('edit-quote').value = quote;
    document.getElementById('edit-durasi').value = durasi ?? '';
    document.getElementById('edit-urutan').value = urutan ?? 0;
    const modal = document.getElementById('edit-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush

@endsection
