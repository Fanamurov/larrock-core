<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="input-group" style="width: 100%">
        <select @if($row_settings->maxItems !== 1) multiple @endif
            name="@if($row_settings->maxItems !== 1){{ $row_key }}[]@else{{ $row_key }}@endif" id="tags_{{ $row_key }}">
            @if($selected)
                @foreach($selected as $sel)
                <option selected="selected" value="{{ $sel->id_child }}">{{ $sel->title }}</option>
                @endforeach
            @endif
        </select>
        <input type="hidden" name="modelParent" value="{{ $row_settings->modelParent }}">
        <input type="hidden" name="modelParentId" value="{{ $data->id }}">
        <input type="hidden" name="modelChild" value="{{ $row_settings->modelChild }}">
    </div>

    <script type="text/javascript">
        $('#tags_{{ $row_key }}').selectize({
            maxItems: {{ $row_settings->maxItems or 'null' }},
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
                        {title: '{!! $value->title !!} @if($value->category) ({!! $value->get_category->title !!}) @endif', id: '{!! $value->id !!}'},
                    @elseif($value->name)
                        {title: '{!! $value->name !!} @if($value->category) ({!! $value->get_category->title !!}) @endif', id: '{!! $value->id !!}'},
                    @else
                        {title: '{!! $value->id !!} @if($value->category) ({!! $value->get_category->title !!}) @endif', id: '{!! $value->id !!}'},
                    @endif
                @endforeach
            ],
        });
    </script>
</div>