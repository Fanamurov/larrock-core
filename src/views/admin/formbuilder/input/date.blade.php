<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <input type="text" name="{{ $row_key }}"
           value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset" class="form-control date" id="{{ $row_key }}">

    @if($row_settings->help)
        <p class="uk-form-help-block">{{ $row_settings->help }}</p>
    @endif
</div>