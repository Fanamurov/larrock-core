$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    ajax_bind_actions();
    FileApiPhoto();
});

function getUploadedImages(model_id, model_type) {
    $.ajax({
        data: {model_id: model_id, model_type: model_type},
        type: "POST",
        url: "/admin/ajax/GetUploadedImage",
        success: function (data) {
            $('#uploadedImages').html(data);
            ajax_bind_actions();
        },
        error: function () {
            alert('Не удалось загрузить прикрепленные фотографии');
        }
    });
}

function FileApiPhoto() {
    var choose = document.getElementById('images');
    if (FileAPI.support.dnd) {
        $('#drag-n-drop-image').show();
        $('#choose').dnd(function (over) {
            $('#drop-zone-image').toggle(over);
        }, function (files) {
            start_uploadImages(files);
        });
    }

    FileAPI.event.on(choose, 'change', function (evt) {
        var files = FileAPI.getFiles(evt); // Retrieve file list
        start_uploadImages(files);
    });
}

function start_uploadImages(files) {
    var resize_original = $('input[name=resize_original]:checked').val();

    FileAPI.filterFiles(files, function (file, info/**Object*/) {
        if (/^image/.test(file.type)) {
            return info.width >= 30 && info.height >= 30;
        }
        return false;
    }, function (files/**Array*/, rejected/**Array*/) {
        $.each(rejected, function (key, value) {
            UIkit.notify({
                message: '<i class="uk-icon-bug"></i> Файл ' + value.name + ' отклонен',
                status: 'danger',
                timeout: 0,
                pos: 'top-right'
            });
        });
        if (files.length) {
            $('.uk-progress-upload-image').show();

            // Загружаем файлы
            FileAPI.upload({
                url: '/admin/ajax/UploadImage',
                files: {images: files},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    model_id: $('#uploadedImages').attr('data-model_id'),
                    model_type: $('#uploadedImages').attr('data-model_type'),
                    resize_original: resize_original
                },
                progress: function (evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/) {
                    var pr = evt.loaded / evt.total * 100;
                    $('.uk-progress-upload-image').find('.uk-progress-bar').width(pr +'%').html(pr +'%');
                },
                complete: function (err, xhr) {
                    var answer = $.parseJSON(xhr.responseText);
                    if (!err) {
                        // All files successfully uploaded.
                        getUploadedImages($('#uploadedImages').attr('data-model_id'), $('#uploadedImages').attr('data-model_type'));
                        $('#images').hide();
                        var countUploadedImages = parseInt($('.countUploadedImages').html());
                        $('.countUploadedImages').html(++countUploadedImages);
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
                    $('.uk-progress-upload-image').hide();
                }
            });
        }
    });
}

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
            success: function () {
                $('#image-'+ id).hide('slow');
                var countUploadedImages = parseInt($('.countUploadedImages').html());
                $('.countUploadedImages').html(--countUploadedImages);
                notify_show('success', 'Фото удалено');
            },
            error: function () {
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
            success: function () {
                notify_show('success', 'Данные сохранены');
            },
            error: function () {
                notify_show('danger', 'Дополнительные параметры не сохранены');
            }
        });
    });
    
    $('#clearImages').click(function () {
        var model = $(this).attr('data-model_type');
        var model_id = $(this).attr('data-model_id');
        $.ajax({
            data: {model: model, model_id: model_id},
            type: "POST",
            url: "/admin/ajax/DeleteAllImagesByMaterial",
            success: function () {
                $('#uploadedImages').html('');
                $('.countUploadedImages').html(0);
                notify_show('success', 'Все фото материала удалены');
            },
            error: function () {
                notify_show('danger', 'Фотографии удалить не удалось');
            }
        });
    });
}