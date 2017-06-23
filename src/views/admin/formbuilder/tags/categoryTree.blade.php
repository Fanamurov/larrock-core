<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="input-group" style="width: 100%">
        <select @if($row_settings->max_items !== 1) multiple @endif
            name="@if($row_settings->max_items !== 1){{ $row_key }}[]@else{{ $row_key }}@endif"
                id="tags_{{ $row_key }}">
            @if( !$row_settings->allow_empty && !$selected)
                <option selected="selected" value="0">-Корневой раздел-</option>
            @endif
            @if($selected)
                @foreach($selected as $value)
                    <option selected="selected" value="{{ $value->id }}">{{ $value->title }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <script type="text/javascript">
        $('#tags_{{ $row_key }}').selectize({
            maxItems: {{ $row_settings->max_items or 'null' }},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            plugins: ['remove_button'],
            persist: false,
            allowEmptyOption: false,
            options: [
                @if( !$row_settings->allow_empty && !$selected)
                    {title: '-Корневой раздел-', id: '0'},
                @endif
                @foreach($row_settings->options as $value)
                    {title: '{!! $value->title !!}', id: '{!! $value->id !!}'},
                    @if(isset($value->children))
                        @foreach($value->children as $child)
                            {title: '- {!! $child->title !!}', id: '{!! $child->id !!}'},
                            @if(isset($child->children))
                                @foreach($child->children as $child_child)
                                    {title: '-- {!! $child_child->title !!}', id: '{!! $child_child->id !!}'},
                                    @if(isset($child_child->children))
                                        @foreach($child_child->children as $child_child_child)
                                            {title: '--- {!! $child_child_child->title !!}', id: '{!! $child_child_child->id !!}'},
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            ],
        });
    </script>
</div>