<div class="uk-sortable uk-sortable-file-plugin" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
    @foreach($data as $file_item)
        <div class="uk-grid uk-form uk-form-stacked" id="file-{{ $file_item->id }}">
            <div class="uk-width-1-2 uk-width-medium-1-10">
                <i class="uk-icon-file"></i>
            </div>
            <div class="uk-width-1-2 uk-width-medium-8-10">
                <div class="uk-grid">
                    <div class="uk-width-1-1 uk-width-medium-3-10">
                        <label>Группа для галереи:</label>
                        <input class="uk-width-1-1 param-file ajax_edit_media_files" type="text" value="{{ $file_item->custom_properties['gallery'] or '' }}"
                               data-model_type="{!! $file_item->model_type !!}"
                               data-id="{{ $file_item->id }}" data-row="param"
                               placeholder="Галерея">
                    </div>
                    <div class="uk-width-1-1 uk-width-medium-5-10">
                        <label>Alt/description:</label>
                        <input class="uk-width-1-1 description-file ajax_edit_media_files" type="text" value="{{ $file_item->custom_properties['alt'] or '' }}"
                               data-model_type="{!! $file_item->model_type !!}"
                               data-id="{{ $file_item->id }}" data-row="description"
                               placeholder="Alt/description">
                    </div>
                    <div class="uk-width-1-1 uk-width-medium-2-10 plugin-position">
                        <label>Вес:</label>
                        <input class="uk-width-1-1 position-file ajax_edit_media_files" type="text" value="{{ $file_item->order_column or '0' }}"
                               data-model_type="{!! $file_item->model_type !!}"
                               data-id="{{ $file_item->id }}" data-row="position"
                               placeholder="Вес">
                        <i class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right" title="Перенести материал по весу"></i>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <span>Url:</span> <a target="_blank" href="{{ $file_item->getUrl() }}">{{ $file_item->getUrl() }}</a> [{{ $file_item->humanReadableSize }}]
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-medium-1-10" style="position: relative;">
                <button class="uk-button uk-button-danger btn_delete_file" type="button" data-id="{{ $file_item->id }}"
                        data-model="{!! $file_item->model_type !!}"
                        data-model_id="{{ $file_item->model_id }}">Удалить</button>
            </div>
        </div>
        <br/><br/>
    @endforeach
</div>