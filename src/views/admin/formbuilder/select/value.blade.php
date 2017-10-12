<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
        @if($row_settings->allowCreate)
            <div class="uk-float-right"><span class="uk-button uk-button-small new_list" data-row-name="{{ $row_key }}">Создать</span></div>
        @endif
    </label>
    <select name="{{ $row_key }}" id="{{ $row_key }}" class="{{ $row_settings->css_class }}">
        <option></option>
        @foreach($row_settings->options as $options_key => $options_value)
            @if($row_settings->connect)
                <option value="{{ $options_value->{$row_settings->name} }}"
                    @isset($data->{$row_settings->name})
                        @if(Request::old($row_settings->name, $data->{$row_settings->name}) === $options_value->{$row_settings->name}) selected @endif
                    @endisset>
                    @if($row_settings->option_title)
                        {{ $options_value->{$row_settings->option_title} }}
                    @else
                        {{ $options_value->{$row_settings->name} }}
                    @endif
                </option>
            @else
                <option value="{{ $options_value }}"
                    @isset($data->{$row_settings->name})
                        @if(Request::old($row_settings->name, $data->{$row_settings->name}) === $options_value) selected @endif
                    @endisset>
                    @if($row_settings->option_title)
                        {{ $options_value->{$row_settings->option_title} }}
                    @else
                        {{ $options_value }}
                    @endif
                </option>
            @endif
        @endforeach
    </select>
</div>