<div class="uk-form-row form-group-{{ $row_key }} {{ \Illuminate\Support\Arr::get($row_settings, 'css_class_group') }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <select name="{{ $row_key }}" class="{{ $row_settings->css_class }}" id="{{ $row_key }}">
        @foreach($row_settings->options as $options_key => $options_value)
            <option value="{{ $options_key }}" @isset($data->{$row_key}) @if(Request::old($row_key, $data->{$row_key}) === $options_key) selected @endif @endisset>{{ $options_value }}</option>
        @endforeach
    </select>
</div>