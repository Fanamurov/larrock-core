@if(isset($allowDestroy))
    <form id="{{ $formId }}" class="uk-alert uk-alert-warning massive_action uk-hidden" method="post" action="/admin/{{ $app }}/0">
        <select name="ids[]" multiple class="uk-hidden">
            @foreach($data as $item)
                <option value="{{ $item->id }}">{{ $item->id }}</option>
            @endforeach
        </select>
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <button type="submit" class="uk-button uk-button-danger please_conform uk-margin-right">Удалить</button> Выделено: <span>0</span> элементов.
    </form>
@endif