@extends('admin.layout')

@section('title', 'Form Builder')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Form Builder & Generator</h4>
            <a href="{{ route('admin.forms.create') }}" class="btn btn-success fw-semibold">
                <i class="fas fa-plus me-1"></i> Buat Form Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control" placeholder="Cari judul form"
                            value="{{ request()->query('keyword', '') }}" name="keyword">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Form</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Field</th>
                                <th>Total Response</th>
                                <th>Dibuat Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($forms ?? collect()) as $index => $form)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $form->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $form->slug }}</small>
                                    </td>
                                    <td>{{ Str::limit($form->description, 40) ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $form->fields->count() }} field</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $form->responses->count() }} response</span>
                                    </td>
                                    <td>{{ $form->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-nowrap">
                                        <!-- Preview Button -->
                                        <a href="{{ url('/form/' . $form->slug) }}" class="btn btn-light btn-sm border"
                                            target="_blank" title="Preview" aria-label="Preview">
                                            <i class="fas fa-eye text-muted"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.forms.edit', $form->id) }}"
                                            class="btn btn-light btn-sm border" title="Edit" aria-label="Edit">
                                            <i class="fas fa-pen text-muted"></i>
                                        </a>

                                        <!-- Responses Button -->
                                        <a href="{{ route('admin.forms.responses', $form->id) }}"
                                            class="btn btn-light btn-sm border" title="Lihat Response"
                                            aria-label="Responses">
                                            <i class="fas fa-list text-muted"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-light btn-sm border btn-delete-form"
                                            data-form-id="{{ $form->id }}" data-form-title="{{ $form->title }}"
                                            title="Hapus" aria-label="Hapus">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No data"
                                                style="max-width:240px; opacity: 0.5;">
                                            <p>Data Tidak Ada</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <div class="d-flex fs-14px align-items-center gap-1">
                        Total: <strong>{{ $forms->count() ?? 0 }}</strong> Form
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Delete Form Modal --}}
    <div class="modal fade" id="deleteFormModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="deleteFormForm" class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Konfirmasi Hapus Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Apakah Anda yakin ingin menghapus form <strong id="formTitleLabel"></strong>?
                    </p>
                    <p class="text-muted small mt-2">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        Semua response dan data terkait akan dihapus secara permanen.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.btn-delete-form').forEach(btn => {
                btn.addEventListener('click', function() {
                    const formId = this.dataset.formId;
                    const formTitle = this.dataset.formTitle;

                    document.getElementById('formTitleLabel').textContent = '"' + formTitle + '"';
                    document.getElementById('deleteFormForm').action =
                        '{{ route('admin.forms.destroy', ':id') }}'.replace(':id', formId);

                    new bootstrap.Modal(document.getElementById('deleteFormModal')).show();
                });
            });
        </script>
    @endpush
@endsection
