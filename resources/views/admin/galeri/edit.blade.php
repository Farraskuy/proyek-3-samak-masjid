@extends('admin.layout')

@section('title', 'Edit Album')

@push('styles')
    <style>
        .file-uploader {
            padding: 2rem;
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
            background: #fafafa;
            text-align: center;
            cursor: pointer;
            color: #666;
            transition: .2s ease-in-out;
            display: block;
        }

        .file-uploader.on-drag {
            background: #f3f3f3;
            border-color: #CE9138 !important;
        }

        #newCoverPreview img,
        #photosPreview img {
            border-radius: 0.5rem;
        }
    </style>
@endpush

@section('content') <section class="p-3 container">

        {{-- success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- error message --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Ada kesalahan pada input:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="updateForm" action="{{ route('admin.galeri.update', $album->album_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Edit Album</h4>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Detail Album --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Informasi Album</h5>

                        {{-- Judul Album --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Judul Album <span class="text-danger">*</span></label>
                            <input type="text" name="album_name" class="form-control input-lg" required maxlength="100"
                                value="{{ old('album_name', $album->album_name) }}" placeholder="Masukkan judul album">
                        </div>

                        {{-- ISI ALBUM --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-3">Foto Isi Album</label>

                            <input type="hidden" name="delete_photos" id="delete_photos">

                            <label for="photos" class="file-uploader" id="photos-uploader">
                                <i class="fas fa-images fa-2x mb-2"></i>
                                <div class="fw-semibold">Tambah Foto Baru</div>
                                <div class="small text-muted">Drag & drop atau klik untuk pilih banyak</div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>Maks. 4 MB/foto | JPG, JPEG, PNG, WEBP
                                </div>
                            </label>

                            <input type="file" id="photos" accept="image/*" multiple class="d-none">

                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <div id="photosPreview" class="d-flex flex-wrap gap-3"></div>
                                
                                @foreach ($album->photos->where('caption', '!=', 'Cover Album') as $photo)
                                    <div class="position-relative d-inline-block" id="photoBox{{ $photo->photo_id }}"
                                        style="width: 140px;">
                                        <img src="{{ asset('storage/' . $photo->image_url) }}"
                                            class="img-thumbnail rounded-3 w-100" style="height: 140px; object-fit: cover;">

                                        <input type="text" name="old_captions[{{ $photo->photo_id }}]"
                                            value="{{ old('old_captions.' . $photo->photo_id, $photo->caption ?? '') }}"
                                            class="form-control form-control-sm mt-2" placeholder="Caption">

                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                            onclick="deletePhoto({{ $photo->photo_id }})"
                                            style="border-radius: 50%; width: 24px; height: 24px; padding: 0; line-height: 24px; margin: 5px;">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Cover & Save --}}
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Cover Album</h5>

                        {{-- COVER SECTION --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Cover</label>

                            <div id="coverContainer" class="mb-2">
                                @if ($album->cover)
                                    <div class="position-relative d-block mb-3" id="oldCoverBox">
                                        <img src="{{ asset('storage/' . $album->cover->image_url) }}"
                                            class="w-100 rounded-3 border">

                                        <button type="button" class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2"
                                            onclick="deleteCover()"
                                            style="border-radius: 50%; width: 32px; height: 32px; cursor: pointer;">
                                            &times;
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <input type="hidden" name="delete_cover" id="delete_cover" value="0">

                            {{-- Input cover baru --}}
                            <label for="cover_photo" class="file-uploader {{ $album->cover ? 'd-none' : '' }}" id="cover-uploader">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="fw-semibold">Ganti Cover</div>
                                <div class="small text-muted">Drag & drop atau klik</div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>Maks. 2 MB | JPG, JPEG, PNG, WEBP
                                </div>
                            </label>

                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*" class="d-none">

                            {{-- Preview cover baru --}}
                            <div id="newCoverPreview" class="mt-3"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Simpan & Upload
                        </button>
                    </div>
                </div>
            </div>
            <div id="photosContainer" style="display:none;"></div>
        </form>

    </section>



@endsection

@push('scripts')
    <script>
        const uploaders = [
            { id: 'cover-uploader', input: 'cover_photo' },
            { id: 'photos-uploader', input: 'photos' }
        ];

        document.addEventListener("DOMContentLoaded", function() {
            uploaders.forEach(obj => {
                const uploader = document.getElementById(obj.id);
                const input = document.getElementById(obj.input);

                if (uploader && input) {
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
                        
                        try {
                            const dt = new DataTransfer();
                            Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));
                            input.files = dt.files;
                        } catch (err) {
                            input.files = e.dataTransfer.files;
                        }
                        
                        const event = new Event('change');
                        input.dispatchEvent(event);
                    });
                }
            });
        });

        let deletePhotos = [];
        let selectedFiles = []; // array File
        let newCoverFile = null;
        let captions = {}; // Store captions by file name

        // HAPUS COVER LAMA
        function deleteCover() {
            document.getElementById('delete_cover').value = 1;
            document.getElementById('oldCoverBox')?.remove();
            document.getElementById('newCoverPreview').innerHTML = "";
            // Show uploader
            document.getElementById('cover-uploader').classList.remove('d-none');
            // Kosongkan input file cover
            const coverInput = document.getElementById('cover_photo');
            if (coverInput) coverInput.value = "";
            newCoverFile = null;
        }

        // PREVIEW COVER BARU
        document.getElementById("cover_photo").addEventListener("change", function(e) {
            // jika ada file baru, hapus preview/oldCover
            if (document.getElementById('oldCoverBox')) deleteCover();

            const file = e.target.files && e.target.files[0];
            if (!file) return;

            newCoverFile = file;
            document.getElementById('cover-uploader').classList.add('d-none');

            const reader = new FileReader();
            reader.onload = function(evt) {
                const html = `
        <div class="position-relative d-block" id="newCoverBox">
            <img src="${evt.target.result}" class="w-100 rounded-3 border">
            <button type="button" class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2"
                  onclick="removeNewCover()" style="border-radius:50%; width:32px; height:32px; cursor:pointer;">&times;</button>
        </div>
    `;
                document.getElementById('newCoverPreview').innerHTML = html;
            };
            reader.readAsDataURL(file);
        });

        function removeNewCover() {
            newCoverFile = null;
            document.getElementById('cover_photo').value = "";
            document.getElementById('newCoverPreview').innerHTML = "";
            document.getElementById('cover-uploader').classList.remove('d-none');
        }

        // HAPUS FOTO LAMA ISI ALBUM
        function deletePhoto(id) {
            deletePhotos.push(id);
            document.getElementById('delete_photos').value = JSON.stringify(deletePhotos);
            document.getElementById("photoBox" + id).remove();
        }

        // PREVIEW FOTO BARU + INPUT CAPTION
        document.getElementById("photos").addEventListener("change", function(e) {
            const files = Array.from(e.target.files || []);
            if (files.length === 0) return;

            selectedFiles.push(...files);

            renderPreview();

            // Reset value input trigger agar bisa select file yang sama berulang kali jika perlu
            this.value = "";
        });

        function renderPreview() {
            const wrap = document.getElementById("photosPreview");
            const container = document.getElementById("photosContainer");

            wrap.innerHTML = "";
            container.innerHTML = "";

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    // Preview card
                    const card = document.createElement('div');
                    card.className = 'position-relative d-inline-block';
                    card.style.width = '140px';
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'img-thumbnail rounded-3 w-100';
                    img.style.height = '140px';
                    img.style.objectFit = 'cover';
                    img.alt = file.name;

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.width = '24px';
                    removeBtn.style.height = '24px';
                    removeBtn.style.padding = '0';
                    removeBtn.style.lineHeight = '24px';
                    removeBtn.style.margin = '5px';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = () => {
                        removeNewPhoto(index, card);
                    };

                    // caption input untuk foto baru
                    const captionInput = document.createElement('input');
                    captionInput.type = 'text';
                    captionInput.name = `new_captions[${index}]`;
                    captionInput.className = 'form-control form-control-sm mt-2';
                    captionInput.placeholder = 'Caption foto baru';
                    
                    // Restore caption value if exists
                    if (captions[file.name]) {
                        captionInput.value = captions[file.name];
                    }
                    
                    // Save caption on input change
                    captionInput.addEventListener('input', function() {
                        captions[file.name] = captionInput.value;
                    });

                    card.appendChild(img);
                    card.appendChild(removeBtn);
                    card.appendChild(captionInput);

                    wrap.appendChild(card);
                };
                reader.readAsDataURL(file);

                // Hidden input untuk file (name="photos[]" ada di sini)
                const dt = new DataTransfer();
                dt.items.add(file);

                const hiddenInput = document.createElement("input");
                hiddenInput.type = "file";
                hiddenInput.name = "photos[]";
                hiddenInput.files = dt.files;
                hiddenInput.hidden = true;

                container.appendChild(hiddenInput);
            });
        }

        function removeNewPhoto(i, cardElement) {
            // Smooth animation
            cardElement.style.opacity = '0';
            cardElement.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                // Remove file and caption after animation
                const fileName = selectedFiles[i].name;
                delete captions[fileName];
                selectedFiles.splice(i, 1);
                renderPreview();
            }, 300);
        }

        // SUBMIT FORM
        document.getElementById("updateForm").addEventListener("submit", function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-hourglass-split me-1"></i> Menyimpan...';
            }
        });
    </script>
@endpush
