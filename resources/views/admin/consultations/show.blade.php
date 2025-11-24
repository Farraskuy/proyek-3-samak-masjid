@extends('admin.layout')

@section('title', 'Detail Konsultasi')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary btn-sm mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h1 class="h3 mb-0 text-gray-800">Detail Konsultasi</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @include('admin.consultations.show_partial', [
                    'consultation' => $consultation,
                    'messages' => $messages,
                ])
            </div>
        </div>
    </div>
@endsection
