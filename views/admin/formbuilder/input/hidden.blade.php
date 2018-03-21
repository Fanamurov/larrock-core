<div class="uk-form-row uk-hidden form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <input type="hidden" name="{{ $row_key }}"
           value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset"
           class="form-control {{ $row_settings->cssClass }}" id="{{ $row_key }}">
</div>