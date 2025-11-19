<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1>Create Form</h1>
        <form id="fb-form" method="POST" action="{{ route('admin.forms.store') }}">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Slug (optional)</label>
                <input name="slug" class="form-control">
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Toolbox</h5>
                    <div class="fb-toolbox border p-2">
                        <div class="fb-tool mb-2 p-2 bg-light" data-type="text">Text</div>
                        <div class="fb-tool mb-2 p-2 bg-light" data-type="textarea">Textarea</div>
                        <div class="fb-tool mb-2 p-2 bg-light" data-type="select">Select</div>
                        <div class="fb-tool mb-2 p-2 bg-light" data-type="radio">Radio</div>
                        <div class="fb-tool mb-2 p-2 bg-light" data-type="checkbox">Checkbox</div>
                        <button class="btn btn-sm btn-outline-primary fb-add-field" data-type="text">Add Text</button>
                    </div>
                </div>
                <div class="col-md-8">
                    <h5>Canvas (drop here)</h5>
                    <div id="fb-canvas" class="border p-3" style="min-height:200px"></div>
                </div>
            </div>

            <div class="mt-3">
                <button id="fb-preview" class="btn btn-secondary">Preview</button>
                <button id="fb-save" class="btn btn-primary">Save Form</button>
            </div>
        </form>

        <h3 class="mt-4">Preview</h3>
        <div id="fb-preview-area" class="border p-3"></div>
    </div>

    <script src="/assets/js/form-builder.js"></script>
</body>
</html>
