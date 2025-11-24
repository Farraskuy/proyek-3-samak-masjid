@extends('client.profile.layout')

@section('profile-content')
    <div class="card settings-card border-0 bg-white">
        <div class="card-body p-5">
            <div class="d-flex align-items-center mb-5">
                <i class="fa-regular fa-clock-rotate-left fs-4 me-3 text-dark"></i>
                <h5 class="fw-bold mb-0 text-dark">Riwayat Konsultasi</h5>
            </div>

            @if ($consultations->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa-regular fa-comments fa-3x text-muted opacity-50"></i>
                    </div>
                    <p class="text-muted mb-4">Belum ada riwayat konsultasi.</p>
                    <a href="{{ route('client.consultations.create') }}" class="btn btn-primary px-4 py-2 fw-medium">
                        <i class="fa-solid fa-plus me-2"></i>Buat Konsultasi Baru
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="py-3 ps-4 border-0 rounded-start">Topik</th>
                                <th scope="col" class="py-3 border-0">Status</th>
                                <th scope="col" class="py-3 border-0">Tanggal</th>
                                <th scope="col" class="py-3 pe-4 border-0 rounded-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultations as $consultation)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $consultation->question_subject }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 200px;">
                                            {{ $consultation->question_text }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if ($consultation->status == 'active')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Aktif</span>
                                        @elseif($consultation->status == 'pending')
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Menunggu</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ $consultation->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="{{ route('client.consultations.show', $consultation->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $consultations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
