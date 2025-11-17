@extends('admin.layout')

@section('title', $title ?? 'Admin')

@section('content')
    <section class="p-3">
        <h4 class="fw-semibold">{{ $title ?? 'Data' }}</h4>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter" style="height: fit-content">
                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1000">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari" value="{{ request()->query('keyword', '') }}" name="keyword">
                        <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                            <option value="">Urutkan berdasarkan</option>
                        </select>
                        <div class="btn-group" role="group" aria-label="Order">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('ordered_by_asc').checked = true; this.form.submit();">Asc</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('ordered_by_desc').checked = true; this.form.submit();">Desc</button>
                        </div>
                        <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc" hidden>
                        <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc" hidden checked>
                    </div>
                </div>

                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                @php
                                    $cols = $columns ?? ['#', 'Nama', 'Keterangan', 'Aksi'];
                                @endphp
                                @foreach($cols as $col)
                                    <th scope="col">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    @if(is_object($row) || is_array($row))
                                        @foreach(array_slice($cols, 1) as $c)
                                            @php
                                                $key = strtolower(str_replace(' ', '_', $c));
                                            @endphp
                                            <td>{{ data_get($row, $key) ?? '-' }}</td>
                                        @endforeach
                                    @else
                                        <td colspan="{{ max(1, count($cols)-1) }}">{{ $row }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($cols) }}" class="text-center">Tidak ada data untuk ditampilkan.</td>
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
                                <option {{ request()->query('showing',50) == 10 ? 'selected' : '' }}>10</option>
                                <option {{ request()->query('showing',50) == 20 ? 'selected' : '' }}>20</option>
                                <option {{ request()->query('showing',50) == 50 ? 'selected' : '' }}>50</option>
                                <option {{ request()->query('showing',50) == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                        @if(isset($data) && method_exists($data, 'links'))
                            {{ $data->onEachSide(1)->links() }}
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
