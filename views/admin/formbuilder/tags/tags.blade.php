<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->cssClassGroup }} @if($row_settings->costValue) row-costValue @endif">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-label uk-label-warning">{{ $row_settings->help }}</span>
        @endif
    </label>
    <div class="input-group">
        <select @if($row_settings->maxItems !== 1) multiple @endif
            name="@if($row_settings->maxItems !== 1){{ $row_key }}[]@else{{ $row_key }}@endif" id="tags_{{ $row_key }}">
            @if($selected)
                @foreach($selected as $value)
                    <option selected="selected" value="{{ $value->id }}">{{ $value->{$row_settings->titleRow} }}</option>
                @endforeach
            @endif
        </select>

        @if($row_settings->costValue)
            <div class="uk-form uk-form-horizontal">
                @foreach($selected as $value)
                    <div>
                        <label class="uk-form-label">Цена «{{ $value->{$row_settings->titleRow} }}»</label>
                        <input class="uk-input uk-form-width-medium" type="text" name="cost_{{ $value->id }}" value="{{ $value->cost or $data->cost }}" placeholder="Цена модификации">
                        <span class="uk-margin-left uk-text-small uk-text-muted">{{ $data->what }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script type="text/javascript">
        $('#tags_{{ $row_key }}').selectize({
            maxItems: {{ $row_settings->maxItems or 'null' }},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            persist: false,
            createOnBlur: false,
            @if($row_settings->allowCreate)
            create: true,
            @else
            create: false,
            @endif
            plugins: ['remove_button'],
            allowEmptyOption: true,
            sortField: {
                field: 'title',
                direction: 'asc'
            },
            options: [
                @foreach($tags as $value)
                    @if($value->{$row_settings->titleRow})
                        {title: '{!! $value->{$row_settings->titleRow} !!}', id: '{!! $value->id !!}'},
                    @elseif($value->name)
                        {title: '{!! $value->name !!}', id: '{!! $value->id !!}'},
                    @else
                        {title: '{!! $value->id !!}', id: '{!! $value->id !!}'},
                    @endif
                @endforeach
            ],
        });
    </script>
</div>