<div class="uk-form-row form-group-{{ $row_key }} {{ \Illuminate\Support\Arr::get($row_settings, 'cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="uk-inline">
        <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: calendar"></span>
        @if($data->{$row_key} > \Carbon\Carbon::createFromFormat('Y-m-d h:s:i', '2015-01-01 00:00:00'))
            <input type="{{ $row_settings['type'] or 'text' }}" name="{{ $row_key }}"
                   value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset" class="form-control dateDay uk-input" id="{{ $row_key }}">
        @else
            <input type="{{ $row_settings['type'] or 'text' }}" name="{{ $row_key }}"
                   value="" class="form-control dateDay uk-input" id="{{ $row_key }}">
        @endif
    </div>
</div>