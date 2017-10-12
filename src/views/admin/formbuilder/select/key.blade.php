<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <select name="{{ $row_key }}" class="{{ $row_settings->css_class }}" id="{{ $row_key }}">
        @if($row_settings->default !== NULL)
            <option>{{ $row_settings->default }}</option>
        @endif
        @foreach($row_settings->options as $options_key => $options_value)
            <option value="@if($row_settings->option_key){{ $options_value->{$row_settings->option_key} }}@else{{ $options_key }}@endif"
                    @isset($data->{$row_key})
                    @if($row_settings->option_key)
                        @if(Request::old($row_settings->option_key, $data->{$row_key}) === $options_value->{$row_settings->option_key}) selected @endif
                    @else
                        @if(Request::old($row_key, $data->{$row_key}) === $options_key) selected @endif
                    @endif
                    @endisset>
                {{ $options_value->{$row_settings->option_title} }}</option>
        @endforeach
    </select>
</div>