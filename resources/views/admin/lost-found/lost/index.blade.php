@extends('admin.layout')
@section('title', 'Laporan Barang Hilang | SAMAK-Kampus')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Laporan Barang Hilang</h4>
        @can('create_lost_items')
            <a href="{{ route('admin.lost-items.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Laporan
            </a>
        @endcan
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi Hilang</th>
                    <th>Tanggal Hilang</th>
                    <th>Kadaluarsa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->location_lost ?? '-' }}</td>
                    <td>{{ $item->lost_at?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $item->expiry_date?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @can('edit_lost_items')
                            <a href="{{ route('admin.lost-items.edit', $item->id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan
                        @can('delete_lost_items')
                            <form action="{{ route('admin.lost-items.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Belum ada laporan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $items->links() }}
    </div>
</div>
@endsection
