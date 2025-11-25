@extends('admin.layout')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Kelola Rekening Bank</h4>
            <a href="{{ route('admin.banks.create') }}" class="btn btn-success fw-semibold">
                <i class="fas fa-plus me-1"></i> Tambah Rekening
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="d-flex gap-2 justify-content-end mb-2">
                    <input type="text" class="form-control" placeholder="Cari Bank / Pemilik..."
                        value="{{ request()->query('keyword', '') }}" name="keyword">

                    {{-- Sort Toggle --}}
                    <div class="sort-toggle">
                        <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc"
                            {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()" hidden>
                        <label for="ordered_by_asc" class="btn btn-outline-secondary" title="Urutkan A-Z">
                            <i class="fas fa-sort-alpha-down"></i>
                        </label>

                        <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc"
                            {{ request('ordered_by', 'desc') == 'desc' ? 'checked' : '' }} onchange="this.form.submit()"
                            hidden>
                        <label for="ordered_by_desc" class="btn btn-outline-secondary" title="Urutkan Z-A">
                            <i class="fas fa-sort-alpha-up"></i>
                        </label>
                    </div>
                </div>
                <div class="table-responsive position-relative" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px m-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-3">Logo</th>
                                <th class="p-3">Bank</th>
                                <th class="p-3">No. Rekening</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $bank)
                                <tr>
                                    <td class="p-3 align-middle"><img src="{{ asset($bank->logo_url) }}" width="40"
                                            class="rounded"></td>
                                    <td class="p-3 align-middle">
                                        <div class="fw-semibold">{{ $bank->bank_name }}</div>
                                        <small class="text-muted">{{ $bank->account_holder_name }}</small>
                                    </td>
                                    <td class="p-3 align-middle font-monospace">{{ $bank->account_number }}</td>
                                    <td class="p-3 align-middle"><span
                                            class="badge bg-info text-dark bg-opacity-10 border border-info">{{ strtoupper($bank->category) }}</span>
                                    </td>
                                    <td class="p-3 align-middle">
                                        @if ($bank->is_active)
                                            <span class="badge rounded-pill text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="p-3 align-middle">
                                        <a href="{{ route('admin.banks.edit', $bank->account_id) }}"
                                            class="btn btn-sm btn-light border"><i class="fas fa-pen text-muted"></i></a>
                                        <form action="{{ route('admin.banks.destroy', $bank->account_id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-light border text-danger"
                                                onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
