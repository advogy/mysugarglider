<div id="photo-modal" class="be-modal hidden" onclick="closePhotoModal()">
    <div class="relative max-w-lg w-full mx-4" onclick="event.stopPropagation()">
        <button type="button" onclick="closePhotoModal()"
                class="absolute -top-3 -right-3 z-10 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-bark-muted hover:text-bark transition-colors">
            <i class="bi bi-x text-lg"></i>
        </button>
        <img id="photo-modal-img" src="" alt=""
             class="w-full max-h-[80vh] object-contain rounded-2xl shadow-hover">
        <p id="photo-modal-caption" class="text-center text-white text-sm font-semibold mt-3 drop-shadow"></p>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(src, caption) {
    document.getElementById('photo-modal-img').src = src;
    document.getElementById('photo-modal-caption').textContent = caption;
    const modal = document.getElementById('photo-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closePhotoModal() {
    const modal = document.getElementById('photo-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('photo-modal-img').src = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePhotoModal();
});
</script>
@endpush
