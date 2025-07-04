@props(['code', 'value'])

<div class="form-group col-lg-6">
    <div class="row">
        <label class="col-form-label col-md-3" for="{{ $code }}">{{ trans("model.config.$code") }}</label>
        <div class="col-md-8">
            <input name="{{ $code }}" data-plugin="dropify" data-default-file="{{ asset($value ?? '/assets/images/default.png') }}" type="file" />
            <button class="btn btn-success float-right mt-10" type="submit">{{ trans('common.submit') }}</button>
        </div>
    </div>
</div>
