@extends('admin.layout')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Kelola Rekening Bank</h4>
            
            @can('create_banks')
                <a href="{{ route('admin.banks.create') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Rekening
                </a>
            @endcan
        </div>

        {{-- Quick Filter --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="?status=all"
                class="btn btn-sm {{ request('status', 'all') == 'all' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Semua
            </a>
            <a href="?status=active"
                class="btn btn-sm {{ request('status') == 'active' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Aktif
            </a>
            <a href="?status=inactive"
                class="btn btn-sm {{ request('status') == 'inactive' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Non-Aktif
            </a>
        </div>

        <div class="row g-0 gap-3">
            <form method="get" class="col rounded-3 bg-white p-3 pt-0 form-filter" style="height: fit-content">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 10">
                    <div class="d-flex gap-2 justify-content-end mb-2 align-items-center">
                        <input type="text" class="form-control" placeholder="Cari Bank / Pemilik..."
                            value="{{ request('keyword') }}" name="keyword">

                        <select name="sort_by" class="form-select fs-14px h-100 w-auto" style="line-height: 1.7"
                            onchange="this.form.submit()">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru
                            </option>
                            <option value="bank_name" {{ request('sort_by') == 'bank_name' ? 'selected' : '' }}>Nama Bank
                            </option>
                            <option value="account_holder_name"
                                {{ request('sort_by') == 'account_holder_name' ? 'selected' : '' }}>Pemilik</option>
                        </select>

                        <div class="sort-toggle">
                            <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc"
                                {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()" hidden>
                            <label for="ordered_by_asc" class="btn btn-outline-secondary" title="Urutkan A-Z">
                                <i class="fas fa-sort-alpha-down"></i>
                            </label>

                            <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc"
                                {{ request('ordered_by', 'desc') == 'desc' ? 'checked' : '' }}
                                onchange="this.form.submit()" hidden>
                            <label for="ordered_by_desc" class="btn btn-outline-secondary" title="Urutkan Z-A">
                                <i class="fas fa-sort-alpha-up"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px align-middle">
                        <thead>
                            <tr>
                                <th class="p-3 fw-semibold">Logo</th>
                                <th class="p-3 fw-semibold">Bank</th>
                                <th class="p-3 fw-semibold">No. Rekening</th>
                                <th class="p-3 fw-semibold">Saldo</th>
                                <th class="p-3 fw-semibold">Kategori</th>
                                <th class="p-3 fw-semibold">Status</th>
                                
                                @canany(['edit_banks', 'delete_banks'])
                                    <th class="p-3 fw-semibold">Aksi</th>
                                @endcanany
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
                                    <td class="p-3 align-middle fw-semibold">{{ $bank->formatted_balance }}</td>
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
                                    
                                    {{-- KOLOM AKSI --}}
                                    @canany(['edit_banks', 'delete_banks'])
                                    <td class="p-3 align-middle">
                                        @can('edit_banks')
                                            <a href="{{ route('admin.banks.edit', $bank->account_id) }}"
                                                class="btn btn-sm btn-light border"><i class="fas fa-pen text-muted"></i></a>
                                        @endcan

                                        @can('delete_banks')
                                            <button type="button"
                                                class="btn btn-sm btn-light border text-danger btn-delete-article"
                                                data-action="{{ route('admin.banks.destroy', $bank->account_id) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <div class="d-flex justify-content-between showing-wrapper-bawah">
                        <div class="d-flex fs-14px align-items-center gap-1">
                            Menampilkan
                            <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                                <option value="all" selected>Semua</option>
                            </select>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection