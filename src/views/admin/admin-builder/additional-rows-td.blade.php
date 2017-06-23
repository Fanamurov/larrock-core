<td class="row-edit uk-hidden-small">
    <a href="/admin/{{ $link or $app->name }}/{{ $data_value->id }}/edit" class="uk-button uk-button-small">Свойства</a>
</td>
<td class="row-delete uk-hidden-small">
    <form action="/admin/{{ $app->name }}/{{ $data_value->id }}" method="post">
        <input name="_method" type="hidden" value="DELETE">
        <input name="id_connect" type="hidden" value="{{ $data_value->id }}">
        <input name="type_connect" type="hidden" value="{{ $link or $app->name }}">
        {{ csrf_field() }}
        <button type="submit" class="uk-button uk-button-small uk-button-danger please_conform">Удалить</button>
    </form>
</td>