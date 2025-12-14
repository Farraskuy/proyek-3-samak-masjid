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
            {{-- Kolom Kiri: Preview (TETAP SAMA) --}}
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
                                        fill="currentColor" class="fas fa-image mb-3 opacity-50" viewBox="0 0 16 16">
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

            {{-- Kolom Kanan: Form Approval --}}
            <div class="col-md-4">
                @php 
                    $currentStatus = strtolower($post->status); 
                    // Cek apakah form harus di-hide (jika draft atau revisi)
                    $isLocked = in_array($currentStatus, ['draft', 'revisi']);
                @endphp

                @if($isLocked)
                    {{-- TAMPILAN JIKA STATUS DRAFT / REVISI (Form Hide) --}}
                    <div class="card bg-warning-subtle border-warning rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold text-warning-emphasis">
                            <i class="fas fa-lock me-2"></i>Aksi Terkunci
                        </h5>
                        <p class="mb-0 text-muted">
                            Postingan ini sedang dalam status <strong>{{ ucfirst($currentStatus) }}</strong>. 
                            Admin tidak dapat melakukan approval atau perubahan status sampai Penulis mengajukan postingan ini kembali.
                        </p>
                    </div>
                @else
                    {{-- TAMPILAN FORM (Hanya untuk Pending, Published, Arsip) --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold">Publikasi / Keputusan Approval</h5>

                        <form id="approvalForm" action="{{ route('admin.postingan.approval.update', ['id' => $post->id]) }}"
                            method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="decision_select" class="form-label">Keputusan</label>
                                
                                <select name="decision" id="decision_select" class="form-select form-control form-control-lg">
                                    <option value="" selected hidden>Pilih Aksi...</option>

                                    {{-- ATURAN BISNIS DROP DOWN --}}
                                    
                                    {{-- 1. Jika PENDING -> Bisa Publish atau Revisi --}}
                                    @if($currentStatus === 'pending')
                                        <option value="published">Setujui & Terbitkan</option>
                                        <option value="revisi">Kembalikan untuk Revisi</option>
                                    @endif

                                    {{-- 2. Jika PUBLISHED -> Hanya bisa Arsip --}}
                                    @if($currentStatus === 'published')
                                        <option value="arsip">Arsipkan Postingan</option>
                                    @endif

                                    {{-- 3. Jika ARSIP -> Hanya bisa Draft --}}
                                    @if($currentStatus === 'arsip')
                                        <option value="published">Kembalikan ke Published (Tayang)</option>
                                    @endif
                                </select>
                            </div>

                            {{-- Container Note: Muncul jika pilih 'Revisi' --}}
                            <div class="mb-3" id="note_container" style="display:none;">
                                <label for="note_field" class="form-label text-danger">Catatan / Instruksi Revisi *</label>
                                <textarea id="note_field" name="note" class="form-control" rows="4"
                                    placeholder="Tuliskan detail revisi yang diperlukan...">{{ $post->approval_note }}</textarea>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success w-100">Simpan Keputusan</button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="card bg-white border-0 rounded-3 p-4">
                    <h6 class="fw-semibold">Informasi</h6>
                    <p class="mb-1"><strong>Status Saat Ini:</strong> 
                        <span class="badge bg-secondary">{{ ucfirst($post->status) }}</span>
                    </p>
                    <p class="mb-1"><strong>Penulis:</strong> {{ optional($post->creator)->full_name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $post->kategori }}</p>
                    <p class="mb-1"><strong>Dibuat:</strong> {{ $post->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function() {
                const decisionSelect = document.getElementById('decision_select');
                const noteContainer = document.getElementById('note_container');
                const noteField = document.getElementById('note_field');

                // Cek null safety (karena elemen ini tidak ada jika status draft/revisi)
                if(decisionSelect) {
                    function updateState() {
                        const val = decisionSelect.value;
                        if (val === 'revisi') {
                            noteContainer.style.display = 'block';
                            noteField.setAttribute('required', 'required');
                        } else {
                            noteContainer.style.display = 'none';
                            noteField.removeAttribute('required');
                        }
                    }

                    decisionSelect.addEventListener('change', updateState);
                    updateState(); 

                    const form = document.getElementById('approvalForm');
                    const submitBtn = form.querySelector("button[type='submit']");
                    form.addEventListener('submit', function() {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                    });
                }
            })();
        </script>
    @endpush

@endsection