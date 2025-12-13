@extends('admin.layout')

@section('title', 'Edit Kegiatan')

@push('styles') <style> .file-uploader { padding: 2rem; border-radius: 1rem; border: 2px dashed #dee2e6; background: #fafafa; text-align: center; cursor: pointer; color: #666; transition: .2s ease-in-out; display: block !important; }

    .file-uploader.on-drag {
        background: #f3f3f3;
        border-color: #CE9138 !important;
    }

    #image-preview-container {
        display: none;
        position: relative;
    }

    #image-preview {
        width: 100%;
        border-radius: 1rem;
        border: 1px solid #ddd;
        object-fit: contain;
        height: 200px;
        background: #eee;
    }

    #remove-image-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, .55);
        color: white;
        font-size: 18px;
        cursor: pointer;
    }
</style>

@endpush

@section('content') <section class="p-3 container">

    <form action="{{ route('admin.kegiatan.update', $event->event_id) }}" method="POST" enctype="multipart/form-data"
        id="formKegiatan">
        @csrf
        @method('PUT')

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Edit Kegiatan</h4>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Detail Kegiatan</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="event_name"
                            class="form-control input-lg @error('event_name') is-invalid @enderror"
                            value="{{ old('event_name', $event->event_name) }}" required>
                        @error('event_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tema / Deskripsi</label>
                        <textarea name="theme" class="form-control" rows="3">{{ old('theme', $event->theme) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location"
                            class="form-control input-lg @error('location') is-invalid @enderror"
                            value="{{ old('location', $event->location) }}" required>
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waktu Mulai <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}"
                                required>
                            @error('start_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waktu Selesai <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_time"
                                class="form-control @error('end_time') is-invalid @enderror"
                                value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}"
                                required>
                            @error('end_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-semibold mb-3">Tamu Undangan</h5>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="checkTamu"
                            name="is_have_tamu_undangan" {{ $event->tamuUndangan->count() > 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="checkTamu">Ada pembicara / tamu undangan</label>
                    </div>

                    <div id="daftarTamuContainer"
                        style="display:{{ $event->tamuUndangan->count() > 0 ? 'block' : 'none' }};">
                        <div id="inputTamuWrapper">
                            @forelse($event->tamuUndangan as $index => $tamu)
                                @if ($loop->first)
                                    <input type="text" name="daftar_tamu[]" class="form-control mb-2"
                                        placeholder="Nama pembicara" value="{{ $tamu->nama_tamu }}">
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" name="daftar_tamu[]" class="form-control"
                                            placeholder="Nama pembicara {{ $loop->iteration }}"
                                            value="{{ $tamu->nama_tamu }}">
                                        <button type="button" class="btn btn-light border text-danger"
                                            onclick="this.parentElement.remove()">×</button>
                                    </div>
                                @endif
                            @empty
                                <input type="text" name="daftar_tamu[]" class="form-control mb-2"
                                    placeholder="Nama pembicara">
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-light border mt-2" id="btnTambahTamu">
                            <i class="fas fa-plus me-1"></i> Tambah Tamu
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Poster & Pengaturan</h5>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Poster Kegiatan</label>

                        @if ($event->poster)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="hapus_poster" id="hapusPoster">
                                <label class="form-check-label text-danger" for="hapusPoster">
                                    Hapus poster saat ini
                                </label>
                            </div>
                        @endif

                        <label for="file-input" id="file-uploader" class="file-uploader"
                            style="{{ $event->poster ? 'display:none !important' : '' }}">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <div class="fw-semibold">Upload Poster</div>
                            <div class="small text-muted">Drag & drop atau klik</div>
                        </label>

                        <input type="file" name="poster" id="file-input" accept="image/*" class="d-none">

                        <div id="image-preview-container" class="mt-3"
                            style="{{ $event->poster ? 'display:block' : '' }}">
                            <img id="image-preview"
                                src="{{ $event->poster ? asset('storage/' . $event->poster) : '#' }}" alt="Preview">
                            <button type="button" id="remove-image-btn" title="Ganti Gambar">&times;</button>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">Integrasi Formulir</label>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkRegForm"
                                name="has_registration_form" value="1"
                                {{ $event->has_registration_form ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkRegForm">Formulir Pendaftaran</label>
                        </div>
                        <div id="regFormContainer" class="mb-3"
                            style="display:{{ $event->has_registration_form ? 'block' : 'none' }};">
                            <select name="registration_form_id" class="form-select form-select-sm">
                                <option value="">-- Pilih Formulir --</option>
                                @foreach ($forms as $form)
                                    <option value="{{ $form->id }}"
                                        {{ $event->registration_form_id == $form->id ? 'selected' : '' }}>
                                        {{ $form->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkCloseForm"
                                name="has_closing_form" value="1"
                                {{ $event->has_closing_form ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkCloseForm">Formulir Penutupan (Kuisioner)</label>
                        </div>
                        <div id="closeFormContainer"
                            style="display:{{ $event->has_closing_form ? 'block' : 'none' }};">
                            <select name="closing_form_id" class="form-select form-select-sm">
                                <option value="">-- Pilih Formulir --</option>
                                @foreach ($forms as $form)
                                    <option value="{{ $form->id }}"
                                        {{ $event->closing_form_id == $form->id ? 'selected' : '' }}>
                                        {{ $form->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Penanggung Jawab</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkPJ" name="has_pj"
                                value="1" {{ $event->has_pj ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkPJ">Aktifkan PJ Acara</label>
                        </div>

                        <div id="pjInfoContainer" class="mt-3"
                            style="display:{{ $event->has_pj ? 'block' : 'none' }};">
                            @if ($event->has_pj && $event->pjUser)
                                <div class="alert alert-info small mb-0">
                                    <p class="fw-semibold mb-2"><i class="fas fa-user-shield me-1"></i>Kredensial PJ</p>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Email:</small>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{ $event->pjUser->email }}" readonly id="pjEmail">
                                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('pjEmail')" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Password:</small>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{ $event->pjUser->pj_password ?? 'Hubungi Admin' }}" readonly id="pjPassword">
                                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('pjPassword')" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <small class="text-muted">Nama:</small>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{ $event->pjUser->name }}" readonly id="pjName">
                                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('pjName')" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-light border small text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Akun PJ akan dibuat otomatis. Kredensial muncul setelah disimpan.
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

@endsection

@push('scripts') <script> document.addEventListener("DOMContentLoaded", function() { /* ======================== IMAGE UPLOADER =========================*/ const uploader = document.getElementById("file-uploader"); const input = document.getElementById("file-input"); const preview = document.getElementById("image-preview"); const container = document.getElementById("image-preview-container"); const removeBtn = document.getElementById("remove-image-btn"); const hapusPosterCheck = document.getElementById('hapusPoster');

        uploader.addEventListener("dragover", e => {
            e.preventDefault();
            uploader.classList.add("on-drag");
        });

        uploader.addEventListener("dragleave", () => {
            uploader.classList.remove("on-drag");
        });

        uploader.addEventListener("drop", e => {
            e.preventDefault();
            uploader.classList.remove("on-drag");
            input.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        });

        input.addEventListener("change", () => {
            if (input.files[0]) showPreview(input.files[0]);
        });

        removeBtn.addEventListener("click", () => {
            input.value = "";
            container.style.display = "none";
            uploader.style.display = "block";
            preview.src = "#";
            if (hapusPosterCheck) hapusPosterCheck.checked = true;
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                container.style.display = "block";
                uploader.style.display = "none";
                if (hapusPosterCheck) hapusPosterCheck.checked = false;
            };
            reader.readAsDataURL(file);
        }

        /* ========================  LOGIC KEGIATAN =========================*/
        let counter = {{ $event->tamuUndangan->count() > 0 ? $event->tamuUndangan->count() : 1 }};

        // Tamu Toggle
        const checkTamu = document.getElementById('checkTamu');
        const tamuContainer = document.getElementById('daftarTamuContainer');
        checkTamu.addEventListener('change', function() {
            tamuContainer.style.display = this.checked ? 'block' : 'none';
        });

        // Add Tamu
        document.getElementById('btnTambahTamu').addEventListener('click', function() {
            counter++;
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="text" name="daftar_tamu[]" class="form-control" placeholder="Nama pembicara ${counter}">
                <button type="button" class="btn btn-light border text-danger" onclick="this.parentElement.remove()">×</button>
            `;
            document.getElementById('inputTamuWrapper').appendChild(div);
        });

        // Form Integrasi Toggles
        document.getElementById('checkRegForm').addEventListener('change', function() {
            document.getElementById('regFormContainer').style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('checkCloseForm').addEventListener('change', function() {
            document.getElementById('closeFormContainer').style.display = this.checked ? 'block' : 'none';
        });

        // PJ Toggle
        document.getElementById('checkPJ').addEventListener('change', function() {
            document.getElementById('pjInfoContainer').style.display = this.checked ? 'block' : 'none';
        });

        // Date Validation
        document.getElementById('formKegiatan').addEventListener('submit', function(e) {
            const start = new Date(document.querySelector('[name="start_time"]').value);
            const end = new Date(document.querySelector('[name="end_time"]').value);
            if (start >= end) {
                e.preventDefault();
                alert('Waktu selesai harus lebih besar dari waktu mulai!');
            }
        });
    });
    
    // Copy to clipboard function
    function copyToClipboard(elementId) {
        const input = document.getElementById(elementId);
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show feedback
        const btn = input.nextElementSibling;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 1500);
    }
</script>