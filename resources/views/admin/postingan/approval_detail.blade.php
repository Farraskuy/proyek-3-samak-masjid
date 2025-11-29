@extends('admin.layout')

@section('title', 'Detail Approval Postingan')

@section('content')
    <section class="p-3 container">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ url('admin/postingan') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Detail Approval Postingan</h4>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold">Preview Postingan</h5>

                    <div class="mb-3 w-100">
                        <span class="text-muted small">content image</span><br>
                        @if ($post->featured_image_url)
                            <img src="{{ asset('storage/' . $post->featured_image_url) }}" alt="thumbnail"
                                class="img-fluid rounded w-100"
                                style="object-fit: cover; min-height: 300px; background-color: lightgray;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded w-100 text-muted"
                                style="min-height: 300px; background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                        fill="currentColor" class="bi bi-image mb-3 opacity-50" viewBox="0 0 16 16">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                        <path
                                            d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                    </svg>
                                    <p class="mb-0 fw-medium">No Featured Image</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <div class="mb-1">
                            <span class="text-muted small">Judul:</span><br>
                            <span class="fw-bold fs-5">{{ $post->title }}</span>
                        </div>

                        <div class="mt-3">
                            <span class="text-muted small">Keterangan Postingan:</span><br>
                            <span class="fw-semibold">{{ $post->keterangan }}</span>
                        </div>
                    </div>

                    <div class="content-preview">
                        <span class="text-muted small">isi content</span><br>
                        {!! $post->content !!}
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold">Publikasi / Keputusan Approval</h5>

                    <form action="{{ route('admin.postingan.approval.update', ['id' => $post->id]) }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <label for="decision_select" class="form-label">Keputusan</label>
                            <select name="decision" id="decision_select" class="form-select form-control form-control-lg">
                                <option value="approve">Setujui</option>
                                <option value="reject">Tolak</option>
                                <option value="revision">Minta Revisi</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="status_pub" class="form-label">Status Publikasi</label>
                            {{-- Tabindex -1 agar tidak bisa difokuskan lewat keyboard --}}
                            <select name="status" id="status_pub" class="form-select form-control form-control-lg" tabindex="-1">
                                <option value="published">Publish</option>
                                <option value="not published">Not Publish</option>
                                <option value="revisi">Revisi</option>
                            </select>
                        </div>

                        <div class="mb-2" id="note_container" style="display:none;">
                            <label for="note_field" class="form-label">Catatan / Instruksi Revisi</label>
                            <textarea id="note_field" name="note" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success">Simpan Keputusan</button>
                            <a href="{{ route('admin.postingan.approval.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>

                <div class="card bg-white border-0 rounded-3 p-4">
                    <h6 class="fw-semibold">Informasi</h6>
                    <p class="mb-1"><strong>Penulis:</strong> {{ optional($post->creator)->full_name ?? 'N/A'}}
                    </p>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $post->kategori }}</p>
                    <p class="mb-1"><strong>Dibuat:</strong> {{ $post->created_at }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function() {
                const decision = document.getElementById('decision_select');
                const statusPub = document.getElementById('status_pub');
                const noteContainer = document.getElementById('note_container');

                function updateState() {
                    const val = decision.value;

                    // Kunci Status Publikasi agar tidak bisa dipilih manual
                    // Kita gunakan style pointer-events: none dan background abu-abu
                    // agar terlihat disabled TAPI nilainya tetap terkirim (beda dengan atribut disabled)
                    statusPub.style.pointerEvents = 'none';
                    statusPub.style.backgroundColor = '#e9ecef';

                    if (val === 'approve') {
                        // Jika Setujui -> Status wajib Publish
                        statusPub.value = 'published';
                        noteContainer.style.display = 'none';
                    } else if (val === 'reject') {
                        // Jika Tolak -> Status wajib Not Publish
                        statusPub.value = 'not published';
                        noteContainer.style.display = 'none';
                    } else if (val === 'revision') {
                        // Jika Revisi -> Status wajib Revisi & Munculkan Note
                        statusPub.value = 'revisi';
                        noteContainer.style.display = 'block';
                    }
                }

                decision.addEventListener('change', updateState);

                // Jalankan fungsi saat halaman pertama dimuat untuk set default awal
                updateState();
            })();
        </script>
    @endpush

@endsection
