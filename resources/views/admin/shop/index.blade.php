@extends('admin.table_layouts')
@section('content')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h1 class="panel-title">
                    <i class="icon wb-shopping-cart" aria-hidden="true"></i>{{ trans('admin.menu.shop.goods') }}
                </h1>
                @can('admin.goods.create')
                    <div class="panel-actions">
                        <a class="btn btn-primary" href="{{ route('admin.goods.create') }}">
                            <i class="icon wb-plus"></i> {{ trans('common.add') }}
                        </a>
                    </div>
                @endcan
            </div>
            <div class="panel-body">
                <form class="form-row">
                    <div class="form-group col-lg-2 col-sm-4">
                        <select class="form-control" id="type" name="type" data-plugin="selectpicker" data-style="btn-outline btn-primary"
                                title="{{ trans('model.common.type') }}">
                            <option value="1">{{ trans('admin.goods.type.package') }}</option>
                            <option value="2">{{ trans('admin.goods.type.plan') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-sm-4">
                        <select class="form-control" id="status" name="status" data-plugin="selectpicker" data-style="btn-outline btn-primary"
                                title="{{ trans('common.status.attribute') }}">
                            <option value="1">{{ trans('admin.goods.status.yes') }}</option>
                            <option value="0">{{ trans('admin.goods.status.no') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-sm-4 btn-group">
                        <button class="btn btn-primary" type="submit">{{ trans('common.search') }}</button>
                        <button class="btn btn-danger" type="button" onclick="resetSearchForm()">{{ trans('common.reset') }}</button>
                    </div>
                </form>
                <table class="text-md-center" data-toggle="table" data-mobile-responsive="true">
                    <thead class="thead-default">
                        <tr>
                            <th> #</th>
                            <th> {{ trans('model.goods.name') }}</th>
                            <th> {{ trans('model.common.type') }}</th>
                            <th> {{ trans('model.goods.logo') }}</th>
                            <th> {{ trans('model.goods.traffic') }}</th>
                            <th> {{ trans('model.goods.price') }}</th>
                            <th> {{ trans('model.common.sort') }}</th>
                            <th> {{ trans('admin.goods.sell_and_used') }}</th>
                            <th> {{ trans('model.goods.hot') }}</th>
                            <th> {{ trans('model.goods.limit_num') }}</th>
                            <th> {{ trans('common.status.attribute') }}</th>
                            <th> {{ trans('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodsList as $goods)
                            <tr>
                                <td> {{ $goods->id }} </td>
                                <td> {{ $goods->name }} </td>
                                <td>
                                    @if ($goods->type === 1)
                                        {{ trans('admin.goods.type.package') }}
                                    @elseif($goods->type === 2)
                                        {{ trans('admin.goods.type.plan') }}
                                    @else
                                        {{ trans('admin.goods.type.top_up') }}
                                    @endif
                                </td>
                                <td style="background-color: {{ $goods->color ?? 'white' }}" @if ($goods->color) class="text-white" @endif>
                                    @if ($goods->logo)
                                        <a href="{{ asset($goods->logo) }}" target="_blank">
                                            <img class="h-50" src="{{ asset($goods->logo) }}" alt="logo" />
                                        </a>
                                    @elseif($goods->color)
                                        {{ trans('common.none') }}
                                    @endif
                                </td>
                                <td> {{ $goods->traffic_label }} </td>
                                <td> {{ $goods->price_tag }}</td>
                                <td> {{ $goods->sort }} </td>
                                <td><code>{{ $goods->use_count }} / {{ $goods->total_count }}</code></td>
                                <td>
                                    @if ($goods->is_hot)
                                        🔥
                                    @endif
                                </td>
                                <td>
                                    {{ $goods->limit_num ?: trans('common.unlimited') }}
                                </td>
                                <td>
                                    @if ($goods->status)
                                        <span class="badge badge-lg badge-success">{{ trans('admin.goods.status.yes') }}</span>
                                    @else
                                        <span class="badge badge-lg badge-default">{{ trans('admin.goods.status.no') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @canany(['admin.goods.edit', 'admin.goods.destroy'])
                                        <div class="btn-group">
                                            @can('admin.goods.edit')
                                                <a class="btn btn-primary" href="{{ route('admin.goods.edit', $goods) }}">
                                                    <i class="icon wb-edit"></i>
                                                </a>
                                            @endcan
                                            @can('admin.goods.destroy')
                                                <button class="btn btn-danger" onclick="delGoods('{{ route('admin.goods.destroy', $goods) }}','{{ $goods->name }}')">
                                                    <i class="icon wb-trash"></i>
                                                </button>
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
                        {!! trans('admin.goods.counts', ['num' => $goodsList->total()]) !!}
                    </div>
                    <div class="col-sm-8">
                        <nav class="Page navigation float-right">
                            {{ $goodsList->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function() {
            $("#type").selectpicker("val", @json(Request::query('type')));
            $("#status").selectpicker("val", @json(Request::query('status')));
        });

        @can('admin.goods.destroy')
            // 删除商品
            function delGoods(url, name) {
                swal.fire({
                    title: '{{ trans('common.warning') }}',
                    text: '{{ trans('admin.confirm.delete.0', ['attribute' => trans('model.goods.attribute')]) }}' + name +
                        '{{ trans('admin.confirm.delete.1') }}',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: '{{ trans('common.cancel') }}',
                    confirmButtonText: '{{ trans('common.confirm') }}'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            method: "DELETE",
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
        @endcan
    </script>
@endpush
