$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    ajax_bind_actions_files();
    FileApiFile();
});

function getUploadedFiles(model_id, model_type) {
    $.ajax({
        data: {model_id: model_id, model_type: model_type},
        type: "POST",
        url: "/admin/ajax/GetUploadedFile",
        success: function (data) {
            $('#uploadedFiles').html(data);
            ajax_bind_actions_files();
        },
        error: function () {
            alert('Не удалось загрузить прикрепленные фотографии');
        }
    });
}

function FileApiFile() {
    var choose = document.getElementById('files');

    if (FileAPI.support.dnd) {
        $('#drag-n-drop-file').show();
        $('#choose_file').dnd(function (over) {
            $('#drop-zone-file').toggle(over);
        }, function (files) {
            start_uploadFiles(files);
        });
    }

    FileAPI.event.on(choose, 'change', function (evt) {
        var files = FileAPI.getFiles(evt); // Retrieve file list
        start_uploadFiles(files);
    });
}

function start_uploadFiles(files) {
    if (files.length) {
        $('.uk-progress-upload-file').show();

        // Загружаем файлы
        FileAPI.upload({
            url: '/admin/ajax/UploadFile',
            files: {files: files},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                model_id: $('#uploadedFiles').attr('data-model_id'),
                model_type: $('#uploadedFiles').attr('data-model_type')
            },
            progress: function (evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/) {
                var pr = evt.loaded / evt.total * 100;
                $('.uk-progress-upload-file').find('.uk-progress-bar').width(pr +'%').html(pr +'%');
            },
            complete: function (err, xhr) {
                var answer = $.parseJSON(xhr.responseText);
                if (!err) {
                    // All files successfully uploaded.
                    getUploadedFiles($('#uploadedFiles').attr('data-model_id'), $('#uploadedFiles').attr('data-model_type'));
                    var countUploadedImages = parseInt($('.countUploadedFiles').html());
                    $('.countUploadedFiles').html(++countUploadedImages);
                    UIkit.notify({
                        message: '<i class="uk-icon-check"></i> ' + answer.message,
                        status: 'info',
                        timeout: 3000,
                        pos: 'top-right'
                    });
                } else {
                    UIkit.notify({
                        message: '<i class="uk-icon-bug"></i> Ошибка: ' + answer.message,
                        status: 'danger',
                        timeout: 0,
                        pos: 'top-right'
                    });
                }
                $('.uk-progress-upload-file').hide();
            }
        });
    }
}

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
            success: function () {
                $('#file-'+ id).hide('slow');
                var countUploadedImages = parseInt($('.countUploadedImages').html());
                $('.countUploadedImages').html(--countUploadedImages);
                notify_show('success', 'Файл удален');
            },
            error: function () {
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
            success: function () {
                notify_show('success', 'Данные сохранены');
            },
            error: function () {
                notify_show('danger', 'Дополнительные параметры не сохранены');
            }
        });
    });

    $('#clearFiles').click(function () {
        var model = $(this).attr('data-model_type');
        var model_id = $(this).attr('data-model_id');
        $.ajax({
            data: {model: model, model_id: model_id},
            type: "POST",
            url: "/admin/ajax/DeleteAllFilesByMaterial",
            success: function () {
                $('#uploadedFiles').html('');
                $('.countUploadedFiles').html(0);
                notify_show('success', 'Все файлы материала удалены');
            },
            error: function () {
                notify_show('danger', 'Файлы удалить не удалось');
            }
        });
    });
}