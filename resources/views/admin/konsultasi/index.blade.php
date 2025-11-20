@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')

@section('content')
    <section class="p-3">
        <div class="row g-3">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="bg-white rounded-3 p-4">
                    <h4 class="fw-semibold mb-4">Daftar Konsultasi</h4>

                    <!-- Search and Filter -->
                    <div class="mb-4">
                        <form method="get" class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-sm" placeholder="Cari pertanyaan..."
                                    value="{{ request()->query('keyword', '') }}" name="keyword">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                    <option value="all" @if (request()->query('status', 'all') === 'all') selected @endif>Semua Status
                                    </option>
                                    <option value="pending" @if (request()->query('status') === 'pending') selected @endif>Pending
                                    </option>
                                    <option value="in_progress" @if (request()->query('status') === 'in_progress') selected @endif>Sedang
                                        Diproses</option>
                                    <option value="answered" @if (request()->query('status') === 'answered') selected @endif>Sudah Dijawab
                                    </option>
                                    <option value="closed" @if (request()->query('status') === 'closed') selected @endif>Selesai
                                    </option>
                                    <option value="rejected" @if (request()->query('status') === 'rejected') selected @endif>Ditolak
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Cari</button>
                            </div>
                        </form>
                    </div>

                    <!-- Consultation Table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Dari</th>
                                    <th>Pertanyaan</th>
                                    <th>Status</th>
                                    <th>Tgl Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $consultation)
                                    <tr>
                                        <td>
                                            @if ($consultation->is_anonymous)
                                                <span class="badge bg-warning">Anonim</span>
                                            @else
                                                <small>{{ $consultation->question_from }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($consultation->question_subject, 30) }}</small>
                                        </td>
                                        <td>
                                            @if ($consultation->status === 'pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif($consultation->status === 'in_progress')
                                                <span class="badge bg-info">Sedang Diproses</span>
                                            @elseif($consultation->status === 'answered')
                                                <span class="badge bg-success">Dijawab</span>
                                            @elseif($consultation->status === 'closed')
                                                <span class="badge bg-secondary">Selesai</span>
                                            @elseif($consultation->status === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $consultation->created_at->format('d M Y') }}</small></td>
                                        <td>
                                            <a href="{{ route('konsultasi.show', $consultation->id) }}"
                                                class="btn btn-sm btn-light border" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Tidak ada konsultasi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (method_exists($data, 'links'))
                        <div class="mt-3">
                            {{ $data->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Consultation List -->
            <div class="col-lg-4">
                <div class="bg-white rounded-3 p-4" style="max-height: 80vh; overflow-y: auto;">
                    <h5 class="fw-semibold mb-3">
                        <i class="fas fa-list me-2"></i> Daftar Konsultasi
                    </h5>

                    <!-- Tab Filter -->
                    <ul class="nav nav-tabs nav-fill mb-3 small" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link @if (!request()->has('status') || request()->query('status') === 'all') active @endif"
                                href="?status=all">Semua</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link @if (request()->query('status') === 'pending') active @endif"
                                href="?status=pending">Pending</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link @if (request()->query('status') === 'answered') active @endif"
                                href="?status=answered">Dijawab</a>
                        </li>
                    </ul>

                    <!-- Consultation Items -->
                    <div class="list-group list-group-flush">
                        @forelse($consultations as $cons)
                            <a href="{{ route('admin.konsultasi.show', $cons->id) }}"
                                class="list-group-item list-group-item-action py-2">
                                <small class="d-block text-truncate">{{ $cons->question_subject }}</small>
                                <small class="text-muted d-block">
                                    @if ($cons->is_anonymous)
                                        Anonim
                                    @else
                                        {{ $cons->question_from }}
                                    @endif
                                </small>
                                <span class="d-inline-block mt-1">
                                    @if ($cons->status === 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif($cons->status === 'answered')
                                        <span class="badge bg-success">Dijawab</span>
                                    @elseif($cons->status === 'closed')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </span>
                            </a>
                        @empty
                            <p class="text-muted small text-center py-3">Tidak ada konsultasi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @forelse(($data ?? collect()) as $index => $row)
        <tr>
            <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
            <td>{{ $row->question_from ?? '-' }}</td>
            <td>{{ $row->question_subject ?? '-' }}</td>
            <td>{{ $row->created_at ?? '-' }}</td>
            <td>{{ $row->status ?? '-' }}</td>
            <td>-</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">
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
