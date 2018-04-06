@if(isset($allowUpdate))
<td class="row-edit @if(isset($package->rows['title'])) uk-visible@s @endif">
    <a href="/admin/{{ $link or $package->name }}/{{ $data_value->id }}/edit" class="uk-button uk-button-default uk-button-small">Свойства</a>
</td>
@endif
@if(isset($allowDestroy))
    <td class="row-delete uk-visible@s">
        <form action="/admin/{{ $package->name }}/{{ $data_value->id }}" method="post">
            <input name="_method" type="hidden" value="DELETE">
            <input name="id_connect" type="hidden" value="{{ $data_value->id }}">
            <input name="type_connect" type="hidden" value="{{ $link or $package->name }}">
            {{ csrf_field() }}
            <button type="submit" class="uk-button uk-button-small uk-button-danger please_conform">Удалить</button>
        </form>
    </td>
@endif