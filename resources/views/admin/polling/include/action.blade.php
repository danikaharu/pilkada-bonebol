<div class="d-flex">
    @can('view polling')
        <a href="{{ route('admin.polling.show', $model->id) }}" class="btn btn-sm btn-info text-white me-2">
            <i class="bx bx-show"></i>
        </a>
    @endcan
    @can('edit polling')
        @if ($model->status != 1)
            <a href="{{ route('admin.polling.edit', $model->id) }}" class="btn btn-sm btn-warning text-white me-2">
                <i class="bx bx-edit"></i>
            </a>
        @endif
    @endcan
</div>
