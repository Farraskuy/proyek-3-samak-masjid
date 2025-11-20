@extends('client.layout')

@section('title', $form->title)

@section('content')
    <div class="min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Form Header -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="card-title fw-bold mb-2">{{ $form->title }}</h2>
                            @if($form->description)
                                <p class="card-text text-muted mb-0">{{ $form->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('form.submit', $form->slug) }}" class="card border-0 shadow-sm">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading fw-semibold">
                                    <i class="fas fa-exclamation-circle me-2"></i> Validasi Gagal
                                </h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card-body p-4">
                            <!-- Form Fields -->
                            @forelse($form->fields->sortBy('order') as $field)
                                <div class="mb-4">
                                    <!-- Label -->
                                    <label for="field_{{ $field->id }}" class="form-label fw-semibold">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    <!-- Text Input -->
                                    @if($field->type === 'text')
                                        <input type="text" class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            placeholder="{{ $field->placeholder }}"
                                            value="{{ old($field->name) }}"
                                            @if($field->is_required) required @endif>
                                    @endif

                                    <!-- Email Input -->
                                    @if($field->type === 'email')
                                        <input type="email" class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            placeholder="{{ $field->placeholder }}"
                                            value="{{ old($field->name) }}"
                                            @if($field->is_required) required @endif>
                                    @endif

                                    <!-- Number Input -->
                                    @if($field->type === 'number')
                                        <input type="number" class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            placeholder="{{ $field->placeholder }}"
                                            value="{{ old($field->name) }}"
                                            @if($field->is_required) required @endif>
                                    @endif

                                    <!-- Phone Input -->
                                    @if($field->type === 'tel')
                                        <input type="tel" class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            placeholder="{{ $field->placeholder }}"
                                            value="{{ old($field->name) }}"
                                            @if($field->is_required) required @endif>
                                    @endif

                                    <!-- Textarea -->
                                    @if($field->type === 'textarea')
                                        <textarea class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            placeholder="{{ $field->placeholder }}" rows="5"
                                            @if($field->is_required) required @endif>{{ old($field->name) }}</textarea>
                                    @endif

                                    <!-- Select Dropdown -->
                                    @if($field->type === 'select' && is_array($field->options))
                                        <select class="form-select @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            @if($field->is_required) required @endif>
                                            <option value="">-- Pilih {{ $field->label }} --</option>
                                            @foreach($field->options as $option)
                                                <option value="{{ $option }}" @if(old($field->name) === $option) selected @endif>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <!-- Radio Buttons -->
                                    @if($field->type === 'radio' && is_array($field->options))
                                        <div class="d-flex gap-3 flex-wrap mt-2">
                                            @foreach($field->options as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input @error($field->name) is-invalid @enderror"
                                                        type="radio" id="field_{{ $field->id }}_{{ $loop->index }}"
                                                        name="{{ $field->name }}" value="{{ $option }}"
                                                        @if(old($field->name) === $option) checked @endif
                                                        @if($field->is_required) required @endif>
                                                    <label class="form-check-label" for="field_{{ $field->id }}_{{ $loop->index }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Checkbox -->
                                    @if($field->type === 'checkbox' && is_array($field->options))
                                        <div class="d-flex gap-3 flex-wrap mt-2">
                                            @foreach($field->options as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input @error($field->name) is-invalid @enderror"
                                                        type="checkbox" id="field_{{ $field->id }}_{{ $loop->index }}"
                                                        name="{{ $field->name }}[]" value="{{ $option }}"
                                                        @if(is_array(old($field->name)) && in_array($option, old($field->name))) checked @endif>
                                                    <label class="form-check-label" for="field_{{ $field->id }}_{{ $loop->index }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Date Input -->
                                    @if($field->type === 'date')
                                        <input type="date" class="form-control @error($field->name) is-invalid @enderror"
                                            id="field_{{ $field->id }}" name="{{ $field->name }}"
                                            value="{{ old($field->name) }}"
                                            @if($field->is_required) required @endif>
                                    @endif

                                    <!-- Error Message -->
                                    @error($field->name)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @empty
                                <p class="text-muted">Form ini belum memiliki field.</p>
                            @endforelse
                        </div>

                        <!-- Form Footer -->
                        <div class="card-footer bg-light p-4">
                            <div class="d-flex gap-2 justify-content-between">
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary fw-semibold">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="fas fa-paper-plane"></i> Kirim Formulir
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
        }
    </style>
@endsection
