@props(['type' => 'text', 'code', 'value', 'holder' => null, 'url' => null, 'test' => null])

<div class="form-group col-lg-6">
    <div class="row">
        <label class="col-md-3 col-form-label" for="{{ $code }}">
            {{ trans("model.config.$code") }}
        </label>
        <div class="col-md-6">
            <div class="input-group">
                <input class="form-control" id="{{ $code }}" type="{{ $type }}" value="{{ $value }}" placeholder="{!! $holder !!}" />
                <span class="input-group-append">
                    <button class="btn btn-primary" type="button" onclick="update('{{ $code }}')">
                        {{ trans('common.update') }}
                    </button>
                </span>
            </div>
            @if (trans("admin.system.hint.$code") !== "admin.system.hint.$code")
                <span class="text-help">
                    @isset($url)
                        {!! trans("admin.system.hint.$code", ['url' => $url]) !!}
                    @else
                        {!! trans("admin.system.hint.$code") !!}
                    @endisset
                    @isset($test)
                        @can('admin.test.notify')
                            <a href="javascript:sendTestNotification('{{ $test }}');">
                                [{{ trans('admin.system.notification.send_test') }}]
                            </a>
                        @endcan
                    @endisset
                </span>
            @endif
        </div>
    </div>
</div>
