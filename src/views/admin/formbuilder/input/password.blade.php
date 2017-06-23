<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <input type="password" name="{{ $row_key }}"
           value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset"
           class="{{ $row_settings->css_class }}" id="{{ $row_key }}">
</div>