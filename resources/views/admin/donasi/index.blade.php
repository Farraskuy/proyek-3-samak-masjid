@extends('admin.layout')

@section('title', 'Verifikasi Donasi')

@section('content')
    @php
        $columns = ['#', 'Nama Pengirim', 'Jumlah', 'Tanggal', 'Bukti', 'Status', 'Aksi'];
    @endphp

    <section class="p-3">
        <h4 class="fw-semibold">Verifikasi Donasi</h4>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari"
                            value="{{ request()->query('keyword', '') }}" name="keyword">
                        <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                            <option value="">Urutkan berdasarkan</option>
                        </select>
                        <div class="btn-group" role="group" aria-label="Order">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('ordered_by_asc').checked = true; this.form.submit();">Asc</button>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('ordered_by_desc').checked = true; this.form.submit();">Desc</button>
                        </div>
                        <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc" hidden>
                        <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc" hidden checked>
                    </div>
                </div>

                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Pengirim</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ optional($row->user)->full_name ?? '#' . ($row->user_id ?? '-') }}</td>
                                    <td>{{ isset($row->amount) ? number_format($row->amount, 0, ',', '.') : '-' }}</td>
                                    <td>{{ $row->transfer_date ?? ($row->created_at ?? '-') }}</td>
                                    <td>
                                        @if (!empty($row->proof_image_url))
                                            <a href="{{ asset('storage/' . ltrim($row->proof_image_url, '/')) }}"
                                                target="_blank">Lihat Bukti</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $row->status ?? '-' }}</td>
                                    <td>-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <img src="{{ asset('assets/images/no-data.png') }}"" alt="No data"
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
                    <div class="d-flex justify-content-between showing-wrapper-bawah">
                        <div class="d-flex fs-14px align-items-center gap-1">
                            Menampilkan
                            <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                                <option {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                                <option {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                                <option {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50</option>
                                <option {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>Semua
                                </option>
                            </select>
                            Data
                        </div>
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
@endsection
