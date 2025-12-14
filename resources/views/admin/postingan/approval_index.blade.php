@extends('admin.layout')

@section('title', 'Approval Postingan')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Daftar Postingan ({{ ucfirst($status ?? 'pending') }})</h4>
        </div>

        {{-- Navigasi Tab Status --}}
        {{-- Kita gunakan request('keyword') di href agar saat ganti tab, kata kunci pencarian tetap terbawa (opsional, tapi bagus untuk UX) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="{{ route('admin.postingan.approval.index', ['status' => 'draft', 'keyword' => request('keyword')]) }}"
                class="btn btn-sm {{ ($status ?? 'pending') == 'draft' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Draft
            </a>

            <a href="{{ route('admin.postingan.approval.index', ['status' => 'revisi', 'keyword' => request('keyword')]) }}"
                class="btn btn-sm {{ ($status ?? 'pending') == 'revisi' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Revisi
            </a>

            <a href="{{ route('admin.postingan.approval.index', ['status' => 'pending', 'keyword' => request('keyword')]) }}"
                class="btn btn-sm {{ ($status ?? 'pending') == 'pending' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Menunggu Approval(pending)
            </a>

            <a href="{{ route('admin.postingan.approval.index', ['status' => 'published', 'keyword' => request('keyword')]) }}"
                class="btn btn-sm {{ ($status ?? 'pending') == 'published' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Publish
            </a>

            <a href="{{ route('admin.postingan.approval.index', ['status' => 'arsip', 'keyword' => request('keyword')]) }}"
                class="btn btn-sm {{ ($status ?? 'pending') == 'arsip' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Arsip
            </a>
        </div>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                
                {{-- PENTING: Input Hidden Status --}}
                {{-- Agar saat tombol cari diklik, status yang sedang aktif tetap terbawa --}}
                <input type="hidden" name="status" value="{{ $status ?? 'pending' }}">

                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control" placeholder="Cari Judul, Slug, dll..."
                            value="{{ request()->query('keyword', '') }}" name="keyword">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>

                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal dibuat</th>
                                <th>Tanggal diupdate</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ $row->title ?? '-' }}</td>
                                    <td>{{ $row->kategori ?? '-' }}</td>
                                    <td>{{ optional($row->creator)->full_name ?? ($row->user_id ?? '-') }}</td>
                                    <td>{{ $row->created_at->format('d M Y H:i') ?? '-' }}</td>
                                    <td>{{ $row->updated_at->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ url('/admin/postingan/approval/' . ($row->id ?? $row->id)) }}"
                                            class="btn btn-primary btn-sm">Lihat Postingan</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No data"
                                                style="max-width:240px; opacity: 0.5;">
                                            <p>Data Tidak Ditemukan</p>
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