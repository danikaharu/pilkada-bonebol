<div class="d-flex">
    @can('edit polling station')
        <a href="{{ route('admin.pollingstation.edit', $id) }}" class="btn btn-sm btn-warning text-white me-2">
            <i class='bx bx-edit'></i>
        </a>
    @endcan

    @can('delete polling station')
        <form action="{{ route('admin.pollingstation.destroy', $id) }}" method="POST" role="alert" alert-title="Hapus Data"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-2"><i class="bx bx-trash"></i>
            </button>
        </form>
    @endcan
</div>
