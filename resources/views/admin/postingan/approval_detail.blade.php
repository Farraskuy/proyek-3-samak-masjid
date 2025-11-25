@extends('admin.layout')

@section('title', 'Detail Approval Postingan')

@section('content')
    <section class="p-3">
        <h4 class="fw-semibold">Detail Approval</h4>

        <div class="row g-3">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5 class="fw-semibold">Preview Postingan</h5>

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

                    @if($post->featured_image_url)
                        <div class="mb-3">
                            <span class="text-muted small">content image</span><br>
                            <img src="{{ asset('storage/' . $post->featured_image_url) }}" alt="thumbnail" class="img-fluid rounded">
                        </div>

                    @endif

                    <div class="content-preview">
                        <span class="text-muted small">isi content</span><br>
                        {!! $post->content !!}
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <h5 class="fw-semibold">Publikasi / Keputusan Approval</h5>

                    <form action="{{ route('postingan.admin.approval.update', ['id' => $post->id]) }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <label for="decision_select" class="form-label">Keputusan</label>
                            <select name="decision" id="decision_select" class="form-select">
                                <option value="approve">Setujui</option>
                                <option value="reject">Tolak</option>
                                <option value="revision">Minta Revisi</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="status_pub" class="form-label">Status Publikasi</label>
                            {{-- Tabindex -1 agar tidak bisa difokuskan lewat keyboard --}}
                            <select name="status" id="status_pub" class="form-select" tabindex="-1">
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
                            <a href="{{ route('postingan.admin.approval.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>

                <div class="card p-3">
                    <h6 class="fw-semibold">Informasi</h6>
                    <p class="mb-1"><strong>Penulis:</strong> {{ optional($post->creator)->name ?? $post->user_id }}</p>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $post->kategori }}</p>
                    <p class="mb-1"><strong>Dibuat:</strong> {{ $post->created_at }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        (function(){
            const decision = document.getElementById('decision_select');
            const statusPub = document.getElementById('status_pub');
            const noteContainer = document.getElementById('note_container');

            function updateState(){
                const val = decision.value;

                // Kunci Status Publikasi agar tidak bisa dipilih manual
                // Kita gunakan style pointer-events: none dan background abu-abu
                // agar terlihat disabled TAPI nilainya tetap terkirim (beda dengan atribut disabled)
                statusPub.style.pointerEvents = 'none';
                statusPub.style.backgroundColor = '#e9ecef'; 

                if(val === 'approve'){
                    // Jika Setujui -> Status wajib Publish
                    statusPub.value = 'published';
                    noteContainer.style.display = 'none';
                } else if(val === 'reject'){
                    // Jika Tolak -> Status wajib Not Publish
                    statusPub.value = 'not published';
                    noteContainer.style.display = 'none';
                } else if(val === 'revision'){
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