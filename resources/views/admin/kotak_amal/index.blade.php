@extends('admin.layout')

@section('title', 'Pendataan Kotak Amal')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Pendataan Kotak Amal</h4>
            <a href="{{ route('admin.kotak-amal.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Input Data Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive bg-white rounded-3 p-3 shadow-sm">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Identitas Kotak</th>
                        <th>Total Uang</th>
                        <th>Petugas</th>
                        <th width="100" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $index => $collection)
                        <tr>
                            <td>{{ $collections->firstItem() + $index }}</td>
                            <td>{{ $collection->collection_date->translatedFormat('d F Y') }}</td>
                            <td class="fw-bold">{{ $collection->box_name }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($collection->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-group">
                                        @foreach ($collection->officers->take(3) as $officer)
                                            <span class="badge bg-light text-dark border me-1" title="{{ $officer->name }}">
                                                {{ Str::limit($officer->name, 10) }}
                                            </span>
                                        @endforeach
                                        @if ($collection->officers->count() > 3)
                                            <span
                                                class="badge bg-secondary">+{{ $collection->officers->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada data pembukaan kotak amal.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $collections->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
@endsection
