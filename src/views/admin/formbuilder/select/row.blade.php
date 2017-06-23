<div class="uk-form-row form-group-{{ $row_key }} {{ \Illuminate\Support\Arr::get($row_settings, 'css_class_group') }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="input-group">
        <select name="{{ $row_key }}" id="{{ $row_key }}" class="{{ $row_settings->css_class }}">
            <option value="">Не назначено</option>
            @foreach($row_settings['options'] as $options_key => $options_value)
                <option value="@if(\Illuminate\Support\Arr::get($row_settings['options_connect'], 'selected_search') === 'value'){{ $options_value }}@else{{ $options_key }}@endif"
                        @isset($data->{$row_key})
                        @if(\Illuminate\Support\Arr::get($row_settings['options_connect'], 'selected_search') === 'value')
                            @if(Request::old($row_key, $data->{$row_key}) === $options_value) selected @endif
                        @else
                            @if((int)Request::old($row_key, $data->{$row_key}) === (int)$options_key) selected @endif
                        @endif
                        @endisset>
                    {{ $options_value }}</option>
            @endforeach
        </select>
        <div class="uk-float-right"><span class="uk-button uk-button-small new_list" data-row-name="{{ $row_key }}">Создать</span></div>
    </div>
</div>