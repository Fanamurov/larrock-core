<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">({{ $row_settings->help }})</span>
        @endif
    </label>
    <input type="file" @if($row_settings->multiple) multiple @endif name="{{ $row_key }}@if($row_settings->multiple)[]@endif"
           class="uk-input uk-width-1-1 {{ $row_settings->cssClass }}" id="{{ $row_key }}"
           @if($row_settings->accept) accept="{{ $row_settings->accept }}" @endif>
</div>