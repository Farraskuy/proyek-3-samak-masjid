@extends('admin.layout') 

@section('content')
<div class="container mt-4">
    <h3>Kelola Rekening Bank</h3>
    <a href="{{ route('admin.banks.create') }}" class="btn btn-primary mb-3">Tambah Rekening</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Bank</th>
                        <th>No. Rekening</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banks as $bank)
                    <tr>
                        <td><img src="{{ asset($bank->logo_url) }}" width="50"></td>
                        <td>{{ $bank->bank_name }}<br><small>{{ $bank->account_holder_name }}</small></td>
                        <td>{{ $bank->account_number }}</td>
                        <td><span class="badge bg-info">{{ strtoupper($bank->category) }}</span></td>
                        <td>
                            @if($bank->is_active) <span class="badge bg-success">Aktif</span> 
                            @else <span class="badge bg-danger">Non-Aktif</span> @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.banks.edit', $bank->account_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.banks.destroy', $bank->account_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection