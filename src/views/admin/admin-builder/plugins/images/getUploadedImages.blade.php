<div class="uk-sortable uk-sortable-img-plugin" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
    @foreach($data as $image_value)
        <div class="uk-grid uk-form uk-form-stacked" id="image-{{ $image_value->id }}">
            <div class="uk-width-1-2 uk-width-medium-1-10">
                <img src="{{ $image_value->getUrl('110x110') }}" alt="Фото" class="all-width uk-text-right">
            </div>
            <div class="uk-width-1-2 uk-width-medium-8-10">
                <div class="uk-grid">
                    <div class="uk-width-1-1 uk-width-medium-3-10">
                        <label>Группа для галереи:</label>
                        <input class="uk-width-1-1 param-image ajax_edit_media" type="text" value="{{ $image_value->custom_properties['gallery'] or '' }}"
                               data-model_type="{!! $image_value->model_type !!}"
                               data-id="{{ $image_value->id }}" data-row="param"
                               placeholder="Галерея">
                    </div>
                    <div class="uk-width-1-1 uk-width-medium-5-10">
                        <label>Alt/description:</label>
                        <input class="uk-width-1-1 description-image ajax_edit_media" type="text" value="{{ $image_value->custom_properties['alt'] or '' }}"
                               data-model_type="{!! $image_value->model_type !!}"
                               data-id="{{ $image_value->id }}" data-row="description"
                               placeholder="Alt/description">
                    </div>
                    <div class="uk-width-1-1 uk-width-medium-2-10 plugin-position">
                        <label>Вес:</label>
                        <input class="uk-width-1-1 position-image ajax_edit_media" type="text" value="{{ $image_value->order_column or '0' }}"
                               data-model_type="{!! $image_value->model_type !!}"
                               data-id="{{ $image_value->id }}" data-row="position"
                               placeholder="Вес">
                        <i class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right" title="Перенести материал по весу"></i>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <span>Url:</span> <a target="_blank" href="{{ $image_value->getUrl() }}">{{ $image_value->getUrl() }}</a> [{{ $image_value->humanReadableSize }}]
                    @foreach($image_value->getMediaConversionNames() as $conv)
                        <a href="{{ $image_value->getUrl($conv) }}" target="_blank">[{{ $conv }}]</a>
                    @endforeach
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-medium-1-10" style="position: relative;">
                <button class="uk-button uk-button-danger btn_delete_image" type="button" data-id="{{ $image_value->id }}"
                        data-model="{!! $image_value->model_type !!}"
                        data-model_id="{{ $image_value->model_id }}">Удалить</button>
            </div>
        </div>
        <br/><br/>
    @endforeach
</div>