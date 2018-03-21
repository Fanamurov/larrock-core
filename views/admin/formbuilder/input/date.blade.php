<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="uk-inline">
        <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: calendar"></span>
        <input type="text" name="{{ $row_key }}"
               value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset" class="form-control date uk-input" id="{{ $row_key }}">
    </div>
    @if($row_settings->help)
        <p class="uk-form-help-block">{{ $row_settings->help }}</p>
    @endif
</div>