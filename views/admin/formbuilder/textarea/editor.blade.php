<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <textarea name="{{ $row_key }}" class="uk-textarea form-control {{ $row_settings->cssClass }}" id="{{ $row_key }}">@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset</textarea>
</div>