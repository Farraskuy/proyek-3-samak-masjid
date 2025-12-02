@extends('admin.layout')

@section('title', 'Manajemen Role')

@section('content')
    <section class="p-3">
        {{-- Header & Tombol Tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Manajemen Role</h4>
            @can('create_roles')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Role
                </a>
            @endcan
        </div>
        

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <div class="alert-container"></div>

                {{-- Toolbar Pencarian --}}
                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control" placeholder="Cari Role..."
                            value="{{ request()->query('keyword', '') }}" name="keyword">

                        {{-- Sort Toggle (Optional, kept simple for now) --}}
                        {{-- <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                            <option value="">Urutkan berdasarkan</option>
                        </select> --}}
                    </div>
                </div>

                {{-- Tabel Data --}}
                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Role</th>
                                <th>Alias</th>
                                <th>Deskripsi</th>
                                <th>Jumlah User</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($roles ?? collect()) as $index => $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->alias }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-secondary">
                                            {{ $role->users_count }} User
                                        </span>
                                    </td>
                                    <td class="text-nowrap text-end">
                                        @can('edit_roles')
                                            <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                class="btn btn-light btn-sm border" aria-label="Edit">
                                                <i class="fas fa-pen text-muted"></i>
                                            </a>
                                        @endcan

                                        @can('delete_roles')
                                            @if ($role->users_count == 0)
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled title="Role sedang digunakan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
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

                {{-- Pagination (If applicable, though roles usually aren't paginated heavily, but good to have structure) --}}
                {{-- Assuming $roles might be a collection or paginator. If collection, links() won't work. --}}
                {{-- For now, we'll just keep the structure simple as roles are usually few. --}}

            </form>
        </div>
    </section>

    @push('styles')
        <style>
            .fs-14px {
                font-size: 14px;
            }
        </style>
    @endpush
@endsection
