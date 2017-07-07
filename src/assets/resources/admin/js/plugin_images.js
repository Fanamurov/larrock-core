$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    ajax_bind_actions();

    function getUploadedImages(model_id, model_type, el)
    {
        $.ajax({
            data: {model_id: model_id, model_type: model_type},
            type: "POST",
            url: "/admin/ajax/GetUploadedImage",
            success: function (data) {
                $('#uploadedImages').html(data);
                ajax_bind_actions();
                el.slideUp('slow');
            },
            error: function (data) {
                alert('Не удалось загрузить прикрепленные фотографии');
            }
        });
    }

    load_image_plugin();
    function load_image_plugin()
    {
        if ($.fn.filer) {
            /* Image upload http://filer.grandesign.md/#download */
            $('#upload_image_filer').filer({
                changeInput: '<div class="jFiler-input-dragDrop">' +
                '<div class="jFiler-input-inner">' +
                '<div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div>' +
                '<div class="jFiler-input-text"><h3>Перетащите фото сюда</h3> ' +
                '<span style="display:inline-block; margin: 15px 0">или</span></div>' +
                '<a class="jFiler-input-choose-btn blue">Выберите фото из проводника</a></div></div>',
                showThumbs: true,
                theme: "dragdropbox",
                addMore: true,
                files: [],
                templates: {
                    box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                    item: '<li class="jFiler-item uk-width-2-10">\
                    <div class="jFiler-item-container">\
                        <div class="jFiler-item-inner uk-thumbnail">\
                            <div class="jFiler-item-thumb uk-width-2-10">\
                                <div class="jFiler-item-status"></div>\
                                {{fi-image}}\
                            </div>\
                            <div class="jFiler-item-params col-xs-9" data-image="{{fi-name}}">\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul>\
                                        <li>Загрузка фото {{fi-name | limitTo: 25}} [{{fi-size2}}]: {{fi-progressBar}}</li>\
                                        <li><i class="uk-icon-spin uk-icon-refresh"></i> Обработка фото</li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </li>',
                    progressBar: '<div class="bar">Загрузка</div>',
                    itemAppendToEnd: false,
                    removeConfirmation: true,
                    _selectors: {
                        list: '.jFiler-items-list',
                        item: '.jFiler-item',
                        progressBar: '.bar',
                        remove: '.jFiler-item-trash-action'
                    }
                },
                dragDrop: {
                    dragEnter: null,
                    dragLeave: null,
                    drop: null
                },
                uploadFile: {
                    url: "/admin/ajax/UploadImage",
                    data: {
                        model_id: $('#uploadedImages').attr('data-model_id'),
                        model_type: $('#uploadedImages').attr('data-model_type'),
                        resize_original: $('input[name=resize_original]').val(),
                    },
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    success: function(data, el){
                        //var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").find('.bar').addClass('waiting').html('Обработка фото');
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            //$("<div class=\"jFiler-item-others uk-text-success\"><i class=\"uk-icon-check\"></i> Загружено</div>").hide().appendTo(parent).fadeIn("slow");
                            getUploadedImages($('#uploadedImages').attr('data-model_id'), $('#uploadedImages').attr('data-model_type'), el);
                        });
                    },
                    error: function(el){
                        var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            $("<div class=\"jFiler-item-others text-error\"><i class=\"uk-icon-bug\"></i> Ошибка</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    },
                    statusCode: null,
                    onProgress: function () {
                    },
                    onComplete: function () {
                        UIkit.notify({
                            message : '<i class="uk-icon-check"></i> Загрузка файлов окончена',
                            status  : 'success',
                            timeout : 0,
                            pos     : 'top-right'
                        });
                    }
                },
                captions: {
                    button: "Choose Files",
                    feedback: "Choose files To Upload",
                    feedback2: "files were chosen",
                    drop: "Drop file here to Upload",
                    removeConfirmation: "Are you sure you want to remove this file?",
                    errors: {
                        filesLimit: "Только {{fi-limit}} файлов может быть загружено.",
                        filesType: "Для загрузки разрешены только файлы картинок.",
                        filesSize: "{{fi-name}} слишком большой! Введено ограничение на размер файла в {{fi-fileMaxSize}} MB.",
                        filesSizeAll: "Общий объем файлов больше разрешенного! Пожалуйста, загружайте за раз не более {{fi-maxSize}} MB."
                    }
                },
                afterShow: null
            });
        }
    }
    /* End image upload */
});

function ajax_bind_actions()
{
    $('.btn_delete_image').click(function(){
        var id = $(this).attr('data-id');
        var model = $(this).attr('data-model');
        var model_id = $(this).attr('data-model_id');
        $.ajax({
            data: {id: id, model: model, model_id: model_id},
            type: "POST",
            url: "/admin/ajax/DeleteUploadedImage",
            success: function (data) {
                $('#image-'+ id).hide('slow');
                notify_show('success', 'Фото удалено');
            },
            error: function (data) {
                notify_show('danger', 'Фото не удалено');
            }
        });
    });

    $('.ajax_edit_media').change(function(){
        var id = $(this).attr('data-id');
        var alt = $('#image-'+ id).find('.description-image').val();
        var gallery = $('#image-'+ id).find('.param-image').val();
        var position = $('#image-'+ id).find('.position-image').val();
        $.ajax({
            data: {alt: alt, gallery: gallery, position: position, id: id},
            type: "POST",
            url: "/admin/ajax/CustomProperties",
            success: function (data) {
                notify_show('success', 'Данные сохранены');
            },
            error: function (data) {
                notify_show('danger', 'Дополнительные параметры не сохранены');
            }
        });
    });
}