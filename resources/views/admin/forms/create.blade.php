@extends('admin.layout')

@section('title', 'Form Builder')

@section('content')
    <div class="p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Form Builder</h4>
                <p class="text-muted mb-0">Desain formulir dengan drag & drop</p>
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
                    <i class="fas fa-save me-1"></i> Simpan Layout
                </button>
            </div>
        </div>

        {{-- Alert Guide --}}
        <div class="alert border-0 d-flex align-items-start mb-4" role="alert"
            style="background-color: #E8F5E9;">
            <i class="fas fa-magic me-3 mt-1 fs-5" style="color: #2E7D32;"></i>
            <div>
                <h6 class="fw-semibold mb-1" style="color: #2E7D32;">Panduan Page Builder</h6>
                <ol class="mb-0 ps-3 small" style="line-height: 1.8; color: #2E7D32;">
                    <li>Pilih komponen dari panel kiri dan klik untuk menambahkan</li>
                    <li>Drag komponen di canvas untuk mengatur urutan</li>
                    <li>Klik "Edit" untuk mengubah konten komponen</li>
                    <li>Klik "Simpan Layout" untuk menerapkan perubahan</li>
                </ol>
            </div>
        </div>

        <form id="fb-form" method="POST" action="{{ route('admin.forms.store') }}">
            @csrf

            <div class="row g-4">
                {{-- Left Panel: Components --}}
                <div class="col-lg-3">
                    {{-- Toolbox --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-1">Komponen</h6>
                            <p class="text-muted small mb-4">Pilih untuk menambahkan</p>

                            <div class="fb-toolbox d-flex flex-column gap-3">
                                {{-- Header Section --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="header">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <span class="fw-bold text-secondary" style="font-size: 18px;">T</span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Header Section</h6>
                                        <small class="text-muted" style="font-size: 12px;">Judul besar dengan subjudul
                                            dan gambar latar</small>
                                    </div>
                                </div>

                                {{-- Text Input --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="text">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <i class="fas fa-magic text-secondary" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Text Input</h6>
                                        <small class="text-muted" style="font-size: 12px;">Input teks satu baris</small>
                                    </div>
                                </div>

                                {{-- Text Area --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="textarea">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <i class="fas fa-align-left text-secondary" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Text Area</h6>
                                        <small class="text-muted" style="font-size: 12px;">Input teks panjang</small>
                                    </div>
                                </div>

                                {{-- Dropdown --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="select">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <i class="fas fa-book-open text-secondary" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Dropdown</h6>
                                        <small class="text-muted" style="font-size: 12px;">Pilihan menu dropdown</small>
                                    </div>
                                </div>

                                {{-- Radio --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="radio">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <i class="fas fa-dot-circle text-secondary" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Radio Button</h6>
                                        <small class="text-muted" style="font-size: 12px;">Pilihan tunggal</small>
                                    </div>
                                </div>

                                {{-- Checkbox --}}
                                <div class="fb-tool component-card d-flex align-items-center p-3 border rounded-3 bg-white cursor-pointer"
                                    data-type="checkbox">
                                    <div class="icon-wrapper bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                        style="width: 44px; height: 44px; min-width: 44px;">
                                        <i class="fas fa-check-square text-secondary" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size: 14px;">Checkbox</h6>
                                        <small class="text-muted" style="font-size: 12px;">Pilihan jamak</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Panel: Canvas --}}
                <div class="col-lg-9">
                    {{-- Canvas Title --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <h5 class="fw-bold mb-0">Canvas</h5>
                        <span id="component-count" class="badge bg-secondary fw-normal" style="font-size: 11px;">0 komponen</span>
                    </div>

                    {{-- Canvas Card Wrapper --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body p-0">
                            {{-- Canvas Area with Grid Pattern --}}
                            <div id="fb-canvas" class="canvas-area d-flex flex-column gap-3"
                                style="min-height: 400px; border: 1px dashed #dee2e6; border-radius: 16px; padding: 24px; background-color: #FAFBFC; background-image: linear-gradient(#E8ECF0 1px, transparent 1px), linear-gradient(90deg, #E8ECF0 1px, transparent 1px); background-size: 20px 20px;">
                                
                                {{-- Empty State --}}
                                <div class="empty-state d-flex flex-column align-items-center justify-content-center flex-grow-1 py-5" id="empty-state">
                                    <div class="mb-3">
                                        <i class="fas fa-plus" style="font-size: 42px; color: #9CA3AF;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2" style="color: #374151;">Canvas Kosong</h5>
                                    <p class="small mb-0" style="color: #6B7280;">Pilih komponen dari panel kiri untuk memulai</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Form Settings --}}
                    <input type="hidden" name="title" value="Form Builder {{ date('Y-m-d H:i') }}">
                    <input type="hidden" name="slug" value="form-{{ time() }}">
                    <input type="hidden" name="description" value="">
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
        <div class="fb-field card border-0 shadow-sm p-3 position-relative" style="background: white; border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary field-type-badge">Text</span>
                    <span class="fw-bold text-dark small field-index">Field #1</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light text-muted handle" style="cursor: grab;">
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

@push('styles')
    <style>
        .component-card {
            transition: all 0.2s ease;
        }

        .component-card:hover {
            border-color: #22C55E !important;
            background-color: #F0FDF4 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .fb-field {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .fb-field:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .fb-field.sortable-ghost {
            opacity: 0.4;
            background: #f0f9ff;
        }

        .fb-field.sortable-chosen {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .handle {
            cursor: grab;
        }

        .handle:active {
            cursor: grabbing;
        }

        .canvas-area {
            transition: all 0.2s ease;
        }

        .canvas-area.sortable-drag {
            border-color: #22C55E !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('fb-canvas');
            const form = document.getElementById('fb-form');
            const template = document.getElementById('field-template');
            const componentCount = document.getElementById('component-count');
            let fieldCount = 0;

            // Update component count
            function updateCount() {
                const count = document.querySelectorAll('.fb-field').length;
                componentCount.textContent = count + ' komponen';
            }

            // Init Sortable for drag & drop
            new Sortable(canvas, {
                animation: 150,
                handle: '.handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function() {
                    // Re-index fields after drag
                    document.querySelectorAll('.fb-field').forEach((field, index) => {
                        field.querySelector('.field-index').textContent = `Field #${index + 1}`;
                    });
                }
            });

            // Click from Toolbox
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
                const index = Date.now();
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

                // Update count
                updateCount();

                // Scroll to bottom
                fieldNode.scrollIntoView({
                    behavior: 'smooth'
                });

                // Attach Remove Event
                attachRemoveEvent(fieldNode);
            }

            // Attach Remove Event
            function attachRemoveEvent(node) {
                const removeBtn = node.querySelector('.fb-remove');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (confirm('Hapus field ini?')) {
                            node.remove();
                            updateCount();

                            // Show empty state if no fields
                            if (document.querySelectorAll('.fb-field').length === 0) {
                                canvas.innerHTML = `
                                    <div class="empty-state d-flex flex-column align-items-center justify-content-center flex-grow-1 py-5" id="empty-state">
                                        <div class="mb-3">
                                            <i class="fas fa-plus" style="font-size: 42px; color: #9CA3AF;"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2" style="color: #374151;">Canvas Kosong</h5>
                                        <p class="small mb-0" style="color: #6B7280;">Pilih komponen dari panel kiri untuk memulai</p>
                                    </div>
                                `;
                            }
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
