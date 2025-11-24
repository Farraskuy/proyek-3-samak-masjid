@extends('admin.layout')

@section('title', 'Kegiatan')

@section('content')
@php
    $columns = ['#', 'Judul', 'Lokasi', 'Tanggal', 'Aksi'];
@endphp

<section class="p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">Kegiatan</h4>
        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kegiatan
        </a>
    </div>

    <div class="row g-0 gap-3">
        <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
            style="height: fit-content">
            <div class="alert-container"></div>

            <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                <div class="d-flex gap-2 justify-content-end mb-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari"
                        value="{{ request()->query('keyword', '') }}" name="keyword">

                    <select class="form-select fs-14px h-100 w-auto" name="sorted_by">
                        <option value="">Urutkan berdasarkan</option>
                    </select>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="document.getElementById('ordered_by_asc').checked = true; this.form.submit();">Asc</button>
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="document.getElementById('ordered_by_desc').checked = true; this.form.submit();">Desc</button>
                    </div>
                    <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc" hidden>
                    <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc" hidden checked>
                </div>
            </div>

            <div class="table-responsive mb-3" style="min-height: 200px">
                <table class="table table-sm table-hover fs-14px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data ?? collect()) as $index => $row)
                            <tr>
                                <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                <td>{{ $row->event_name }}</td>
                                <td>{{ $row->location }}</td>
                                <td>{{ $row->start_time }}</td>
                                <td>
                                    <a href="{{ route('admin.kegiatan.edit', $row->event_id) }}"
                                        class="btn btn-warning btn-sm me-1">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>

                                    <button type="button" onclick="hapusKegiatan('{{ $row->event_id }}')"
        class="btn btn-danger btn-sm">
    <i class="fa-solid fa-trash"></i>
</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="py-4">
                                        <img src="{{ asset('assets/images/no-data.png') }}"
                                             style="max-width:240px;opacity:0.5;">
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
                    Menampilkan
                    <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                        <option {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                        <option {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                        <option {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50</option>
                        <option {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    Data
                </div>

                <div class="paginate">
                    @if (isset($data) && method_exists($data, 'links'))
                        {{ $data->onEachSide(1)->links() }}
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function hapusKegiatan(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Kegiatan ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/jadwal-kegiatan/delete/${id}`;
            form.style.display = 'none';

            const methodInput = document.createElement('input');
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            const tokenInput = document.createElement('input');
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

@endsection
