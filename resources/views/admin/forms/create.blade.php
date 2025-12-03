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
        <div class="alert alert-success border-0 d-flex align-items-start mb-4" role="alert">
            <i class="fas fa-magic me-3 mt-1 fs-5"></i>
            <div>
                <h6 class="fw-semibold mb-1">Panduan Form Builder</h6>
                <ol class="mb-0 ps-3 small" style="line-height: 1.6;">
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
                    {{-- Form Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Pengaturan Form</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Judul Form</label>
                                <input type="text" name="title" class="form-control form-control-sm"
                                    value="Form Builder {{ date('Y-m-d H:i') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Slug (URL)</label>
                                <input type="text" name="slug" class="form-control form-control-sm"
                                    value="form-{{ time() }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-semibold">Deskripsi</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
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
                                        <i class="fas fa-check-circle fs-5"></i>
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
                        <div class="card-body p-0">
                            <div
                                class="d-flex justify-content-between align-items-center p-3 border-bottom bg-white rounded-top">
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="fw-bold mb-0">Canvas</h6>
                                    <span class="badge bg-light text-secondary border fw-normal">0 komponen</span>
                                </div>
                            </div>

                            {{-- Canvas Area --}}
                            <div id="fb-canvas" class="p-4"
                                style="min-height: 500px; background-color: #F9FAFB; background-image: radial-gradient(#E5E7EB 1px, transparent 1px); background-size: 20px 20px;">

                                {{-- Empty State (Visible when no children) --}}
                                <div
                                    class="empty-state d-flex flex-column align-items-center justify-content-center h-100 py-5 text-center text-muted">
                                    <div class="mb-3 p-3 bg-white rounded-circle shadow-sm">
                                        <i class="fas fa-plus fs-3 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark">Canvas Kosong</h6>
                                    <p class="small mb-0">Pilih komponen dari panel kiri untuk memulai</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Preview Modal --}}
        <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold">Preview Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div id="fb-preview-area" class="bg-white p-4 rounded shadow-sm"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
                border: 1px solid #E5E7EB;
                border-radius: 8px;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                transition: all 0.2s;
            }

            .fb-field:hover {
                border-color: #22C55E;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .cursor-pointer {
                cursor: pointer;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/form-builder.js') }}"></script>
    @endpush
@endsection
