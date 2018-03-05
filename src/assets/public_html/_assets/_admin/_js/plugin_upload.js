$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (typeof FileAPI.support !== 'undefined') {
        FileApiInit('images');
        FileApiInit('files');
    }
    ajaxBindActions();
});

/**
 * Получение загруженных медиа-файлов
 * @param model_id
 * @param model_type
 * @param type    images|files
 */
function getUploadedMedia(model_id, model_type, type) {
    $.ajax({
        data: {model_id: model_id, model_type: model_type, type: type},
        type: "POST",
        url: "/admin/ajax/GetUploadedMedia",
        success: function (data) {
            if(type === 'images'){
                $('#uploadedImages').html(data);
            }
            if(type === 'files'){
                $('#uploadedFiles').html(data);
            }
            ajaxBindActions();
        },
        error: function () {
            if(type === 'images'){
                alert('Не удалось загрузить прикрепленные фотографии');
            }
            if(type === 'files'){
                alert('Не удалось загрузить прикрепленные фотографии');
            }
        }
    });
}

/**
 * Инициализация загрузчика медиа-файлов
 * @param type  images|files
 */
function FileApiInit(type) {
    var choose = document.getElementById(type);
    if(type === 'images'){
        if (FileAPI.support.dnd) {
            $('#drag-n-drop-image').show();
            $('#choose').dnd(function (over) {
                $('.js-fileapi-wrapper').toggleClass('dragged');
            }, function (files) {
                startUploadImages(files);
            });
        }
        FileAPI.event.on(choose, 'change', function (evt) {
            var files = FileAPI.getFiles(evt); // Retrieve file list
            startUploadImages(files);
        });
    }
    if(type === 'files'){
        if (FileAPI.support.dnd) {
            $('#drag-n-drop-file').show();
            $('#choose_file').dnd(function (over) {
                $('.js-fileapi-wrapper').toggleClass('dragged');
            }, function (files) {
                startUploadFiles(files);
            });
        }

        FileAPI.event.on(choose, 'change', function (evt) {
            var files = FileAPI.getFiles(evt); // Retrieve file list
            startUploadFiles(files);
        });
        FileAPI.event.on(choose, 'change', function (evt) {
            var files = FileAPI.getFiles(evt); // Retrieve file list
            startUploadFiles(files);
        });
    }
}

function startUploadImages(files) {
    var resize_original = $('input[name=resize_original]:checked').val();

    FileAPI.filterFiles(files, function (file, info/**Object*/) {
        if (/^image/.test(file.type)) {
            return info.width >= 30 && info.height >= 30
                && (file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/gif');
        }
        return false;
    }, function (files/**Array*/, rejected/**Array*/) {
        $.each(rejected, function (key, value) {
            UIkit.notification({
                message: '<span uk-icon="warning"></span> Файл ' + value.name + ' отклонен',
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
                    resize_original: resize_original,
                    resize_original_px: $('input[name=resize_original_px]').val(),
                    gallery: $('input[name=gallery_img]').val()
                },
                progress: function (evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/) {
                    var bar = document.getElementById('js-progressbar-upload-image');
                    var animate = setInterval(function () {
                        bar.value = evt.loaded / evt.total * 100;
                        if (bar.value >= bar.max) {
                            clearInterval(animate);
                        }
                    }, 1000);
                },
                complete: function (err, xhr) {
                    var answer = $.parseJSON(xhr.responseText);
                    if (!err) {
                        // All files successfully uploaded.
                        getUploadedMedia($('#uploadedImages').attr('data-model_id'), $('#uploadedImages').attr('data-model_type'), 'images');
                        $('#images').hide();
                        var countUploadedImages = parseInt($('.countUploadedImages').html());
                        $('.countUploadedImages').html(++countUploadedImages);
                        UIkit.notification({
                            message: '<i uk-icon="icon: check"></i> ' + answer.message,
                            status: 'info',
                            timeout: 3000,
                            pos: 'top-right'
                        });
                    } else {
                        UIkit.notification({
                            message: '<i uk-icon="icon: warning"></i> Ошибка: ' + answer.message,
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

function startUploadFiles(files) {
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
                model_type: $('#uploadedFiles').attr('data-model_type'),
                gallery: $('input[name=gallery_file]').val()
            },
            progress: function (evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/) {
                var bar = document.getElementById('js-progressbar-upload-file');
                var animate = setInterval(function () {
                    bar.value = evt.loaded / evt.total * 100;
                    if (bar.value >= bar.max) {
                        clearInterval(animate);
                    }
                }, 1000);
            },
            complete: function (err, xhr) {
                var answer = $.parseJSON(xhr.responseText);
                if (!err) {
                    // All files successfully uploaded.
                    getUploadedMedia($('#uploadedFiles').attr('data-model_id'), $('#uploadedFiles').attr('data-model_type'), 'files');
                    var countUploadedFiles = parseInt($('.countUploadedFiles').html());
                    $('.countUploadedFiles').html(++countUploadedFiles);
                    UIkit.notification({
                        message: '<i uk-icon="icon: check"></i> ' + answer.message,
                        status: 'info',
                        timeout: 3000,
                        pos: 'top-right'
                    });
                } else {
                    UIkit.notification({
                        message: '<i uk-icon="icon: warning"></i> Ошибка: ' + answer.message,
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

function ajaxBindActions(){
    $('.btn-upload-remove').click(function(){
        var id = $(this).attr('data-id');
        var model = $(this).attr('data-model');
        var model_id = $(this).attr('data-model_id');
        var type = $(this).attr('data-type');
        $.ajax({
            data: {id: id, model: model, model_id: model_id, type: type},
            type: "POST",
            url: "/admin/ajax/DeleteUploadedMedia",
            success: function () {
                if(type === 'images'){
                    $('#image-'+ id).hide('slow');
                    var countUploadedImages = parseInt($('.countUploadedImages').html());
                    $('.countUploadedImages').html(--countUploadedImages);
                    notify_show('success', 'Фото удалено');
                }
                if(type === 'files'){
                    $('#file-'+ id).hide('slow');
                    var countUploadedFiles = parseInt($('.countUploadedFiles').html());
                    $('.countUploadedFiles').html(--countUploadedFiles);
                    notify_show('success', 'Файл удален');
                }
            },
            error: function () {
                if(type === 'images'){
                    notify_show('danger', 'Фото не удалено');
                }
                if(type === 'files'){
                    notify_show('danger', 'Файл не удален');
                }
            }
        });
    });

    $('.ajax_edit_media').change(function(){
        var id = $(this).attr('data-id');
        var block = $(this).parentsUntil('.plugin-item');
        var alt = $(block).find('.description-image').val();
        var gallery = $(block).find('.param-image').val();
        var position = $(block).find('.position-image').val();
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

    $('.clearMedia').click(function () {
        var model = $(this).attr('data-model_type');
        var model_id = $(this).attr('data-model_id');
        var type = $(this).attr('data-type');
        $.ajax({
            data: {model: model, model_id: model_id, type: type},
            type: "POST",
            url: "/admin/ajax/DeleteAllUploadedMediaByType",
            success: function () {
                if(type === 'images'){
                    $('#uploadedImages').html('');
                    $('.countUploadedImages').html(0);
                    notify_show('success', 'Все фото материала удалены');
                }
                if(type === 'files'){
                    $('#uploadedFiles').html('');
                    $('.countUploadedFiles').html(0);
                    notify_show('success', 'Все файлы материала удалены');
                }
            },
            error: function () {
                if(type === 'images'){
                    notify_show('danger', 'Фотографии удалить не удалось');
                }
                if(type === 'files'){
                    notify_show('danger', 'Файлы удалить не удалось');
                }
            }
        });
    });
}