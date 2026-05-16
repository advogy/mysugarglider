@props(['title' => 'Hapus Data?', 'subtitle' => 'Anda akan menghapus:'])

<div id="delete-modal" class="be-modal hidden">
    <div class="bg-white rounded-3xl shadow-hover max-w-sm w-full p-6">
        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-trash text-red-500 text-xl"></i>
        </div>
        <h3 class="font-bold text-bark text-center text-lg mb-2">{{ $title }}</h3>
        @if ($subtitle)
            <p class="text-bark-muted text-sm text-center mb-2">{{ $subtitle }}</p>
        @endif
        <p id="delete-name" class="font-bold text-bark text-center mb-6"></p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="btn-secondary flex-1 justify-center">Batal</button>
            <form id="delete-form" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
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
    const m = document.getElementById('delete-modal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeDeleteModal() {
    const m = document.getElementById('delete-modal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush
