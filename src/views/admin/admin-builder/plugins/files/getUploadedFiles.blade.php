<div uk-sortable class="uk-sortable-file-plugin">
    @foreach($data as $file_item)
        <div class="uk-margin-bottom sort-item plugin-item" data-id="{{ $file_item->id }}">
            <div id="file-{{ $file_item->id }}" data-id="{{ $file_item->id }}">
                <div class="uk-text-small uk-form-row-small">
                    <span>Файл:</span> <a target="_blank" href="{{ $file_item->getUrl() }}">[{{ $file_item->file_name }} {{ $file_item->humanReadableSize }}]</a>
                </div>
                <div class="uk-form-row-small">
                    <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Галерея:</label>
                    <input class="param-file ajax_edit_media uk-input uk-form-small uk-form-width-medium" type="text"
                           value="{{ $file_item->custom_properties['gallery'] or '' }}"
                           data-model_type="{!! $file_item->model_type !!}"
                           data-id="{{ $file_item->id }}" data-row="param"
                           placeholder="Галерея">
                </div>
                <div class="uk-form-row-small">
                    <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Alt:</label>
                    <input class="description-file ajax_edit_media uk-input uk-form-small uk-form-width-medium" type="text"
                           value="{{ $file_item->custom_properties['alt'] or '' }}"
                           data-model_type="{!! $file_item->model_type !!}"
                           data-id="{{ $file_item->id }}" data-row="description"
                           placeholder="Alt/description">
                </div>
                <div class="plugin-position uk-form-row-small">
                    <label class="uk-form-label uk-display-inline-block uk-text-right" style="width: 55px">Вес:</label>
                    <input class="position-file position-input ajax_edit_media uk-input uk-form-small uk-form-width-medium" type="text"
                           value="{{ $file_item->order_column or '0' }}"
                           data-model_type="{!! $file_item->model_type !!}" data-row="order_column"
                           data-id="{{ $file_item->id }}" data-table="media" data-row_where="id" data-value_where="{{ $file_item->id }}"
                           placeholder="Вес">
                </div>
            </div>
            <div>
                <button class="uk-button uk-button-danger btn-upload-remove uk-button-small" type="button"
                        data-id="{{ $file_item->id }}"
                        data-model="{!! $file_item->model_type !!}"
                        data-model_id="{{ $file_item->model_id }}"
                        data-type="files">Удалить</button>
            </div>
            <hr/>
        </div>
    @endforeach
</div>