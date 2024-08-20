<div class="d-flex">
    @can('edit electoral district')
        <a href="{{ route('admin.electoraldistrict.edit', $id) }}" class="btn btn-sm btn-warning text-white me-2">
            <i class='bx bx-edit'></i>
        </a>
    @endcan

    @can('delete electoral district')
        <form action="{{ route('admin.electoraldistrict.destroy', $id) }}" method="POST" role="alert"
            alert-title="Hapus Data" alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-2"><i class="bx bx-trash"></i>
            </button>
        </form>
    @endcan
</div>
