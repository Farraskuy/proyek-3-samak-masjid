@extends('admin.layout')

@section('title', 'Edit Form Builder')

@section('content')
    <div class="p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Edit Form: {{ $form->title }}</h4>
                <p class="text-muted mb-0">Perbarui desain formulir Anda</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.forms.index') }}" class="btn btn-light border bg-white text-dark fw-medium">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="button" class="btn btn-light border bg-white text-dark fw-medium"
                    onclick="window.location.reload()">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="button" id="fb-preview" class="btn btn-light border bg-white text-dark fw-medium">
                    <i class="fas fa-eye me-1"></i> Preview
                </button>
                <button type="button" id="fb-save" class="btn btn-success fw-medium">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <form id="fb-form" method="POST" action="{{ route('admin.forms.update', $form->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Left Panel: Components --}}
                <div class="col-lg-3">
                    {{-- Form Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Pengaturan Form</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Judul Form</label>
                                <input type="text" name="title" class="form-control form-control-sm"
                                    value="{{ $form->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Slug (URL)</label>
                                <input type="text" name="slug" class="form-control form-control-sm"
                                    value="{{ $form->slug }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-semibold">Deskripsi</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2">{{ $form->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Toolbox --}}
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Komponen</h6>
                            <p class="text-muted small mb-4">Pilih untuk menambahkan</p>

                            <div class="fb-toolbox d-flex flex-column gap-3">
                                {{-- Header Section --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="header">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-heading fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Header Section</h6>
                                        <small class="text-muted" style="font-size: 11px;">Judul besar dengan
                                            subjudul</small>
                                    </div>
                                </div>

                                {{-- Text Field --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="text">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-font fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Text Input</h6>
                                        <small class="text-muted" style="font-size: 11px;">Input teks satu baris</small>
                                    </div>
                                </div>

                                {{-- Textarea --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="textarea">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-align-left fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Text Area</h6>
                                        <small class="text-muted" style="font-size: 11px;">Input teks panjang</small>
                                    </div>
                                </div>

                                {{-- Select --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="select">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-list fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Dropdown</h6>
                                        <small class="text-muted" style="font-size: 11px;">Pilihan menu dropdown</small>
                                    </div>
                                </div>

                                {{-- Radio --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="radio">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-dot-circle fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Radio Button</h6>
                                        <small class="text-muted" style="font-size: 11px;">Pilihan tunggal</small>
                                    </div>
                                </div>

                                {{-- Checkbox --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded bg-white cursor-pointer"
                                    data-type="checkbox">
                                    <div class="icon-wrapper bg-light rounded p-2 me-3 text-secondary">
                                        <i class="fas fa-check-square fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 fs-14px">Checkbox</h6>
                                        <small class="text-muted" style="font-size: 11px;">Pilihan jamak</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Panel: Canvas --}}
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 bg-light rounded-3">
                            <div id="fb-canvas" class="min-vh-50 d-flex flex-column gap-3">
                                {{-- Existing Fields Loop --}}
                                @foreach ($form->fields as $field)
                                    <div class="fb-field card border-0 shadow-sm p-3 position-relative">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($field->type) }}</span>
                                                <span class="fw-bold text-dark small">Field #{{ $loop->index + 1 }}</span>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-light text-muted handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light text-danger fb-remove">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Label</label>
                                                <input type="text" class="form-control form-control-sm fb-label"
                                                    name="fields[{{ $loop->index }}][label]" value="{{ $field->label }}"
                                                    placeholder="Label pertanyaan">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Field Name
                                                    (Unique)</label>
                                                <input type="text" class="form-control form-control-sm fb-name"
                                                    name="fields[{{ $loop->index }}][name]" value="{{ $field->name }}"
                                                    placeholder="field_name">
                                            </div>

                                            <input type="hidden" class="fb-type"
                                                name="fields[{{ $loop->index }}][type]" value="{{ $field->type }}">

                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-muted">Placeholder / Helper
                                                    Text</label>
                                                <input type="text" class="form-control form-control-sm fb-placeholder"
                                                    name="fields[{{ $loop->index }}][placeholder]"
                                                    value="{{ $field->placeholder }}" placeholder="Teks bantuan...">
                                            </div>

                                            @if (in_array($field->type, ['select', 'radio', 'checkbox']))
                                                <div class="col-12 fb-options-container">
                                                    <label class="form-label small fw-semibold text-muted">Opsi (Pisahkan
                                                        dengan koma)</label>
                                                    <input type="text" class="form-control form-control-sm fb-options"
                                                        name="fields[{{ $loop->index }}][options]"
                                                        value="{{ is_array($field->options) ? implode(',', $field->options) : '' }}"
                                                        placeholder="Option 1, Option 2, Option 3">
                                                </div>
                                            @endif

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input fb-required" type="checkbox"
                                                        name="fields[{{ $loop->index }}][is_required]" value="1"
                                                        id="req_{{ $loop->index }}"
                                                        {{ $field->is_required ? 'checked' : '' }}>
                                                    <label class="form-check-label small"
                                                        for="req_{{ $loop->index }}">Wajib Diisi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Empty State if no fields --}}
                                @if ($form->fields->isEmpty())
                                    <div class="text-center text-muted py-5" id="empty-state">
                                        <i class="fas fa-layer-group fa-3x mb-3 text-light-emphasis"></i>
                                        <p class="mb-0">Canvas kosong. Tarik komponen ke sini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Preview Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="preview-content"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Template for New Field (Hidden) --}}
    <template id="field-template">
        <div class="fb-field card border-0 shadow-sm p-3 position-relative mb-3 animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary field-type-badge">Text</span>
                    <span class="fw-bold text-dark small field-index">Field #1</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light text-muted handle">
                        <i class="fas fa-grip-vertical"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light text-danger fb-remove">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Label</label>
                    <input type="text" class="form-control form-control-sm fb-label" name="label"
                        placeholder="Label pertanyaan">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Field Name (Unique)</label>
                    <input type="text" class="form-control form-control-sm fb-name" name="name"
                        placeholder="field_name">
                </div>

                <input type="hidden" class="fb-type" name="type">

                <div class="col-12">
                    <label class="form-label small fw-semibold text-muted">Placeholder / Helper Text</label>
                    <input type="text" class="form-control form-control-sm fb-placeholder" name="placeholder"
                        placeholder="Teks bantuan...">
                </div>

                <div class="col-12 fb-options-container" style="display:none;">
                    <label class="form-label small fw-semibold text-muted">Opsi (Pisahkan dengan koma)</label>
                    <input type="text" class="form-control form-control-sm fb-options" name="options"
                        placeholder="Option 1, Option 2, Option 3">
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input fb-required" type="checkbox" name="is_required" value="1"
                            id="req_new">
                        <label class="form-check-label small" for="req_new">Wajib Diisi</label>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('fb-canvas');
            const form = document.getElementById('fb-form');
            const template = document.getElementById('field-template');
            let fieldCount = {{ $form->fields->count() }};

            // Init Sortable
            new Sortable(canvas, {
                animation: 150,
                handle: '.handle',
                ghostClass: 'bg-light'
            });

            // Drag from Toolbox
            document.querySelectorAll('.fb-tool').forEach(tool => {
                tool.addEventListener('click', function() {
                    const type = this.dataset.type;
                    addField(type);
                });
            });

            function addField(type) {
                const clone = template.content.cloneNode(true);
                const fieldNode = clone.querySelector('.fb-field');

                // Update Badge
                fieldNode.querySelector('.field-type-badge').textContent = type.charAt(0).toUpperCase() + type
                    .slice(1);

                // Update Index
                fieldCount++;
                fieldNode.querySelector('.field-index').textContent = `Field #${fieldCount}`;

                // Set Type Input
                fieldNode.querySelector('.fb-type').value = type;

                // Show Options Input if needed
                if (['select', 'radio', 'checkbox'].includes(type)) {
                    fieldNode.querySelector('.fb-options-container').style.display = 'block';
                }

                // Update Names for Array Submission
                const index = Date.now(); // Use timestamp for unique index
                fieldNode.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.name) {
                        input.name = `fields[${index}][${input.name}]`;
                        if (input.type === 'checkbox' && input.classList.contains('fb-required')) {
                            input.id = `req_${index}`;
                            fieldNode.querySelector('label[for="req_new"]').setAttribute('for',
                                `req_${index}`);
                        }
                    }
                });

                // Remove Empty State
                const emptyState = document.getElementById('empty-state');
                if (emptyState) emptyState.remove();

                canvas.appendChild(fieldNode);

                // Scroll to bottom
                fieldNode.scrollIntoView({
                    behavior: 'smooth'
                });

                // Attach Remove Event
                attachRemoveEvent(fieldNode);
            }

            // Attach Remove Event to existing and new fields
            function attachRemoveEvent(node) {
                const removeBtn = node.querySelector('.fb-remove');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (confirm('Hapus field ini?')) {
                            node.remove();
                        }
                    });
                }
            }

            // Initial Attach
            document.querySelectorAll('.fb-field').forEach(node => attachRemoveEvent(node));

            // Save Handler
            document.getElementById('fb-save').addEventListener('click', function() {
                form.submit();
            });

            // Preview Handler
            document.getElementById('fb-preview').addEventListener('click', function() {
                const previewContent = document.getElementById('preview-content');
                previewContent.innerHTML = '';

                document.querySelectorAll('.fb-field').forEach(field => {
                    const label = field.querySelector('.fb-label').value || 'Untitled Field';
                    const type = field.querySelector('.fb-type').value;
                    const placeholder = field.querySelector('.fb-placeholder').value;
                    const options = field.querySelector('.fb-options') ? field.querySelector(
                        '.fb-options').value.split(',') : [];
                    const required = field.querySelector('.fb-required').checked;

                    let html =
                        `<div class="mb-3"><label class="form-label fw-bold">${label} ${required ? '<span class="text-danger">*</span>' : ''}</label>`;

                    if (type === 'text' || type === 'email') {
                        html +=
                            `<input type="text" class="form-control" placeholder="${placeholder}">`;
                    } else if (type === 'textarea') {
                        html +=
                            `<textarea class="form-control" rows="3" placeholder="${placeholder}"></textarea>`;
                    } else if (type === 'select') {
                        html += `<select class="form-select"><option value="">-- Pilih --</option>`;
                        options.forEach(opt => html += `<option>${opt.trim()}</option>`);
                        html += `</select>`;
                    } else if (type === 'radio') {
                        options.forEach(opt => {
                            html +=
                                `<div class="form-check"><input class="form-check-input" type="radio" name="preview_radio"><label class="form-check-label">${opt.trim()}</label></div>`;
                        });
                    } else if (type === 'checkbox') {
                        options.forEach(opt => {
                            html +=
                                `<div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-label">${opt.trim()}</label></div>`;
                        });
                    } else if (type === 'header') {
                        html =
                            `<h4 class="fw-bold mt-4 mb-2 border-bottom pb-2">${label}</h4><p class="text-muted">${placeholder}</p>`;
                    }

                    html += `</div>`;
                    previewContent.innerHTML += html;
                });

                new bootstrap.Modal(document.getElementById('previewModal')).show();
            });
        });
    </script>
@endpush
