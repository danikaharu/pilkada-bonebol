<div class="d-flex">
    @can('edit candidate')
        <a href="{{ route('admin.candidate.edit', $id) }}" class="btn btn-sm btn-warning text-white me-2">
            <i class='bx bx-edit'></i>
        </a>
    @endcan

    @can('delete candidate')
        <form action="{{ route('admin.candidate.destroy', $id) }}" method="POST" role="alert" alert-title="Hapus Data"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-2"><i class="bx bx-trash"></i>
            </button>
        </form>
    @endcan
</div>
