@if(isset($data->getImages))
    <li id="tabimages">
        <div class="form-group">
            <label class="uk-form-label uk-width-1-1 uk-text-right" style="display: block">
                <input type="checkbox" name="resize_original" value="1"> Сжать оригинал до 800px</label>
            @if(count($data->getImages) > 0)
                <label class="uk-form-label uk-width-1-1 uk-text-right uk-margin-top" style="display: block">
                    <button id="clearImages" type="button" class="uk-button uk-button-danger"
                            data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">Удалить все фото</button>
                </label>
            @endif
            <div class="js-fileapi-wrapper upload-btn" id="choose">
                <div class="upload-btn__txt">Выберите файлы для загрузки</div>
                <input id="images" name="files" type="file" accept="image/*" multiple />
                <div id="drag-n-drop-image" class="drag-n-drop-message" style="display: none">или перетащите сюда файлы мышкой</div>
            </div>
            <div id="drop-zone-image" class="b-dropzone" style="display: none">
                <div class="b-dropzone__bg"></div>
                <div class="b-dropzone__txt">Вставка изображений</div>
            </div>
            <div class="uk-progress uk-progress-striped uk-active uk-progress-upload-image" style="display: none">
                <div class="uk-progress-bar" style="width: 40%;">40%</div>
            </div>
            <div id="images">
                <!-- предпросмотр -->
            </div>
            <div id="uploadedImages" data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">
                @include('larrock::admin.admin-builder.plugins.images.getUploadedImages', ['data' => $data->getImages, 'app' => $app])
            </div>
        </div>
    </li>
@else
    <p class="uk-alert uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif