<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Artikel</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body class="bg-body-tertiary"> <div class="container my-5">
    
    <header class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-success fw-bold mb-0">Kelola Artikel</h1>
      
      <a href="/admin/artikel/tambah" class="btn btn-success d-flex align-items-center shadow-sm">
        <i class="fas fa-plus me-2"></i> Tambah Artikel
      </a>
    </header>

    <main class="card shadow-sm border-0 rounded-3 p-4">
      <h2 class="h5 mb-3">Daftar Artikel</h2>

      <div class="d-flex flex-column gap-3">
        
        @foreach ($post_data as $row)
        <div class="d-flex justify-content-between align-items-center p-3 rounded-2 border bg-light">
          
          <div class="d-flex flex-column">
            <span class="fw-semibold mb-1">{{ $row->title }}</span>
            
            <div class="d-flex gap-2">
              <span class="badge rounded-pill text-bg-secondary">{{ $row->kategori }}</span>
              
              @if ($row->status == 'published' || $row->status == 'Dipublikasikan')
                <span class="badge rounded-pill text-bg-light text-success-emphasis border border-success-subtle">
                  {{ $row->status }}
                </span>
              @else
                <span class="badge rounded-pill text-bg-light text-danger-emphasis border border-danger-subtle">
                  {{ $row->status }}
                </span>
              @endif
            </div>
          </div>
          
          <div class="d-flex gap-2">
            <a href="/admin/artikel/edit/{{ $row->post_id }}" class="btn btn-light border" aria-label="Edit">
              <i class="fas fa-pen text-muted"></i>
            </a>
            
            <a href="/admin/artikel/delete/{{ $row->post_id }}" class="btn btn-danger" aria-label="Hapus" onclick="return confirm('Anda yakin ingin menghapus artikel ini?')">
              <i class="fas fa-trash"></i>
            </a>
          </div>
        </div>
        @endforeach
        
        </div> </main> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>