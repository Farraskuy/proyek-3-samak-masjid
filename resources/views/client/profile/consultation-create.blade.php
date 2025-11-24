@extends('client.profile.layout')

@section('profile-content')
    <div class="card settings-card border-0 bg-white">
        <div class="card-body p-5">
            <div class="d-flex align-items-center mb-5">
                <i class="fa-regular fa-pen-to-square fs-4 me-3 text-dark"></i>
                <h5 class="fw-bold mb-0 text-dark">Konsultasi Baru</h5>
            </div>

            <form action="{{ route('client.consultations.store') }}" method="POST" id="consultationForm">
                @csrf

                <div class="mb-4">
                    <label for="question_subject" class="form-label">Topik Pertanyaan</label>
                    <input type="text" class="form-control" id="question_subject" name="question_subject"
                        placeholder="Contoh: Hukum Shalat Dhuha" required>
                </div>

                <div class="mb-4">
                    <label for="question_text" class="form-label">Isi Pertanyaan</label>
                    <textarea class="form-control" id="question_text" name="question_text" rows="6"
                        placeholder="Jelaskan pertanyaan Anda secara detail..." required></textarea>
                    <div class="form-text">Minimal 10 karakter.</div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="is_anonymous"
                            name="is_anonymous">
                        <label class="form-check-label" for="is_anonymous">
                            Ajukan sebagai anonim (Nama Anda tidak akan ditampilkan ke publik)
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-5">
                    <a href="{{ route('client.consultations.history') }}"
                        class="btn btn-outline-secondary px-4 py-2 fw-medium">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-medium">Kirim Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('consultationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const subject = document.getElementById('question_subject').value;
            const text = document.getElementById('question_text').value;

            if (text.length < 10) {
                alert('Pertanyaan terlalu pendek. Minimal 10 karakter.');
                return;
            }

            // Submit via fetch to handle JSON response if needed, or just let it submit normally if controller redirects
            // Since the controller returns JSON in the original implementation, we should handle it or update controller.
            // Let's assume we update controller to handle standard form submit or use JS here.
            // For better UX with the JSON response:

            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Mengirim...';

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to detail
                        window.location.href = data.redirect;
                    } else {
                        alert(data.error || 'Terjadi kesalahan.');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada server.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    </script>
@endsection
