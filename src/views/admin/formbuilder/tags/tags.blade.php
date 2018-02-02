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
            @foreach($selected as $value)
                <option selected="selected" value="{{ $value->id }}">{{ $value->title }}</option>
            @endforeach
        </select>

        @if($row_settings->costValue)
            <div class="uk-margin-top">
                <p class="uk-alert uk-alert-warning">Данное поле влияет на цену товара. Стандартное поле "Цена" учитываться не будет</p>
                <p class="uk-alert uk-alert-warning">Внесение цен модификаций товара доступно после сохранения</p>
                <div class="uk-margin-top uk-form uk-form-horizontal">
                    @foreach($selected as $value)
                        <div>
                            <label class="uk-form-label">Цена модификации «{{ $value->title }}»</label>
                            <input type="text" name="cost_{{ $value->id }}" value="{{ $value->cost or $data->cost }}" placeholder="Цена модификации">
                        </div>
                    @endforeach
                </div>
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