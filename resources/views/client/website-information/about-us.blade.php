@extends('client.layout')

@section('title', $page->title)

@section('content')
    <div class="min-vh-100 d-flex flex-column">
        <!-- Hero Section -->
        <section class="py-5 bg-pattern position-relative"
            style="background-color: #175C9E; height: 400px; display:flex; align-items:center; overflow: hidden;">
            @if ($page->featured_image_url)
                <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
                    <img src="{{ asset('storage/' . $page->featured_image_url) }}" alt="{{ $page->title }}"
                        class="w-100 h-100" style="object-fit: cover;">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(23, 92, 158, 0.85);">
                    </div>
                </div>
            @endif

            <div class="container text-center position-relative" style="z-index: 1;">
                <h1 class="display-4 fw-bold text-white mb-3">
                    {{ $page->title }}
                </h1>
                @if ($page->description)
                    <p class="text-white-50 lead mx-auto" style="max-width: 700px;">
                        {{ $page->description }}
                    </p>
                @endif
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-5 flex-grow-1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="prose prose-lg">
                            {!! $content_html !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .prose {
            font-size: 1.1rem;
            line-height: 1.75;
            color: #333;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            font-weight: 600;
            color: #000;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5em 0;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1em;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }

        .prose a:hover {
            text-decoration: underline;
        }

        .prose blockquote {
            border-left: 4px solid #0066cc;
            padding-left: 1.5em;
            margin: 1.5em 0;
            color: #666;
            font-style: italic;
        }
    </style>
@endsection
