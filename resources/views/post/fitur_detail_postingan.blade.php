@extends('client.layout')

@section('title', $title ?? 'Detail Post')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
    * {
        font-family: 'Poppins', "Lexend", sans-serif;
    }

    /* Container luar: sesuai wireframe */
    .content-wrapper {
        width: 100%;
        background: #fef8f8ff;
        padding: 1%;
        min-height: 10vh;
        border-radius: 20px;
        margin-bottom: 10%;
    }

    /* Tombol back */
    .btn-back {
        background: white;
        color: black;
        border: 1px solid #ccc;
        padding: 6px 18px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #f3f3f3;
    }

    /* Wrapper untuk tombol supaya posisi sama seperti wireframe */
    .back-container {
        margin-bottom: 20px;
        margin-top: 10px;
    }

    /* Styling konten Quill */
    .ql-editor {
        background: transparent !important;
        font-size: 1rem;
        color: #222;
    }

    .ql-editor img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        margin: 15px 0;
    }
</style>
@endpush

@section('content')

<div class="container mt-4">

    {{-- Tombol Back --}}
    <div class="back-container">
        <a href="{{ url('/postingan') }}" class="btn-back">BACK</a>
    </div>

    {{-- Container Konten --}}
    <div class="content-wrapper">
        <div class="ql-editor">
            {!! $data_posts !!}
        </div>
    </div>

</div>

@endsection
