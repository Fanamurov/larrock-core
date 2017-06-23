<div class="uk-form-row form-group-{{ $row_key }} {{ \Illuminate\Support\Arr::get($row_settings, 'css_class_group') }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    @foreach($data->items as $item)
        <div class="row">
            <div class="col-xs-2">
                <img class="all-width" src="/media/Catalog/{{ $item->image->name }}/140x140.jpg" alt='{{ $item->name }}'>
            </div>
            <div class="col-xs-8">
                <p class="h3">{{ $item->name }}</p>
                @foreach($item->options as $key_option => $value_option)
                    <p><small class="text-muted">{{ $key_option }}:</small> {{ $value_option }}</p>
                @endforeach
                <p>{{ $data->comment }}</p>
                <p>{{ $item->qty }} шт. X {{ $item->price }} = {{ $item->subtotal }} руб.</p>
            </div>
            <div class="col-xs-2">
                <button type="button" class="btn btn-danger">Удалить</button>
            </div>
        </div>
        <hr/>
    @endforeach
    <button type="button" class="btn btn-default">Добавить товар</button>
</div>