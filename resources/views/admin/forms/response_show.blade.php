@extends('admin.layout')

@section('title', 'Detail Respon')

@section('content')
    <div class="p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Detail Respon #{{ $response->id }}</h4>
                <p class="text-muted mb-0">Untuk Form: <strong>{{ $form->title }}</strong></p>
            </div>
            <a href="{{ route('admin.forms.responses', $form->id) }}"
                class="btn btn-light border bg-white text-dark fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Jawaban --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0">Jawaban Responden</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-4">
                            @foreach ($response->items as $item)
                                <div class="border-bottom pb-3 last-no-border">
                                    <label
                                        class="small text-muted text-uppercase fw-bold mb-1">{{ $item->field_label }}</label>
                                    <div class="fs-6 text-dark">
                                        @if (is_array(json_decode($item->value, true)))
                                            <ul class="mb-0 ps-3">
                                                @foreach (json_decode($item->value, true) as $val)
                                                    <li>{{ $val }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {!! nl2br(e($item->value)) !!}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Metadata --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0">Metadata</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold">Waktu Submit</label>
                            <div class="text-dark">{{ $response->created_at->format('d F Y, H:i:s') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold">IP Address</label>
                            <div class="text-dark font-monospace">{{ $response->ip_address }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted fw-bold">User Agent</label>
                            <div class="text-dark small text-break">{{ $response->user_agent }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
