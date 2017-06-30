<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    @if($row_settings->typo)
        <div class="uk-grid">
            <div class="uk-width-1-1 uk-width-medium-7-10">
                <input type="text" name="{{ $row_key }}"
                       value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endif"
                       class="uk-width-1-1 {{ $row_settings->css_class }}" id="{{ $row_key }}"
                @if($row_key === 'title') data-table="{{ $app->model }}" @endif>
            </div>
            <div class="uk-width-1-1 uk-width-medium-3-10">
                <button type="button" class="uk-button uk-button-outline typo-target" data-target="{{ $row_key }}">Типограф</button>
                @if($row_key === 'title')
                    <button type="button" class="uk-button uk-button-outline refresh-url">Создать url</button>
                @endif
            </div>
        </div>
    @else
        <input type="text" name="{{ $row_key }}"
               value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset"
               class="{{ $row_settings->css_class }}" id="{{ $row_key }}">
    @endif
</div>