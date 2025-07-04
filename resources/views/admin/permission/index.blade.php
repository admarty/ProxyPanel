@extends('admin.table_layouts')
@section('content')
    <div class="page-content container">
        <div class="panel">
            <div class="panel-heading">
                <h2 class="panel-title">{{ trans('admin.menu.rbac.permission') }}</h2>
                @can('admin.permission.create')
                    <div class="panel-actions">
                        <a class="btn btn-outline-primary" href="{{ route('admin.permission.create') }}">
                            <i class="icon wb-plus" aria-hidden="true"></i> {{ trans('common.add') }}
                        </a>
                    </div>
                @endcan
            </div>
            <div class="panel-body">
                <form class="form-row">
                    <div class="form-group col-lg-5 col-sm-6">
                        <input class="form-control" name="description" type="text" value="{{ Request::query('description') }}"
                               placeholder="{{ trans('model.permission.description') }}" />
                    </div>
                    <div class="form-group col-lg-5 col-sm-6">
                        <input class="form-control" name="name" type="text" value="{{ Request::query('name') }}"
                               placeholder="{{ trans('model.permission.name') }}" />
                    </div>
                    <div class="form-group col-lg-2 col-sm-6 btn-group">
                        <button class="btn btn-primary" type="submit">{{ trans('common.search') }}</button>
                        <button class="btn btn-danger" type="button" onclick="resetSearchForm()">{{ trans('common.reset') }}</button>
                    </div>
                </form>
                <table class="text-md-center" data-toggle="table" data-mobile-responsive="true">
                    <thead class="thead-default">
                        <tr>
                            <th> #</th>
                            <th> {{ trans('model.permission.description') }}</th>
                            <th> {{ trans('model.permission.name') }}</th>
                            <th> {{ trans('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->description }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    @canany(['admin.permission.edit', 'admin.permission.destroy'])
                                        <div class="btn-group">
                                            @can('admin.permission.edit')
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.permission.edit', $permission) }}">
                                                    <i class="icon wb-edit"></i></a>
                                            @endcan
                                            @can('admin.permission.destroy')
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="delPermission('{{ route('admin.permission.destroy', $permission) }}','{{ $permission->name }}')">
                                                    <i class="icon wb-trash"></i></button>
                                            @endcan
                                        </div>
                                    @endcanany
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-4">
                        {!! trans('admin.permission.counts', ['num' => $permissions->total()]) !!}
                    </div>
                    <div class="col-sm-8">
                        <nav class="Page navigation float-right">
                            {{ $permissions->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    @can('admin.permission.destroy')
        <script>
            function delPermission(url, name) {
                swal.fire({
                    title: '{{ trans('common.warning') }}',
                    text: '{{ trans('admin.confirm.delete.0', ['attribute' => trans('model.permission.attribute')]) }}' +
                        name + '{{ trans('admin.confirm.delete.1') }}',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: '{{ trans('common.close') }}',
                    confirmButtonText: '{{ trans('common.confirm') }}'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "DELETE",
                            url: url,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: "json",
                            success: function(ret) {
                                if (ret.status === "success") {
                                    swal.fire({
                                        title: ret.message,
                                        icon: "success",
                                        timer: 1000,
                                        showConfirmButton: false
                                    }).then(() => window.location.reload());
                                } else {
                                    swal.fire({
                                        title: ret.message,
                                        icon: "error"
                                    }).then(() => window.location.reload());
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endcan
@endpush
