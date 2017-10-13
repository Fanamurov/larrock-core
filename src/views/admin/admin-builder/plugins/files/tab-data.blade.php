@if(isset($data->getFiles))
    <li id="tabfiles">
        <div class="form-group">
            <label class="uk-form-label uk-width-1-1 uk-text-right" style="display: block">
            @if(count($data->getFiles) > 0)
                <label class="uk-form-label uk-width-1-1 uk-text-right uk-margin-top" style="display: block">
                    <button id="clearFiles" type="button" class="uk-button uk-button-danger"
                            data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">Удалить все файлы</button>
                </label>
            @endif
            <div class="js-fileapi-wrapper upload-btn" id="choose_file">
                <div class="upload-btn__txt">Выберите файлы для загрузки</div>
                <input id="files" name="files" type="file" multiple />
                <div id="drag-n-drop-file" class="drag-n-drop-message" style="display: none">или перетащите сюда файлы мышкой</div>
            </div>
            <div id="drop-zone-file" class="b-dropzone" style="display: none">
                <div class="b-dropzone__bg"></div>
                <div class="b-dropzone__txt">Вставка файлов</div>
            </div>
            <div class="uk-progress uk-progress-striped uk-active uk-progress-upload-file" style="display: none">
                <div class="uk-progress-bar" style="width: 40%;">40%</div>
            </div>
            <div id="uploadedFiles" data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">
                @include('larrock::admin.admin-builder.plugins.files.getUploadedFiles', ['data' => $data->getFiles, 'app' => $app])
            </div>
        </div>
    </li>
@else
    <p class="uk-alert uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif