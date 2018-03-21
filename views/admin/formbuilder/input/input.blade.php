<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    @if($row_settings->typo)
        <div uk-grid class="uk-grid uk-grid-small uk-form-row-small">
            <div class="uk-width-expand">
                <input type="text" name="{{ $row_key }}"
                       value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endif"
                       class="uk-input uk-width-1-1 {{ $row_settings->cssClass }}" id="{{ $row_key }}"
                       @if($row_key === 'title') data-table="{{ $app->model }}" @endif>
            </div>
            <div class="uk-width-auto">
                <button type="button" class="uk-button uk-button-default typo-target" data-target="{{ $row_key }}">Типограф</button>
                @if($row_key === 'title')
                    <button type="button" class="uk-button uk-button-default refresh-url">Создать url</button>
                @endif
            </div>
        </div>
    @else
        <input type="text" name="{{ $row_key }}"
               value="@isset($data->{$row_key}){{ Request::old($row_key, $data->{$row_key}) }}@endisset"
               class="{{ $row_settings->cssClass }} uk-input" id="{{ $row_key }}">
    @endif
</div>