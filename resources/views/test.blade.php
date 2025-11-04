            <form action="/admin/artikel/delete/storage/{{ $row->post_id }}" method="post">
              @csrf
              @method('DELETE')
              <button type="submit"  class="btn btn-danger" aria-label="Hapus" onclick="return confirm('Anda yakin ingin menghapus artikel ini?')">
                <i class="fas fa-trash"></i>
                  </button>
            </form>