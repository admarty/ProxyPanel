@props(['code', 'check', 'url' => null, 'feature' => null])

<div class="form-group col-lg-6">
    <div class="row">
        <label class="col-md-3 col-form-label" for="{{ $code }}">{{ trans("model.config.$code") }}</label>
        <div class="col-md-9">
            <input id="{{ $code }}" data-plugin="switchery" type="checkbox" @if ($check) checked @endif
                   @if ($feature) data-feature-toggle="{{ $feature }}" @endif
                   onchange="updateFromOther('switch','{{ $code }}')" />
            @if (trans("admin.system.hint.$code") !== "admin.system.hint.$code")
                <span class="text-help">
                    @if (isset($url))
                        {!! trans("admin.system.hint.$code", ['url' => $url]) !!}
                    @else
                        {!! trans("admin.system.hint.$code") !!}
                    @endif
                </span>
            @endif
        </div>
    </div>
</div>
