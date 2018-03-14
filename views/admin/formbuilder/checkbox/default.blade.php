<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label>
        <input type="checkbox" name="{{ $row_key }}" id="{{ $row_key }}" class="uk-checkbox"
               @isset($data->{$row_key}) @if(Request::old($row_key, $data->{$row_key}) === 1) checked @endif @endisset
               value="1"> {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
</div>