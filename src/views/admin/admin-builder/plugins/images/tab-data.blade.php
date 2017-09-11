@if(isset($data->getImages))
    <li id="tabimages">
        <div class="form-group">
            <label class="uk-form-label uk-width-1-1 uk-text-right" style="display: block"><input type="checkbox" name="resize_original" value="1" checked> Сжать оригинал до 800px</label>
            <form action="/admin/ajax/UploadImage" method="post" enctype="multipart/form-data" id="plugin_image">
                <input type="file" name="images[]" id="upload_image_filer" multiple="multiple">
                <input type="submit" value="Submit" class="uk-button uk-button-primary uk-hidden">
            </form>
            <div id="uploadedImages" data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">
                @include('larrock::admin.admin-builder.plugins.images.getUploadedImages', ['data' => $data->getImages, 'app' => $app])
            </div>
        </div>
    </li>
@else
    <p class="uk-alert uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif