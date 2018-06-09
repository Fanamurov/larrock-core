<div uk-sortable class="uk-sortable-img-plugin">
    @foreach($data as $image_value)
        <div class="uk-margin-bottom sort-item plugin-item" data-id="{{ $image_value->id }}">
            <div uk-grid class="uk-grid uk-grid-small" id="image-{{ $image_value->id }}" data-id="{{ $image_value->id }}">
                <div class="uk-width-auto">
                    <a data-fancybox data-caption="{{ $image_value->custom_properties['gallery'] or '' }}" href="{{ $image_value->getUrl() }}">
                        <img src="{{ $image_value->getUrl('110x110') }}" alt="Фото" class="plugin-image"></a>
                    <div class="uk-clearfix"></div>
                    <button class="uk-button uk-button-danger uk-button-small btn-upload-remove uk-margin-top" type="button"
                            data-id="{{ $image_value->id }}"
                            data-model="{!! $image_value->model_type !!}"
                            data-model_id="{{ $image_value->model_id }}"
                            data-type="images">Удалить</button>
                    <div class="uk-clearfix"></div>
                    <small class="uk-text-center uk-display-block">{{ $image_value->humanReadableSize }}</small>
                </div>
                <div class="uk-width-expand">
                    <div class="uk-form-row-small uk-text-small">
                        <a target="_blank" href="{{ $image_value->getUrl() }}">{{ $image_value->file_name }}</a><br/>
                        @foreach($image_value->getMediaConversionNames() as $conv)
                            <a href="{{ $image_value->getUrl($conv) }}" target="_blank">[{{ $conv }}]</a>
                        @endforeach
                    </div>
                    <div class="uk-form-row-small">
                        <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Галерея:</label>
                        <input class="param-file ajax_edit_media uk-input uk-form-small uk-form-width-small" type="text"
                               value="{{ $image_value->custom_properties['gallery'] or '' }}"
                               data-model_type="{!! $image_value->model_type !!}"
                               data-id="{{ $image_value->id }}" data-row="param"
                               placeholder="Галерея">
                    </div>
                    <div class="uk-form-row-small">
                        <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Alt:</label>
                        <input class="description-file ajax_edit_media uk-input uk-form-small uk-form-width-small" type="text"
                               value="{{ $image_value->custom_properties['alt'] or '' }}"
                               data-model_type="{!! $image_value->model_type !!}"
                               data-id="{{ $image_value->id }}" data-row="description"
                               placeholder="Alt/description">
                    </div>
                    <div class="plugin-position uk-form-row-small">
                        <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Вес:</label>
                        <input class="position-file position-input ajax_edit_media uk-input uk-form-small uk-form-width-small" type="text"
                               value="{{ $image_value->order_column or '0' }}"
                               data-model_type="{!! $image_value->model_type !!}" data-row="order_column"
                               data-id="{{ $image_value->id }}" data-table="media" data-row_where="id" data-value_where="{{ $image_value->id }}"
                               placeholder="Вес">
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    @endforeach
</div>