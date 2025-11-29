@extends('admin.layout')

@section('title', 'Tambah Album')

@section('content')

<div class="container-fluid p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">Tambah Album</h4>

        <a href="{{ route('admin.galeri') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Daftar Album</a>

    </div>



    <div class="card shadow-sm mb-4">

        <div class="card-body">



            @if (session('success'))

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif



            @if ($errors->any())

                <div class="alert alert-danger">

                    <strong>Ada masalah:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">

                @csrf



                {{-- Judul Album --}}

                <div class="mb-3">

                    <label class="form-label">Judul Album <span class="text-danger">*</span></label>

                    <input type="text" name="album_name" class="form-control" required maxlength="100"

                           value="{{ old('album_name') }}" placeholder="Masukkan judul album">

                </div>



                {{-- Cover Album --}}

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">Foto Cover Album <span class="text-danger">*</span></label>

                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="form-control">

                        <small class="text-muted">Format: jpg, jpeg, png. Maks 2MB.</small>



                        <div id="coverPreviewWrapper" class="mt-2" style="display:none;">
                                <label class="form-label small">Preview Cover</label>
                                <div class="position-relative d-inline-block">
                                    <img id="coverPreview" class="img-thumbnail" style="max-width:240px; display:block;">
                                    <!-- Add remove button for cover photo -->
                                </div>
                            </div>
                        </div>



                    {{-- Foto Isi Album --}}

                    <div class="col-md-6">

                        <label class="form-label">Foto Isi Album (opsional)</label>

                        <input id="photos" type="file" accept="image/*" multiple class="form-control">

                        <small class="text-muted d-block mb-2">Bisa pilih banyak gambar. Maks 4MB per foto.</small>



                        <!-- Preview Foto -->

                        <div id="photosPreview" class="d-flex flex-wrap gap-2 mt-2"></div>



                        <!-- Hidden container to inject photo inputs -->

                        <div id="photosContainer"></div>

                    </div>

                </div>



                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-cloud-upload me-1"></i> Simpan & Upload Album

                    </button>

                    <a href="{{ route('admin.galeri') }}" class="btn btn-outline-secondary">Batal</a>

                </div>



            </form>

        </div>

    </div>

</div>



@endsection



@push('scripts')

<script>
// PREVIEW COVER ALBUM
const coverInput = document.getElementById('cover_photo');
const coverPreview = document.getElementById('coverPreview');
const coverWrapper = document.getElementById('coverPreviewWrapper');

coverInput.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (!file) {
        coverWrapper.style.display = 'none';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        coverPreview.src = e.target.result;
        coverWrapper.style.display = 'block';

        // ambil container yang bener (yang position-relative)
        const imgContainer = coverWrapper.querySelector('.position-relative');

        // hapus tombol lama bila ada
        let removeBtn = imgContainer.querySelector('#removeCoverBtn');
        if (removeBtn) removeBtn.remove();

        // buat tombol baru
        removeBtn = document.createElement("span");
        removeBtn.id = "removeCoverBtn";
        removeBtn.className = "btn btn-danger btn-sm position-absolute top-0 end-0";
        removeBtn.style.borderRadius = "50%";
        removeBtn.style.cursor = "pointer";
        removeBtn.style.zIndex = "20";
        removeBtn.innerText = "×";

        removeBtn.onclick = function() {
            coverInput.value = "";
            coverPreview.src = "";
            coverWrapper.style.display = "none";
        };

        imgContainer.appendChild(removeBtn);
    };

    reader.readAsDataURL(file);
});


// PREVIEW MULTIPLE PHOTOS
let selectedFiles = [];
let captions = {}; // Object to store captions

const photosInput = document.getElementById('photos');
const photosPreview = document.getElementById('photosPreview');
const photosContainer = document.getElementById('photosContainer');

// ketika pilih foto baru
photosInput.addEventListener('change', function (e) {
    const newFiles = Array.from(e.target.files);
    selectedFiles.push(...newFiles);

    // reset input agar bisa pilih lagi
    photosInput.value = "";
    renderPhotos();
});

function renderPhotos() {
    photosPreview.innerHTML = "";
    photosContainer.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(ev) {
            // preview gambar
            const wrapper = document.createElement("div");
            wrapper.style.position = "relative";

            const img = document.createElement("img");
            img.src = ev.target.result;
            img.className = "img-thumbnail";
            img.style.maxWidth = "120px";

            // caption foto
            const captionInput = document.createElement("input");
            captionInput.type = "text";
            captionInput.name = `captions[${index}]`;
            captionInput.className = "form-control form-control-sm mt-1";
            captionInput.placeholder = "Caption foto (opsional)";

            // Restore caption value if it exists
            if (captions[file.name]) {
                captionInput.value = captions[file.name];
            }

            // Save caption value on input change
            captionInput.addEventListener('input', function() {
                captions[file.name] = captionInput.value;
            });

            // tombol hapus
            const removeBtn = document.createElement("span");
            removeBtn.className = "btn btn-danger btn-sm position-absolute top-0 end-0";
            removeBtn.style.borderRadius = "50%";
            removeBtn.style.cursor = "pointer";
            removeBtn.innerText = "×";

            removeBtn.onclick = function() {
                // Tambahkan efek transisi sebelum menghapus
                wrapper.style.transition = "opacity 0.3s ease, transform 0.3s ease";
                wrapper.style.opacity = "0";
                wrapper.style.transform = "scale(0.9)";

                setTimeout(() => {
                    // Remove file and caption setelah animasi selesai
                    selectedFiles.splice(index, 1);
                    delete captions[file.name];
                    renderPhotos();
                }, 300); // Waktu sesuai dengan durasi transisi
            };

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            wrapper.appendChild(captionInput);
            photosPreview.appendChild(wrapper);
        };

        reader.readAsDataURL(file);

        // hidden input untuk dikirim ke server
        const dt = new DataTransfer();
        dt.items.add(file);

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "file";
        hiddenInput.name = "photos[]";
        hiddenInput.files = dt.files;
        hiddenInput.hidden = true;

        photosContainer.appendChild(hiddenInput);
    });
}

// Extra safeguard: normalize/removeCoverBtn placement and behavior
document.addEventListener('DOMContentLoaded', function() {
    function normalizeCoverButton() {
        const relative = coverWrapper.querySelector('.position-relative') || coverWrapper;
        const btn = document.getElementById('removeCoverBtn');
        if (!btn) return;
        // move button into relative container if not already
        if (btn.parentNode !== relative) {
            btn.remove();
            relative.appendChild(btn);
        }
        // ensure visible positioning and aria attributes
        btn.style.top = btn.style.top || '8px';
        btn.style.right = btn.style.right || '8px';
        btn.setAttribute('aria-label', 'Hapus cover');
        // (re)bind click to reliably clear the input and remove preview
        btn.onclick = function() {
            coverInput.value = '';
            coverPreview.src = '';
            coverWrapper.style.display = 'none';
            btn.remove();
        };
    }

    // run once now in case button was created by earlier script
    normalizeCoverButton();

    // also run after every change to the input to ensure correct placement
    coverInput.addEventListener('change', function() {
        // small delay to allow other handlers to finish creating elements
        setTimeout(normalizeCoverButton, 50);
    });
});
</script>

@endpush