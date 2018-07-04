<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <select name="{{ $row_key }}" class="{{ $row_settings->cssClass }} uk-select" id="{{ $row_key }}">
        @if($row_settings->default !== NULL)
            <option>{{ $row_settings->default }}</option>
        @endif
        @foreach($row_settings->options as $options_key => $options_value)
            <option value="{{ $options_key }}" @if(Request::old($row_key, $data->{$row_key}) === $options_key) selected @endif>
                {{ $options_value }}
            </option>
        @endforeach
    </select>
</div>