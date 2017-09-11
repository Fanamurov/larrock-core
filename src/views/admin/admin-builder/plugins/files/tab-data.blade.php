@if(isset($data->getFiles))
    <li class="tab-pane" id="tabfiles">
        <div class="form-group">
            <form action="/admin/ajax/UploadFile" method="post" enctype="multipart/form-data" id="plugin_file">
                <input type="file" name="files[]" id="upload_file_filer" multiple="multiple">
                <input type="submit" value="Submit" class="uk-button uk-button-primary uk-hidden">
            </form>
            <div id="uploadedFiles" data-model_id="{{ $data->id }}" data-model_type="{{ $app->model }}">
                @include('larrock::admin.admin-builder.plugins.files.getUploadedFiles', ['data' => $data->getFiles])
            </div>
        </div>
    </li>
@else
    <p class="uk-alert uk-alert-danger">В конфиге компонента указан этот плагин, но данные для него не получены, используйте метод getFiles</p>
@endif