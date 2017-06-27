<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="input-group" style="width: 100%">
        <select @if($row_settings->max_items !== 1) multiple @endif
            name="@if($row_settings->max_items !== 1){{ $row_key }}[]@else{{ $row_key }}@endif" id="tags_{{ $row_key }}">
            @if(is_array($selected))
                @foreach($selected as $value)
                    <option selected="selected" value="{{ $value->id }}">{{ $value->title }}</option>
                @endforeach
            @endif
            @if(is_object($selected))
                <option selected="selected" value="{{ $selected->id }}">{{ $selected->title }}</option>
            @endif
        </select>
    </div>

    <script type="text/javascript">
        $('#tags_{{ $row_key }}').selectize({
            maxItems: {{ $row_settings->max_items or 'null' }},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            persist: false,
            createOnBlur: false,
            create: false,
            plugins: ['remove_button'],
            allowEmptyOption: true,
            sortField: {
                field: 'title',
                direction: 'asc'
            },
            options: [
                @foreach($tags as $value)
                    @if($value->title)
                        {title: '{!! $value->title !!}', id: '{!! $value->id !!}'},
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