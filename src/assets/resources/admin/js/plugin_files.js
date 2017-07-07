$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    ajax_bind_actions_files();

    function getUploadedFiles(model_id, model_type, el)
    {
        $.ajax({
            data: {model_id: model_id, model_type: model_type},
            type: "POST",
            url: "/admin/ajax/GetUploadedFile",
            success: function (data) {
                $('#uploadedFiles').html(data);
                ajax_bind_actions_files();
                el.slideUp('slow');
            },
            error: function (data) {
                alert('Не удалось загрузить прикрепленные файлы');
            }
        });
    }

    load_files_plugin();
    function load_files_plugin()
    {
        if ($.fn.filer) {
            /* Image upload http://filer.grandesign.md/#download */
            $('#upload_file_filer').filer({
                changeInput: '<div class="jFiler-input-dragDrop">' +
                '<div class="jFiler-input-inner">' +
                '<div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div>' +
                '<div class="jFiler-input-text"><h3>Перетащите файл сюда</h3> ' +
                '<span style="display:inline-block; margin: 15px 0">или</span></div>' +
                '<a class="jFiler-input-choose-btn blue">Выберите файл из проводника</a></div></div>',
                showThumbs: true,
                theme: "dragdropbox",
                addMore: true,
                files: [],
                templates: {
                    box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                    item: '<li class="jFiler-item col-xs-12">\
                    <div class="jFiler-item-container">\
                        <div class="jFiler-item-inner">\
                            <div class="jFiler-item-thumb col-xs-2">\
                                <div class="jFiler-item-status"></div>\
                                <div class="jFiler-item-info">\
                                    <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                    <span class="jFiler-item-others">{{fi-size2}}</span>\
                                </div>\
                                {{fi-image}}\
                            </div>\
                            <div class="jFiler-item-params col-xs-9" data-file="{{fi-name}}">\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                </div>\
                            </div>\
                            <div class="jFiler-item-assets jFiler-row col-xs-1">\
                            </div>\
                        </div>\
                    </div>\
                </li>',
                    progressBar: '<div class="bar"></div>',
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
                    url: "/admin/ajax/UploadFile",
                    data: {
                        model_id: $('#uploadedFiles').attr('data-model_id'),
                        model_type: $('#uploadedFiles').attr('data-model_type')
                    },
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    beforeSend: function(){},
                    success: function(data, el){
                        var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Загружено</div>").hide().appendTo(parent).fadeIn("slow");
                            getUploadedFiles($('#uploadedFiles').attr('data-model_id'), $('#uploadedFiles').attr('data-model_type'), el);
                        });
                    },
                    error: function(el){
                        var parent = el.find(".jFiler-jProgressBar").parent();
                        el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                            $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Ошибка</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    },
                    statusCode: null,
                    onProgress: null,
                    onComplete: null
                },
                captions: {
                    button: "Choose Files",
                    feedback: "Choose files To Upload",
                    feedback2: "files were chosen",
                    drop: "Drop file here to Upload",
                    removeConfirmation: "Are you sure you want to remove this file?",
                    errors: {
                        filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                        filesType: "Only Images are allowed to be uploaded.",
                        filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                        filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
                    }
                },
                afterShow: null
            });
        }
    }
    /* END FILE PLUGIN UPLOADED */
});

function ajax_bind_actions_files()
{
    $('.btn_delete_file').click(function(){
        var id = $(this).attr('data-id');
        var model = $(this).attr('data-model');
        var model_id = $(this).attr('data-model_id');
        $.ajax({
            data: {id: id, model: model, model_id: model_id},
            type: "POST",
            url: "/admin/ajax/DeleteUploadedFile",
            success: function (data) {
                $('#file-'+ id).hide('slow');
                notify_show('success', 'Файл удален');
            },
            error: function (data) {
                notify_show('danger', 'Файл не удален');
            }
        });
    });

    $('.ajax_edit_media_files').change(function(){
        var id = $(this).attr('data-id');
        var alt = $('#file-'+ id).find('.description-file').val();
        var gallery = $('#file-'+ id).find('.param-file').val();
        var position = $('#file-'+ id).find('.position-file').val();
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