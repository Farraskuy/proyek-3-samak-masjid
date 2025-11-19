<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $form->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1>{{ $form->title }}</h1>
        <p>{{ $form->description }}</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ url('/form/'.$form->slug) }}">
            @csrf
            @foreach($form->fields as $field)
                <div class="mb-3">
                    <label class="form-label">{{ $field->label }} @if($field->is_required) * @endif</label>
                    @if($field->type === 'textarea')
                        <textarea name="{{ $field->name }}" class="form-control" placeholder="{{ $field->placeholder }}">{{ old($field->name) }}</textarea>
                    @elseif($field->type === 'select')
                        <select name="{{ $field->name }}" class="form-control">
                            @foreach($field->options ?? [] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    @elseif($field->type === 'radio')
                        @foreach($field->options ?? [] as $opt)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $field->name }}" value="{{ $opt }}">
                                <label class="form-check-label">{{ $opt }}</label>
                            </div>
                        @endforeach
                    @elseif($field->type === 'checkbox')
                        @foreach($field->options ?? [] as $opt)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="{{ $field->name }}[]" value="{{ $opt }}">
                                <label class="form-check-label">{{ $opt }}</label>
                            </div>
                        @endforeach
                    @else
                        <input type="{{ $field->type }}" name="{{ $field->name }}" value="{{ old($field->name) }}" class="form-control" placeholder="{{ $field->placeholder }}">
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
