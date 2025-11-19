<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Response Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1>Response #{{ $response->id }} for {{ $form->title }}</h1>
        <p><a href="{{ route('admin.forms.responses', $form->id) }}" class="btn btn-secondary">Back</a></p>
        <dl class="row">
            <dt class="col-sm-3">Submitted</dt><dd class="col-sm-9">{{ $response->created_at }}</dd>
            <dt class="col-sm-3">IP</dt><dd class="col-sm-9">{{ $response->ip_address }}</dd>
            <dt class="col-sm-3">User agent</dt><dd class="col-sm-9">{{ $response->user_agent }}</dd>
        </dl>

        <h3>Answers</h3>
        <ul class="list-group">
            @foreach($response->items as $item)
                <li class="list-group-item"><strong>{{ $item->field_label }}</strong>: {!! nl2br(e($item->value)) !!}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
