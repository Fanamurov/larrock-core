@if(isset($data->getFiles))
    <li id="tabfiles">
        <div class="form-group">
            <label class="uk-form-label uk-width-1-1">
                Группа для галереи
                <input type="text" class="uk-input" value="" name="gallery_file" placeholder="По-умолчанию - url">
            </label>
            @if(count($data->getFiles) > 0)
                <button id="clearFiles" type="button" class="uk-button uk-button-danger uk-width-1-1 clearMedia"
                        data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}" data-type="files">Удалить все файлы</button>
            @endif
            <div class="js-fileapi-wrapper upload-btn" id="choose_file">
                <div class="upload-btn__txt">Выберите файлы для загрузки</div>
                <input id="files" name="files" type="file" multiple />
                <div id="drag-n-drop-file" class="drag-n-drop-message">или перетащите сюда файлы мышкой</div>
            </div>
            <div class="uk-progress uk-progress-striped uk-active uk-progress-upload-file" style="display: none">
                <progress id="js-progressbar-upload-file" class="uk-progress" value="10" max="100"></progress>
            </div>
            <div id="uploadedFiles" data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">
                @include('larrock::admin.admin-builder.plugins.files.getUploadedFiles', ['data' => $data->getFiles, 'app' => $app])
            </div>
        </div>
    </li>
@else
    <p uk-alert class="uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif