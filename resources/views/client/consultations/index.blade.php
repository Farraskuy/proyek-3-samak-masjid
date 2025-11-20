@extends('layouts.app')

@section('title', 'Riwayat Konsultasi Saya')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Riwayat Konsultasi Saya</h2>
                    <a href="{{ route('client.consultations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Konsultasi Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card border-left-primary shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Konsultasi</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <i class="fas fa-comments fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Jawaban</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Dijawab</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['answered'] }}</div>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Selesai</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['closed'] }}</div>
                        </div>
                        <i class="fas fa-lock fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="keyword" placeholder="Cari konsultasi..."
                            value="{{ $keyword }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="all" @if ($status === 'all') selected @endif>Semua Status</option>
                            <option value="pending" @if ($status === 'pending') selected @endif>Menunggu Jawaban</option>
                            <option value="answered" @if ($status === 'answered') selected @endif>Sudah Dijawab</option>
                            <option value="closed" @if ($status === 'closed') selected @endif>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Consultations List -->
        <div class="row">
            @forelse($consultations as $consultation)
                <div class="col-12 mb-3">
                    <div class="card shadow-sm hover-shadow-lg transition">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="card-title fw-bold mb-2">
                                        {{ $consultation->question_subject }}
                                    </h5>
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit($consultation->question_text, 100) }}
                                    </p>
                                    <small class="text-secondary">
                                        <i class="fas fa-calendar"></i>
                                        {{ $consultation->created_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end align-items-center gap-2">
                                    <div class="text-end">
                                        @if ($consultation->status === 'pending')
                                            <span class="badge bg-warning mb-2 d-block">
                                                <i class="fas fa-clock"></i> Menunggu Jawaban
                                            </span>
                                        @elseif($consultation->status === 'answered')
                                            <span class="badge bg-success mb-2 d-block">
                                                <i class="fas fa-check"></i> Sudah Dijawab
                                            </span>
                                        @elseif($consultation->status === 'closed')
                                            <span class="badge bg-info mb-2 d-block">
                                                <i class="fas fa-lock"></i> Selesai
                                            </span>
                                        @elseif($consultation->status === 'rejected')
                                            <span class="badge bg-danger mb-2 d-block">
                                                <i class="fas fa-ban"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('client.consultations.show', $consultation->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada konsultasi. <a href="{{ route('client.consultations.create') }}">Buat konsultasi baru</a></p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($consultations->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    {{ $consultations->links() }}
                </ul>
            </nav>
        @endif
    </div>
@endsection
