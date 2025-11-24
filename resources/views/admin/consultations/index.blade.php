@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">Support Requests</h1>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar List -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Konsultasi</h6>
                        <div class="dropdown no-arrow">
                            <button class="btn btn-link btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item"
                                    href="{{ route('admin.consultations.index', ['status' => 'all']) }}">Semua</a>
                                <a class="dropdown-item"
                                    href="{{ route('admin.consultations.index', ['status' => 'pending']) }}">Pending</a>
                                <a class="dropdown-item"
                                    href="{{ route('admin.consultations.index', ['status' => 'active']) }}">Aktif</a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($consultations as $item)
                            <a href="{{ route('admin.consultations.show', $item->id) }}"
                                class="list-group-item list-group-item-action {{ request()->route('id') == $item->id ? 'active' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">{{ $item->user->full_name ?? 'Hamba Allah' }}</h6>
                                    <small>{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-truncate">{{ $item->question_subject }}</p>
                                <small>
                                    <span
                                        class="badge badge-{{ $item->status == 'active' ? 'success' : ($item->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </small>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        {{ $consultations->links() }}
                    </div>
                </div>
            </div>

            <!-- Detail / Chat Area -->
            <div class="col-md-8">
                @if (isset($consultation))
                    @include('admin.consultations.show_partial', [
                        'consultation' => $consultation,
                        'messages' => $messages,
                    ])
                @else
                    <div class="card shadow mb-4 h-100 d-flex align-items-center justify-content-center"
                        style="min-height: 500px;">
                        <div class="text-center text-muted">
                            <i class="fas fa-comments fa-4x mb-3"></i>
                            <h5>Pilih konsultasi untuk melihat detail</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
