@if(isset($data->getImages))
    <li id="tabimages" class="uk-form">
        <div class="form-group">
            <label class="uk-form-label uk-width-1-1">
                <input type="checkbox" name="resize_original" value="1"> Сжать оригинал до (px)
                <input type="text" class="uk-input" value="800" name="resize_original_px">
            </label>
            <label class="uk-form-label uk-width-1-1">
                Группа для галереи
                <input type="text" class="uk-input" value="" name="gallery_img" placeholder="По-умолчанию - url">
            </label>
            @if(count($data->getImages) > 0)
                <button id="clearImages" type="button" class="uk-button uk-button-danger uk-width-1-1 clearMedia"
                        data-model_id="{{ $data->id }}" data-model_type="{{ $package->model }}" data-type="images">Удалить все фото</button>
            @endif
            <div class="js-fileapi-wrapper upload-btn" id="choose">
                <div class="upload-btn__txt">Выберите файлы для загрузки</div>
                <input id="images" name="files" type="file" accept="image/*" multiple />
                <div id="drag-n-drop-image" class="drag-n-drop-message">или перетащите сюда файлы мышкой</div>
            </div>
            <div class="uk-progress uk-progress-striped uk-active uk-progress-upload-image" style="display: none">
                <progress id="js-progressbar-upload-image" class="uk-progress" value="10" max="100"></progress>
            </div>
            <div id="images"><!-- предпросмотр --></div>
            <div id="uploadedImages" data-model_id="{{ $data->id }}" data-model_type="{{ $package->model }}">
                @include('larrock::admin.admin-builder.plugins.images.getUploadedImages', ['data' => $data->getImages, 'app' => $app])
            </div>
        </div>
    </li>
@else
    <p uk-alert class="uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif