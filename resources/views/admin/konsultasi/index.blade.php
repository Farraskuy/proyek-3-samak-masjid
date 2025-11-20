@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')

@push('styles')
<style>
    .section-wrapper {
        max-width: 1450px;
        margin: 0 auto;
    }

    .card-modern {
        background: #fff;
        border: 0 !important;
        border-radius: 1rem !important;
        padding: 1.5rem !important;
    }

    .input-lg {
        padding: .75rem 1rem !important;
        font-size: .95rem !important;
        border-radius: .65rem !important;
    }

    .btn-main {
        background-color: #CE9138 !important;
        color: #fff !important;
        border: none !important;
        font-weight: 600;
        border-radius: .75rem !important;
        padding: .65rem 1rem !important;
    }

    .btn-main:hover {
        background-color: #b88027 !important;
    }

    /* Sidebar list styling */
    .list-modern a {
        border-radius: .75rem !important;
        padding: .75rem 1rem !important;
        margin-bottom: .25rem;
        border: 1px solid #f1f1f1 !important;
        transition: .15s;
    }

    .list-modern a:hover {
        background: #fdf8f0 !important;
        border-color: #CE9138 !important;
    }
</style>
@endpush

@section('content')
<section class="p-3 section-wrapper">

    <div class="row g-4">

        <!-- ========= LEFT SIDE ========= -->
        <div class="col-lg-8">
            <div class="card-modern">

                <h4 class="fw-semibold mb-4">Daftar Konsultasi</h4>

                <!-- Search & Filter -->
                <form method="get" class="row g-2 mb-4">
                    <div class="col-md-6">
                        <input type="text" class="form-control input-lg" placeholder="Cari pertanyaan..."
                            value="{{ request()->query('keyword', '') }}" name="keyword">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select input-lg" name="status" onchange="this.form.submit()">
                            <option value="all" @if(request()->query('status','all')=='all') selected @endif>Semua Status</option>
                            <option value="pending" @if(request()->query('status')=='pending') selected @endif>Pending</option>
                            <option value="in_progress" @if(request()->query('status')=='in_progress') selected @endif>Sedang Diproses</option>
                            <option value="answered" @if(request()->query('status')=='answered') selected @endif>Sudah Dijawab</option>
                            <option value="closed" @if(request()->query('status')=='closed') selected @endif>Selesai</option>
                            <option value="rejected" @if(request()->query('status')=='rejected') selected @endif>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-main w-100">Cari</button>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                        @if($consultation->is_anonymous)
                                            <span class="badge bg-warning">Anonim</span>
                                        @else
                                            <small>{{ $consultation->question_from }}</small>
                                        @endif
                                    </td>
                                    <td><small>{{ Str::limit($consultation->question_subject, 35) }}</small></td>

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
                                        <a href="{{ url("admin/konsultasi/{$consultation->id}") }}"
                                            class="btn btn-sm btn-light border rounded-pill">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Tidak ada konsultasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($data, 'links'))
                <div class="mt-3">
                    {{ $data->links() }}
                </div>
                @endif

            </div>
        </div>

        <!-- ========= RIGHT SIDE ========= -->
        <div class="col-lg-4">
            <div class="card-modern" style="max-height: 80vh; overflow-y: auto;">

                <h5 class="fw-semibold mb-3">
                    <i class="fas fa-list me-2"></i> Daftar Konsultasi
                </h5>

                <!-- Tabs -->
                <ul class="nav nav-tabs small mb-3">
                    <li class="nav-item">
                        <a class="nav-link @if(!request()->has('status') || request()->query('status')==='all') active @endif"
                            href="?status=all">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->query('status')==='pending') active @endif"
                            href="?status=pending">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->query('status')==='answered') active @endif"
                            href="?status=answered">Dijawab</a>
                    </li>
                </ul>

                <!-- Modern List -->
                <div class="list-group list-group-flush list-modern">
                    @forelse($consultations as $cons)
                        <a href="{{ url("admin/konsultasi/{$cons->id}") }}" class="list-group-item list-group-item-action">

                            <div class="fw-semibold small text-truncate">{{ $cons->question_subject }}</div>

                            <div class="text-muted small">
                                {{ $cons->is_anonymous ? 'Anonim' : $cons->question_from }}
                            </div>

                            <div class="mt-1">
                                @if ($cons->status === 'pending')
                                    <span class="badge bg-danger">Pending</span>
                                @elseif($cons->status === 'answered')
                                    <span class="badge bg-success">Dijawab</span>
                                @elseif($cons->status === 'closed')
                                    <span class="badge bg-secondary">Selesai</span>
                                @endif
                            </div>

                        </a>
                    @empty
                        <p class="text-muted small text-center py-3">Tidak ada konsultasi</p>
                    @endforelse
                </div>

            </div>
        </div>

    </div>

</section>
@endsection
