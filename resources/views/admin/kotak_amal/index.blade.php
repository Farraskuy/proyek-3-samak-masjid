@extends('admin.layout')

@section('title', 'Pendataan Kotak Amal')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Pendataan Kotak Amal</h4>
            <a href="{{ route('admin.kotak-amal.create') }}" class="btn btn-success fw-bold">
                <i class="fas fa-plus me-1"></i> Input Data Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-0 gap-3">
            <form method="get" action="{{ route('admin.kotak-amal.index') }}"
                class="col rounded-3 bg-white p-3 pt-0 form-filter" style="height: fit-content">

                {{-- Toolbar --}}
                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 10">
                    <div class="d-flex gap-2 justify-content-end mb-2 align-items-center">
                        <input type="text" class="form-control" placeholder="Cari Kotak Amal..."
                            value="{{ request('keyword') }}" name="keyword">
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px align-middle">
                        <thead>
                            <tr>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Identitas Kotak</th>
                                <th class="p-3">Total Uang</th>
                                <th class="p-3">Petugas</th>
                                <th class="p-3 text-end" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($collections as $collection)
                                <tr>
                                    <td class="p-3 align-middle" style="white-space: nowrap;">
                                        {{ $collection->collection_date->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="p-3 align-middle fw-bold">{{ $collection->box_name }}</td>
                                    <td class="p-3 align-middle fw-bold text-success" style="white-space: nowrap;">
                                        Rp {{ number_format($collection->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-group">
                                                @foreach (collect($collection->officers)->take(3) as $officer)
                                                    <span class="badge bg-light text-dark border me-1"
                                                        title="{{ $officer['name'] ?? $officer->name }}">
                                                        {{ Str::limit($officer['name'] ?? $officer->name, 10) }}
                                                    </span>
                                                @endforeach
                                                @if (count($collection->officers) > 3)
                                                    <span
                                                        class="badge bg-secondary">+{{ count($collection->officers) - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 align-middle text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.kotak-amal.show', $collection->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Lihat Laporan">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-article"
                                                title="Hapus"
                                                data-action="{{ route('admin.kotak-amal.destroy', $collection->id) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pembukaan kotak
                                        amal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <div class="d-flex justify-content-between showing-wrapper-bawah">
                        <div class="d-flex fs-14px align-items-center gap-1">
                            Menampilkan
                            <div class="fw-bold">{{ $collections->count() }}</div>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                        {{ $collections->links() }}
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
