<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Responses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1>Responses for: {{ $form->title }}</h1>
        <p><a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">Back</a></p>
        <table class="table">
            <thead><tr><th>ID</th><th>Submitted</th><th>IP</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($responses as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->created_at }}</td>
                        <td>{{ $r->ip_address }}</td>
                        <td>
                            <a href="{{ route('admin.forms.responses.show', [$form->id, $r->id]) }}" class="btn btn-sm btn-info">View</a>
                            <form method="POST" action="{{ route('admin.forms.responses.delete', [$form->id, $r->id]) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete response?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
