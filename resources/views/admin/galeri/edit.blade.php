@extends('admin.layout')

@section('title', 'Edit Album')

@section('content')
<div class="container-fluid p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Album</h4>
        <a href="{{ route('galeri.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Daftar Album</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            {{-- success message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- error message --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ada kesalahan pada input:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="updateForm" action="{{ route('admin.galeri.update', $album->album_id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Judul Album --}}
                <div class="mb-3">
                    <label class="form-label">Judul Album <span class="text-danger">*</span></label>
                    <input type="text" name="album_name" class="form-control" required maxlength="100"
                           value="{{ old('album_name', $album->album_name) }}"
                           placeholder="Masukkan judul album">
                </div>

                <div class="row g-3">

                    {{-- COVER SECTION --}}
                <div class="mb-4">
                    <label class="form-label">Foto Cover Album</label>

                    <div id="coverContainer" class="mb-2">

                        @if ($album->cover)
                            <div class="position-relative d-inline-block me-2" id="oldCoverBox">

                                <img src="{{ asset('storage/' . $album->cover->image_url) }}"
                                     class="img-thumbnail"
                                     style="max-width:150px; height:auto;">

                                <span class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                      onclick="deleteCover()"
                                      style="cursor:pointer; border-radius:50%;">
                                    ×
                                </span>

                            </div>
                        @endif
                    </div>

                    <input type="hidden" name="delete_cover" id="delete_cover" value="0">

                    {{-- Input cover baru --}}
                    <input type="file" name="cover_photo" id="cover_photo"
                           class="form-control mt-2" accept="image/*">

                    {{-- Preview cover baru --}}
                    <div id="newCoverPreview" class="mt-3"></div>

                </div>

                {{-- ISI ALBUM --}}
                <div class="mb-3">
                    <label class="form-label">Foto Isi Album</label>

                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach ($album->photos->where('caption', '!=', 'Cover Album') as $photo)
                            <div class="position-relative d-inline-block"
                                 id="photoBox{{ $photo->photo_id }}">
                                <img src="{{ asset('storage/' . $photo->image_url) }}"
                                     class="img-thumbnail"
                                     style="max-width:120px; height:auto;">

                                <input type="text" name="old_captions[{{ $photo->photo_id }}]"
                                        value="{{ old('old_captions.'.$photo->photo_id, $photo->caption ?? '') }}"
                                        class="form-control form-control-sm mt-1"
                                        placeholder="Caption foto">

                                <span class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                      onclick="deletePhoto({{ $photo->photo_id }})"
                                      style="cursor:pointer; border-radius:50%;">
                                    ×
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="delete_photos" id="delete_photos">

                    <input type="file" id="photos" accept="image/*" multiple
                           class="form-control">

                    <div id="photosPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>

                    <a href="{{ route('galeri.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
let deletePhotos = [];
let selectedFiles = []; // array File
let newCoverFile = null;

// HAPUS COVER LAMA
function deleteCover() {
    document.getElementById('delete_cover').value = 1;
    document.getElementById('oldCoverBox')?.remove();
    document.getElementById('newCoverPreview').innerHTML = "";
    // Kosongkan input file cover supaya formData tidak mengirim file lama
    const coverInput = document.getElementById('cover_photo');
    if (coverInput) coverInput.value = "";
    newCoverFile = null;
}

// PREVIEW COVER BARU
document.getElementById("cover_photo").addEventListener("change", function (e) {
    // jika ada file baru, hapus preview/oldCover
    deleteCover();

    const file = e.target.files && e.target.files[0];
    if (!file) return;

    newCoverFile = file;

    const reader = new FileReader();
    reader.onload = function(evt) {
        const html = `
            <div class="position-relative d-inline-block" id="newCoverBox">
                <img src="${evt.target.result}" class="img-thumbnail"
                     style="max-width:150px; height:auto;">
                <span class="btn btn-danger btn-sm position-absolute top-0 end-0"
                      onclick="removeNewCover()" style="border-radius:50%;">×</span>
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
}

// HAPUS FOTO LAMA ISI ALBUM
function deletePhoto(id) {
    deletePhotos.push(id);
    document.getElementById('delete_photos').value = JSON.stringify(deletePhotos);
    document.getElementById("photoBox"+id).remove();
}

// PREVIEW FOTO BARU + INPUT CAPTION
document.getElementById("photos").addEventListener("change", function (e) {
    const files = Array.from(e.target.files || []);
    if (files.length === 0) return;

    selectedFiles.push(...files);

    renderPreview();

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
            card.className = 'position-relative d-inline-block me-2';
            card.style.width = '130px';

            const img = document.createElement('img');
            img.src = ev.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '120px';
            img.alt = file.name;

            const removeBtn = document.createElement('span');
            removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.cursor = 'pointer';
            removeBtn.innerText = '×';
            removeBtn.onclick = () => {
                removeNewPhoto(index);
            };

            // caption input untuk foto baru
            const captionInput = document.createElement('input');
            captionInput.type = 'text';
            captionInput.name = `new_captions[${index}]`;
            captionInput.className = 'form-control form-control-sm mt-1';
            captionInput.placeholder = 'Caption foto baru';

            card.appendChild(img);
            card.appendChild(removeBtn);
            card.appendChild(captionInput);

            wrap.appendChild(card);
        };
        reader.readAsDataURL(file);

        // Hidden input untuk file
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

function removeNewPhoto(i) {
    selectedFiles.splice(i, 1);
    renderPreview();
}

// SUBMIT FORM
document.getElementById("updateForm").addEventListener("submit", function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';
    }
});
</script>

{{-- Hidden container untuk file inputs --}}
<div id="photosContainer" style="display:none;"></div>
@endpush