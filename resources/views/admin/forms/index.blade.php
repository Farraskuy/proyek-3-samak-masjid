@php
    // Minimal admin index for forms
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Builder - Admin</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1>Form Management</h1>
        <p><a href="{{ route('admin.forms.create') }}" class="btn btn-primary">Create New Form</a></p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead><tr><th>Title</th><th>Slug</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($forms as $form)
                    <tr>
                        <td>{{ $form->title }}</td>
                        <td>{{ $form->slug }}</td>
                        <td>{{ $form->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ url('/form/'.$form->slug) }}" class="btn btn-sm btn-success" target="_blank">Open</a>
                            <a href="{{ route('admin.forms.edit', $form->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('admin.forms.responses', $form->id) }}" class="btn btn-sm btn-info">Responses</a>
                            <form method="POST" action="{{ route('admin.forms.destroy', $form->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete form?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
