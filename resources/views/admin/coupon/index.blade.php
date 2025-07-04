@extends('admin.table_layouts')
@section('content')
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-heading">
                <h1 class="panel-title">{{ trans('admin.menu.shop.coupon') }}</h1>
                @canany(['admin.coupon.export', 'admin.coupon.create'])
                    <div class="panel-actions btn-group">
                        @can('admin.coupon.export')
                            <button class="btn btn-info" onclick="exportCoupon()"><i class="icon wb-code"></i>{{ trans('admin.massive_export') }}</button>
                        @endcan
                        @can('admin.coupon.create')
                            <a class="btn btn-primary" href="{{ route('admin.coupon.create') }}"><i class="icon wb-plus"></i> {{ trans('common.add') }}</a>
                        @endcan
                    </div>
                @endcanany
            </div>
            <div class="panel-body">
                <form class="form-row">
                    <div class="form-group col-lg-3 col-sm-4">
                        <input class="form-control" name="sn" type="text" value="{{ Request::query('sn') }}" placeholder="{{ trans('model.coupon.sn') }}"
                               autocomplete="off" />
                    </div>
                    <div class="form-group col-lg-3 col-sm-4">
                        <select class="form-control" id="type" name="type" data-plugin="selectpicker" data-style="btn-outline btn-primary"
                                title="{{ trans('model.common.type') }}">
                            <option value="1">{{ trans('admin.coupon.type.voucher') }}</option>
                            <option value="2">{{ trans('admin.coupon.type.discount') }}</option>
                            <option value="3">{{ trans('admin.coupon.type.charge') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 col-sm-4">
                        <select class="form-control" id="status" name="status" data-plugin="selectpicker" data-style="btn-outline btn-primary"
                                title="{{ trans('common.status.attribute') }}">
                            <option value="0">{{ trans('common.status.available') }}</option>
                            <option value="1">{{ trans('common.status.used') }}</option>
                            <option value="2">{{ trans('common.status.expire') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 col-sm-4 btn-group">
                        <button class="btn btn-primary" type="submit">{{ trans('common.search') }}</button>
                        <button class="btn btn-danger" type="button" onclick="resetSearchForm()">{{ trans('common.reset') }}</button>
                    </div>
                </form>
                <table class="text-md-center" data-toggle="table" data-mobile-responsive="true">
                    <thead class="thead-default">
                        <tr>
                            <th> #</th>
                            <th> {{ trans('model.coupon.name') }}</th>
                            <th> {{ trans('model.coupon.sn') }}</th>
                            <th> {{ trans('model.coupon.logo') }}</th>
                            <th> {{ trans('model.common.type') }}</th>
                            <th> {{ trans('model.coupon.priority') }}</th>
                            <th> {{ trans('model.coupon.usable_times') }}</th>
                            <th> {{ trans('admin.coupon.discount') }}</th>
                            <th> {{ trans('common.available_date') }}</th>
                            <th> {{ trans('common.status.attribute') }}</th>
                            <th> {{ trans('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($couponList as $coupon)
                            <tr>
                                <td> {{ $coupon->id }} </td>
                                <td> {{ $coupon->name }} </td>
                                <td> {{ $coupon->sn }} </td>
                                <td>
                                    @if ($coupon->logo)
                                        <img class="h-50" src="{{ asset($coupon->logo) }}" alt="{{ trans('model.coupon.logo') }}" />
                                    @endif
                                </td>
                                <td>
                                    {{ [trans('common.status.unknown'), trans('admin.coupon.type.voucher'), trans('admin.coupon.type.discount'), trans('admin.coupon.type.charge')][$coupon->type] }}
                                </td>
                                <td> {{ $coupon->priority }} </td>
                                <td> {{ $coupon->type === 3 ? trans('admin.coupon.single_use') : $coupon->usable_times ?? trans('common.unlimited') }} </td>
                                <td>
                                    {{ trans_choice('admin.coupon.value', $coupon->type, ['num' => $coupon->type === 2 ? $coupon->value : \App\Utils\Helpers::getPriceTag($coupon->value)]) }}
                                </td>
                                <td> {{ $coupon->start_time }} ~ {{ $coupon->end_time }} </td>
                                <td>
                                    <span class="badge badge-lg @if ($coupon->status) badge-default @else badge-success @endif">
                                        {{ [trans('common.status.available'), trans('common.status.used'), trans('common.status.expire')][$coupon->status] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @can('admin.coupon.show')
                                            <a class="btn btn-info" href="{{ route('admin.coupon.show', $coupon) }}" target="_blank">
                                                <i class="icon wb-eye"></i>
                                            </a>
                                        @endcan
                                        @if ($coupon->status !== 1)
                                            @can('admin.coupon.destroy')
                                                <button class="btn btn-danger" onclick="delCoupon('{{ $coupon->id }}','{{ $coupon->name }}')">
                                                    <i class="icon wb-close"></i>
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-4">
                        {!! trans('admin.coupon.counts', ['num' => $couponList->total()]) !!}
                    </div>
                    <div class="col-sm-8">
                        <nav class="Page navigation float-right">
                            {{ $couponList->links() }}
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

        @can('admin.coupon.export')
            // 批量导出卡券
            function exportCoupon() {
                swal.fire({
                    title: '{{ trans('admin.coupon.export_title') }}',
                    text: '{{ trans('admin.confirm.export') }}？',
                    icon: "question",
                    showCancelButton: true,
                    cancelButtonText: '{{ trans('common.close') }}',
                    confirmButtonText: '{{ trans('common.confirm') }}'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = '{{ route('admin.coupon.export') }}';
                    }
                });
            }
        @endcan

        @can('admin.coupon.destroy')
            // 删除卡券
            function delCoupon(id, name) {
                swal.fire({
                    title: '{{ trans('admin.confirm.delete.0', ['attribute' => trans('model.coupon.attribute')]) }}' + name +
                        '{{ trans('admin.confirm.delete.1') }}',
                    icon: "question",
                    allowEnterKey: false,
                    showCancelButton: true,
                    cancelButtonText: '{{ trans('common.close') }}',
                    confirmButtonText: '{{ trans('common.confirm') }}'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "DELETE",
                            url: '{{ route('admin.coupon.destroy', '') }}/' + id,
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
