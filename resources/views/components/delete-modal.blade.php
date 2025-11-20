<!-- Global Delete Confirmation Modal for Admin -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold" id="deleteModalTitle">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-circle text-warning" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                <h6 class="fw-semibold mb-2">Hapus Data Ini?</h6>
                <p class="text-muted mb-0" id="deleteMessage"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-danger fw-semibold">
                    <i class="fas fa-trash"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('[data-bs-delete]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();

        const title = this.dataset.bsTitle || 'Hapus Data';
        const message = this.dataset.bsMessage || 'Apakah Anda yakin ingin menghapus data ini?';
        const action = this.dataset.bsAction;

        document.getElementById('deleteModalTitle').textContent = title;
        document.getElementById('deleteMessage').textContent = message;
        document.getElementById('deleteForm').action = action;

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    });
});
</script>
