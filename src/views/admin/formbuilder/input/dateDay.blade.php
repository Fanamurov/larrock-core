<div class="uk-form-row form-group-{{ $row_key }} {{ \Illuminate\Support\Arr::get($row_settings, 'css_class_group') }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    @if($data->{$row_key} > \Carbon\Carbon::createFromFormat('Y-m-d h:s:i', '2015-01-01 00:00:00'))
        <input type="{{ $row_settings['type'] or 'text' }}" name="{{ $row_key }}"
               value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset" class="form-control dateDay" id="{{ $row_key }}">
    @else
        <input type="{{ $row_settings['type'] or 'text' }}" name="{{ $row_key }}"
               value="" class="form-control dateDay" id="{{ $row_key }}">
    @endif
</div>