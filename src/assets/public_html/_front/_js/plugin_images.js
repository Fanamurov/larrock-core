$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('input[name=orientation]').change(function(){
        var tab = $(this).attr('data-tab');
        $('.sizes').each(function () {
            var h = parseInt($(this).attr('data-h'));
            var w = parseInt($(this).attr('data-w'));

            $(this).attr('data-h', w);
            $(this).attr('data-w', h);
            $(this).html(h +'x'+ w);
        });
        $('.cropper-tab-'+ tab).find('.sizes.active').trigger('click');
    });


    $('select#selected_baget').change(function () {
        $.ajax({
            data: {in_template: false, id: $(this).val()},
            type: "POST",
            url: "/ajax/getTovar",
            dataType: 'json',
            success: function (data) {
                $('input[name=cost]').val(data.cost);
                $('input[name=shirina]').val(parseFloat(data.razmer_h/100));
                $('input[name=total_cost]').val(data.cost);

                var image_h = $('input[name=image_h]').val();
                var image_w = $('input[name=image_w]').val();

                $('input[name=border_h]').val(parseFloat(image_h) + parseFloat(data.razmer_h));
                $('input[name=border_w]').val(parseFloat(image_w) + parseFloat(data.razmer_w));

                $('.bagetImages').find('img').attr('src', data.first_image);

                $('.baget-preview').attr('data-baget_h', data.baget_h);
                $('.baget-preview').attr('data-razmer_h', data.razmer_h);
                $('.baget-preview').attr('data-baget_image', data.first_image);

                calc_rama();
                baget_size();
            },
            error: function () {
                alert('Не удалось найти товар');
            }
        });
    });

    function change_input_baget_image_size() {
        var baget_h = parseFloat($('.baget-preview').attr('data-razmer_h'));

        var image_h = parseFloat($('input[name=image_h]').val());
        var image_w = parseFloat($('input[name=image_w]').val());

        if(image_h > 10 && image_w > 10){
            $('input[name=border_h]').val(image_h+baget_h);
            $('input[name=border_w]').val(image_w+baget_h);
            baget_size();
            calc_rama();
            $('button.buttom-order-rama').removeAttr('disabled');
            $('.alert-razmer').addClass('uk-hidden');
        }else{
            $('button.buttom-order-rama').attr('disabled', 'disabled');
            $('.alert-razmer').removeClass('uk-hidden');
        }
    }

    $('input[name=image_h], input[name=image_w]').change(function(){ change_input_baget_image_size()});

    function change_input_baget_border_size() {
        var baget_h = parseFloat($('.baget-preview').attr('data-razmer_h'));

        var border_h = parseFloat($('input[name=border_h]').val());
        var border_w = parseFloat($('input[name=border_w]').val());

        if(border_h-baget_h > 10 && border_w-baget_h > 10){
            $('input[name=image_h]').val(border_h-baget_h);
            $('input[name=image_w]').val(border_w-baget_h);
            baget_size();
            calc_rama();
            $('button.buttom-order-rama').removeAttr('disabled');
            $('.alert-razmer').addClass('uk-hidden');
        }else{
            $('button.buttom-order-rama').attr('disabled', 'disabled');
            $('.alert-razmer').removeClass('uk-hidden');
        }
    }

    $('input[name=border_h], input[name=border_w]').change(function(){ change_input_baget_border_size()});

    function baget_size() {
        var razmer_h = parseFloat($('.baget-preview').attr('data-razmer_h'));
        var baget_h = parseFloat($('.baget-preview').attr('data-baget_h'));
        var baget_w_px = $('.baget-preview').width();
        var border_h = $('input[name=border_h]').val();
        var border_w = $('input[name=border_w]').val();
        var aspect_border = parseFloat(border_h)/parseFloat(border_w);
        var baget_h_px = baget_w_px * aspect_border;
        if(baget_h_px < 10){
            baget_h_px = 50;
        }
        var baget_image = 'url('+ $('.baget-preview').attr('data-baget_image') +')';

        var shirina = parseFloat($('input[name=shirina]').val()) * 100;


        $('.baget-preview').css('height', baget_h_px +'px')
            .css('border-style', 'solid')
            .css('border-width', razmer_h/(border_h/baget_h_px) +'px')
            .css('border-image', baget_image+' '+ baget_h +' round round');
    }
    baget_size();

    $('#removeAllUploadedImages').click(function(){
        $.ajax({
            type: "POST",
            url: "/photo/removeAllUploadedImages",
            success: function () {
                $('#uploadedImages').html('');
                notify_show('success', 'Все фото удалены');
            },
            error: function () {
                alert('Не удалось удалить прикрепленные фотографии');
            }
        });
    });

    $('.applyToAll').click(function () {
        var tab = $(this).attr('data-key');
        $('.cropper-tab-'+tab).find('select').each(function(){
            var name_select = $(this).attr('name');
            var text_select = $(this).find('option:selected').val();
            $('select[name='+ name_select +']').find('option[value="'+ text_select +'"]').attr("selected", "selected");
        });
        var active_h = $('.cropper-tab-'+tab).find('.sizes.active').attr('data-h');
        var active_w = $('.cropper-tab-'+tab).find('.sizes.active').attr('data-w');
        $('.sizes[data-h="'+ active_h +'"][data-w="'+ active_w +'"]').trigger('click');
    });

    $('select[name=delivery-method]').change(function () {
        if($(this).val() !== 'Самовывоз'){
            $('.row-address-delivery').removeClass('uk-hidden');
        }else{
            $('.row-address-delivery').addClass('uk-hidden');
        }
    });

    $('.subtotal-cost-item').html($('.sizes.active').attr('data-cost'));

    $('.select-baget').click(function(){
        $('.select-baget').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).attr('data-tab');
        var h_b_px = parseInt($(this).attr('data-baget-h'));
        var src_b = $(this).attr('data-baget-src');
        var cost = $(this).attr('data-cost');
        $('input[name=baget_h]').val(parseInt($(this).attr('data-h')));
        $('input[name=baget]').val($(this).attr('title'));
        $('input[name=baget_src]').val($(this).attr('data-baget-src'));
        $('.cropper-tab-'+ tab).addClass('baget-tab');

        var aspectY = $('.sizes-'+ tab +'.active').attr('data-h');
        var aspectX = $('.sizes-'+ tab +'.active').attr('data-w');
        var viewMode = 1;
        $('.cropper-img-'+tab).cropper('destroy').cropper({
            aspectRatio: aspectY / aspectX,
            viewMode: viewMode,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            highlight: true,
            cropBoxMovable: false,
            cropBoxResizable: false
        });

        reload_baget(h_b_px, src_b, tab);
    });
    
    $('select[name=type]').change(function () {
        if($(this).val() === 'холст'){
            $('select[name=paper]').find('option[value=глянцевая]').attr('disabled', 'disabled').removeAttr('selected');
            $('select[name=paper]').find('option[value=сатин]').attr('selected', 'selected');
        }else{
            $('select[name=paper]').find('option[value=глянцевая]').removeAttr('disabled');
        }
    });

    getUploadedCroppedImages();
    ajax_bind_actions();
    function getUploadedImages(){
        $('#uploadedImages').html('<div class="uk-alert uk-text-center">Обрабатываем фотографии...</div>');
        $.ajax({
            type: "GET",
            url: "/photo/GetUploadedImage",
            data: {
                type: $('#uploadedImages').attr('data-type'),
                item: $('#uploadedImages').attr('data-id_tovar'),
                selected_h: $('input[name=current_h_sm]').val(),
                selected_w: $('input[name=current_w_sm]').val(),
                selected_cost: $('input[name=cost]').val(),
            },
            success: function (data) {
                $('#uploadedImages').html(data);
                ajax_bind_actions();
                $('.style_baget').remove();
            },
            error: function () {
                alert('Не удалось загрузить прикрепленные фотографии');
            }
        });
    }

    load_image_plugin();
    function load_image_plugin(){
        /* Image upload http://filer.grandesign.md/#download */
        $('#upload_image_filer').filer({
            changeInput: '<div class="jFiler-input-dragDrop">' +
            '<div class="jFiler-input-inner">' +
            '<div class="jFiler-input-text"><p class="uk-h3">Перетащите фото сюда</p> ' +
            '<span style="display:inline-block; margin: 15px 0">или</span></div>' +
            '<div class="button_upload"><button class="jFiler-input-choose-btn blue uk-button" type="button">Выберите фото из проводника</button></div></div></div>',
            showThumbs: true,
            theme: "dragdropbox",
            addMore: true,
            allowDuplicates: true,
            fileMaxSize: 8,
            maxSize: 100,
            extensions: ["jpg", "jpeg", "png", "gif"],
            files: [],
            templates: {
                box: '<ul class="jFiler-items-list jFiler-items-grid uk-list"></ul>',
                item: '<li class="jFiler-item">\
                    <div class="jFiler-item-container">\
                        <div class="jFiler-item-inner uk-grid">\
                            <div class="jFiler-item-params uk-width-1-1" data-image="{{fi-name}}">\
                                <div class="jFiler-item-status"></div>\
                                <div class="jFiler-item-info">\
                                    <p class="jFiler-item-title">{{fi-name | limitTo: 20}} <small>({{fi-size2}})</small>\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">{{fi-progressBar}}</div>\
                            </div>\
                            <div class="jFiler-item-assets jFiler-row uk-width-1-1"></div>\
                        </div>\
                    </div>\
                </li>',
                progressBar: '<div class="uk-progress-bar"></div>',
                itemAppendToEnd: false,
                removeConfirmation: true,
                _selectors: {
                    list: '.jFiler-items-list',
                    item: '.jFiler-item',
                    progressBar: '.uk-progress-bar',
                    remove: '.jFiler-item-trash-action'
                }
            },
            dragDrop: {
                dragEnter: null,
                dragLeave: null,
                drop: null
            },
            uploadFile: {
                url: "/photo/UploadImage",
                data: {
                    id_tovar: $('#uploadedImages').attr('data-id_tovar')
                },
                type: 'POST',
                enctype: 'multipart/form-data',
                dataType: 'json',
                synchron: false,
                success: function(data, el){
                    el.addClass('finish-load');
                    el.find(".jFiler-item-assets.uk-width-1-1").html("<div class=\"jFiler-item-others uk-alert\">Загружено</div>");
                    el.fadeOut('slow');
                    generatePreview(data.image, data.preview_src);

                    $('#process-upload-modal-files').html($('.jFiler-items').html());
                    setTimeout(function(){
                        $('.finish-load').slideUp('slow');
                    }, 1000);
                },
                error: function(el){
                    el.find(".jFiler-item-assets.uk-width-1-1").html("<div class=\"jFiler-item-others uk-alert uk-alert-error\">Ошибка</div>");
                    alert('Возникла ошибка при загрузке фото, попробуйте загрузить его заново');
                    $('#process-upload-modal-files').html($('.jFiler-items').html());
                },
                statusCode: null,
                onProgress: function () {
                    UIkit.modal("#process-upload-modal").show();
                    $('#process-upload-modal-files').html($('.jFiler-items').html());
                },
                onComplete: function () {
                    $('#process-upload-modal-files').html('<div class="uk-alert">Загрузки завершены</div>');
                    setTimeout(function(){
                        UIkit.modal("#process-upload-modal").hide();
                    }, 1000);
                    getUploadedImages();
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
    /* End image upload */

    function generatePreview(image_src, preview_src) {
        $.ajax({
            data: {image_src: image_src, preview_src: preview_src},
            type: "GET",
            url: "/photo/GeneratePreviewFullImage",
            success: function () {
                $('.image_holder').attr('data-image', preview_src).attr('src', preview_src);
            },
            error: function () {
                alert('Не удалось сгенерировать превью');
            }
        });
    }
});


function ajax_bind_actions() {
    $('.select-tab').each(function(){
        var tab = $(this).attr('data-tab');
        var h = $('.sizes-'+ tab +'.active').attr('data-h');
        var w = $('.sizes-'+ tab +'.active').attr('data-w');
        var viewMode = 1;
        loadCropper(h, w, tab, viewMode);
    });
    load_params_cropper();

    $('.select-tab').click(function () {
        $('.select-tab').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).attr('data-tab');
        $('.uk-accordion-title-'+tab).trigger('click');
    });

    $('button[name=delete_image]').click(function () {
        UIkit.modal("#process-delete-modal").show();
        var tab = $(this).attr('data-image-tab');
        $.ajax({
            data: {rowid: $(this).attr('data-rowid')},
            type: "POST",
            url: "/photo/DeleteUploadedImage",
            success: function () {
                $('.cropper-tab-'+tab).remove();
                $('.uk-preview-'+tab).remove();
                //Переходим на следующий таб с доступным фото
                if($('.select-tab:first').length){
                    $('.select-tab:first').addClass('active').trigger('click');
                }else{
                    $('.container-preview, .container-photos').hide('slow');
                }
            },
            error: function () {
                alert('Не удалось удалить прикрепленные фотографии');
            },
            complete: function() {
                UIkit.modal("#process-delete-modal").hide();
            }
        });
    });

    $('.kolvo-action').change(function () {
        var key = $(this).attr('data-key');
        changeSubtotalItem(key)
    })
}

function changeSubtotalItem(key) {
    var cost = $('.cost-item-'+ key).html();
    var kolvo = $('.kolvo-action-'+ key).val();
    var subtotal = parseFloat(cost) * parseInt(kolvo);

    $('.cropper-tab-'+key).find('input[name=cost]').val(subtotal);

    $('.subtotal-cost-item-'+ key).html(subtotal);
}

function loadCropper(aspectX, aspectY, tab, viewMode) {
    var tab_container = $('.cropper-tab-'+ tab);
    $(tab_container).find('input[name=current_h]').val(aspectY * 0.3937 * 300);
    $(tab_container).find('input[name=current_w]').val(aspectX * 0.3937 * 300);
    $(tab_container).find('input[name=current_h_sm]').val(aspectX);
    $(tab_container).find('input[name=current_w_sm]').val(aspectY);

    changeSize(tab_container);

    var template_imageHeight = 400;
    if(document.getElementById('template-item') !== null){
        template_imageHeight = document.getElementById('template-item').clientHeight;
    }

    $('.cropper-img-'+tab).cropper('destroy').cropper({
        aspectRatio: aspectY / aspectX,
        viewMode: viewMode,
        dragMode: 'move',
        autoCropArea: 1,
        restore: false,
        guides: false,
        highlight: true,
        cropBoxMovable: false,
        cropBoxResizable: false,
        background: false
    });

    $('.cropper-img-suvenir-'+tab).cropper('destroy').cropper({
        viewMode: 3,
        dragMode: 'move',
        autoCropArea: 1,
        restore: false,
        guides: false,
        highlight: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        minContainerHeight: template_imageHeight,
        minCanvasHeight: template_imageHeight,
        background: false
    });

    $('.cropper-tab-'+ tab).find('select[name=borders]').change(function(){
        if($('.cropper-tab-'+ tab).find('select[name=borders]').val() === 'с полями'){
            viewmode = 3;
        }else{
            viewmode = 1;
        }
        changeCropperParams(aspectX, aspectY, tab, viewMode)
    });
}

function changeCropperParams(aspectX, aspectY, tab, viewMode) {
    $('.cropper-img-'+tab).cropper('destroy').cropper({
        aspectRatio: aspectY / aspectX,
        viewMode: viewMode,
        dragMode: 'move',
        autoCropArea: 1,
        restore: false,
        guides: false,
        highlight: true,
        cropBoxMovable: false,
        cropBoxResizable: false,
        background: false
    });
}

function changeSize(tab_container) {
    $(tab_container).find('.sizes').click(function () {
        var viewMode_s = 1;
        var tab_s = $(this).attr('data-tab');
        $('.sizes-'+tab_s).removeClass('active');
        $(this).addClass('active');
        $('.cropper-tab-'+ tab_s).find('input[name=tovar_id]').val($(this).attr('data-tovar'));

        var aspectX = $(this).attr('data-h');
        var aspectY = $(this).attr('data-w');

        console.log(aspectX);
        console.log(aspectY);

        $('.cropper-tab-'+ tab_s).find('input[name=current_h]').val(aspectY * 0.3937 * 300);
        $('.cropper-tab-'+ tab_s).find('input[name=current_w]').val(aspectX * 0.3937 * 300);
        $('.cropper-tab-'+ tab_s).find('input[name=current_h_sm]').val(aspectX);
        $('.cropper-tab-'+ tab_s).find('input[name=current_w_sm]').val(aspectY);
        //$('.cropper-img-'+tab).cropper('destroy');
        if($('.cropper-tab-'+ tab_s).find('select[name=borders]').val() === 'с полями'){
            viewMode_s = 3;
        }else{
            viewMode_s = 1;
        }
        changeCropperParams(aspectX, aspectY, tab_s, viewMode_s);

        $(this).parentsUntil('.uk-accordion-content').find('.cost_item').html($(this).attr('data-cost'));
        $(this).parentsUntil('.uk-accordion-content').find('input[name=cost]').val($(this).attr('data-cost'));

        changeSubtotalItem(tab_s);
    });
}


function load_params_cropper() {
    $('button[name=ScaleX2], button[name=ScaleY2]').hide();

    $('.cropper-buttons').find('button').click(function () {
        var name = $(this).attr('name');
        var action = $(this).attr('data-action');
        var param = $(this).attr('data-param');
        var param2 = $(this).attr('data-param2');
        var tab = $(this).parentsUntil('.cropper-tab').parent().attr('data-key');
        if(name === 'Crop'){
            return false;
        }
        if(param){
            if(param2){
                $(this).parentsUntil('cropper-tab').find('.cropper-img-'+tab).cropper(action, param, param2);
                $(this).parentsUntil('cropper-tab').find('.cropper-img-suvenir-'+tab).cropper(action, param, param2);
            }else{
                $(this).parentsUntil('cropper-tab').find('.cropper-img-'+tab).cropper(action, param);
                $(this).parentsUntil('cropper-tab').find('.cropper-img-suvenir-'+tab).cropper(action, param);
            }
        }else{
            $(this).parentsUntil('cropper-tab').find('.cropper-img-'+tab).cropper(action);
            $(this).parentsUntil('cropper-tab').find('.cropper-img-suvenir-'+tab).cropper(action);
        }

        if(name === 'ScaleX'){
            $(this).hide();
            $('button[name=ScaleX2]').show();
        }
        if(name === 'ScaleX2'){
            $(this).hide();
            $('button[name=ScaleX]').show();
        }
        if(name === 'ScaleY'){
            $(this).hide();
            $('button[name=ScaleY2]').show();
        }
        if(name === 'ScaleY2'){
            $(this).hide();
            $('button[name=ScaleY]').show();
        }
    });

    $('.reset-tab').click(function () {
        $('.uk-active').find('.origImage').cropper('reset');
    });

}

function getUploadedCroppedImages() {
    $.ajax({
        type: "GET",
        url: "/photo/GetUploadedCroppedImage",
        success: function (data) {
            $('#uploadedCroppedImages').html(data);
            $('body.body-photo-Cart').find('#show-form').hide();

            $('.ajax_edit_media').change(function(){
                $.ajax({
                    data: {
                        rowid: $(this).attr('data-rowid'),
                        option: $(this).attr('name'),
                        value: $(this).val()
                    },
                    type: "POST",
                    url: "/photo/CustomProperties",
                    success: function (data) {
                        getUploadedCroppedImages();
                        /*noty({
                            text: data.message,
                            type: 'information',
                            layout: 'center',
                            timeout: 2000,
                            modal: true
                        });*/
                    },
                    error: function (data) {
                        notify_show('danger', data.message);
                    }
                });
            });

            $('button[name=delete_cropped_image]').click(function () {
                UIkit.modal("#process-delete-modal").show();
                var tab = $(this).attr('data-image-tab');
                $.ajax({
                    data: {rowid: $(this).attr('data-rowid')},
                    type: "POST",
                    url: "/photo/DeleteUploadedCroppedImage",
                    dataType: 'json',
                    success: function (data) {
                        if(data.status === 'success'){
                            getUploadedCroppedImages();
                        }else{
                            alert('Не удалось удалить прикрепленные фотографии');
                        }
                    },
                    error: function () {
                        alert('Не удалось удалить прикрепленные фотографии');
                    },
                    complete: function() {
                        UIkit.modal("#process-delete-modal").hide();
                    }
                });
            });
        },
        error: function () {
            alert('Не удалось удалить прикрепленные фотографии');
        }
    });
}

function cropPhoto(tab_name) {
    UIkit.modal("#process-crop-modal").show();

    var tab = $('.cropper-tab-'+tab_name);
    var img = $('.cropper-img-'+tab_name);

    $(img).cropper('getCroppedCanvas').toBlob(function (blob){
        var formData = new FormData();
        formData.append('croppedImage', blob);
        $(tab).find('.send_data').each(function(){
            formData.append($(this).attr('name'), $(this).val());
        });

        sendCropData(formData, tab, tab_name);
    });
}

function cropSuv(tab_name) {
    UIkit.modal("#process-crop-modal").show();

    var tab = $('.cropper-tab-'+tab_name);
    var img = $('.cropper-img-suvenir-'+tab_name);

    $(img).cropper('getCroppedCanvas', {
        width: document.getElementById('template-item').clientWidth,
        height: document.getElementById('template-item').clientHeight
    }).toBlob(function (blob){
        var formData = new FormData();
        formData.append('croppedImage', blob);
        formData.append('originalImage', $('.cropper-img-suvenir-'+tab_name).attr('src'));
        $(tab).find('.send_data').each(function(){
            formData.append($(this).attr('name'), $(this).val());
        });

        if(document.getElementById('template-item') !== null){
            formData.append('template_imageHeight', document.getElementById('template-item').clientHeight);
            formData.append('template_imageWidth', document.getElementById('template-item').clientWidth);
        }

        sendCropData(formData, tab, tab_name);
    });
}

function sendCropData(formData, tab, tab_name) {
    $.ajax('/photo/UploadCroppedImage', {
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            //удаляем таб с редактором
            tab.remove();
            $('.uk-preview-'+tab_name).remove();
            //Загружаем список обработанных фото
            getUploadedCroppedImages();

            //Переходим на следующий таб с доступным фото
            if($('.select-tab:first').length){
                $('.select-tab:first').addClass('active').trigger('click');
            }else{
                $('.container-preview').html('<div class="uk-alert uk-alert-info"><p>Все фото обработаны, можете переходить к оформлению заказа в окне справа</p></div>');
                $('.container-photos').remove();
            }
        },
        error: function () {
            alert('ERROR');
            console.log('Upload error');
        },
        complete: function() {
            UIkit.modal("#process-crop-modal").hide();
        }
    });
}

function notify_show(type, message) {
    // http://ned.im/noty/#installation
    if(type === 'error'){
        noty({
            text: message,
            type: type,
            layout: 'top'
        });
    }else{
        noty({
            text: message,
            type: type,
            layout: 'topRight',
            timeout: 2000
        });
    }
}

//Подсчет цены рамы
function calc_rama() {
    var border_h = parseFloat($('input[name=border_h]').val()/100);
    var border_w = parseFloat($('input[name=border_w]').val()/100);
    var P = (border_w + border_h)*2;
    var S = border_h * border_w;
    var shirina = parseFloat($('input[name=shirina]').val());
    var cost =parseFloat($('input[name=cost]').val());
    var baget = (P + 8* shirina) * cost;
    var osnovanie = parseFloat(S*600);
    var steklo = 0;
    var vstavka = 0;
    var podramnik = 0;
    var usilemie = 0;
    var job = 0;
    if($('input[name=glass]:checked').val() === 'со стеклом'){
        steklo = parseFloat(S*700);
    }
    if($('input[name=vstavka]:checked').val() === 'Да'){
        vstavka = parseFloat((P-1.2)*150+150);
        if(vstavka < 200){
            vstavka = 200;
        }
    }
    if($('input[name=podramnik]:checked').val() === 'Да'){
        podramnik = parseFloat(P*130);
    }
    if($('input[name=usilemie]:checked').val() === 'Да'){
        usilemie = 100;
    }
    if($('input[name=fast]:checked').val() === 'Да'){
        job = parseFloat(P*200);
    }else{
        job = parseFloat(P*100);
    }
    var zajimi = parseFloat(P/0.1*2);
    var krokodil = 30;

    var summa = baget+osnovanie+steklo+zajimi+krokodil+job+vstavka+podramnik+usilemie;
    summa = summa.toFixed();

    $('p#cost_rama').find('span').html(summa);
    $('input[name=total_cost]').val(summa);

    /*console.log(border_h);
    console.log(border_w);
    console.log(P);
    console.log(S);
    console.log(shirina);
    console.log(cost);
    console.log(baget);
    console.log(osnovanie);
    console.log(steklo);
    console.log(zajimi);
    console.log(krokodil);
    console.log(job);
    console.log(summa);*/
}
//END: Подсчет цены рамы