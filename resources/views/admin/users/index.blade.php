@extends('admin.layout')

@section('title', 'Manajemen Pengguna')

@section('content')
    <section class="p-3">
        {{-- Header & Tombol Tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Manajemen Pengguna</h4>
            @can('edit_users')
                <a href="{{ route('admin.users.create') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Pengguna
                </a>
            @endcan
        </div>

        {{-- Filter Cepat (Quick Links) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="{{ route('admin.users.index', ['status' => 'non-jamaah']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'non-jamaah' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Pengguna Aplikasi
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'jamaah']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'jamaah' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Jamaah
            </a>
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
                        <input type="text" class="form-control" placeholder="Cari Pengguna..."
                            value="{{ request()->query('search', '') }}" name="search">
                    </div>
                </div>

                {{-- Tabel Data --}}
                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-secondary">
                                            {{ $user->role->alias ?? ($user->role->name ?? 'No Role') }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap text-end">
                                        @can('edit_users')
                                            @if (!$user->hasRole('Jamaah') && $user->id !== auth()->id())
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-light btn-sm border" aria-label="Edit">
                                                    <i class="fas fa-pen text-muted"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled title="Tidak dapat dihapus">
                                                    <i class="fas fa-pen text-muted"></i>
                                                </button>
                                            @endif
                                        @endcan

                                        @can('delete_users')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-article"
                                                data-action="{{ route('admin.users.destroy', $user->id) }}"
                                                aria-label="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

                {{-- Pagination --}}
                <div class="d-flex justify-content-between gap-2 flex-wrap fs-14px">
                    <div class="d-flex justify-content-between showing-wrapper-bawah">
                        <div class="d-flex fs-14px align-items-center gap-1">
                            Menampilkan
                            <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                                <option {{ request()->query('showing', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option {{ request()->query('showing', 10) == 20 ? 'selected' : '' }}>20</option>
                                <option {{ request()->query('showing', 10) == 50 ? 'selected' : '' }}>50</option>
                                <option {{ request()->query('showing', 10) == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>
                                    Semua
                                </option>
                            </select>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                        @if (isset($users) && method_exists($users, 'links'))
                            {{ $users->onEachSide(1)->links() }}
                        @endif
                    </div>
                </div>

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
