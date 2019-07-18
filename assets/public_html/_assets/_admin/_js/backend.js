window.FileAPI = { staticPath: '/_assets/bower_components/fileapi/dist/' };

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    actionSelect();
    ajax_edit_row();
    sort_rows();
    initSearchModule();

    $('.show-please').click(function () {
        var target = $(this).attr('data-target');
        $('.'+target).removeClass('uk-hidden');
    });

    /**
     * Функционал для formbuilder select
     * По нажатию на кнопку .new_list будет создан input с name= data-row-name кнопки
     * Позволяет вносить в списки кастомные значения
     * */
    $('.new_list').click(function () {
        $(this).hide();
        var row_name = $(this).attr('data-row-name');
        $('select[name='+row_name+']').before('<input class="uk-input" placeholder="Новое значение" type="text" name="' + row_name + '" value="">').remove();
    });
    $('.new_list_multiply').click(function () {
        $(this).hide();
        var row_name = $(this).attr('data-row-name');
        $('select[id=r-row-'+row_name+']').before('<input class="uk-input" placeholder="Новые значения через ;" type="text" name="' + row_name + '_new_list" value="">');
        $('select[id='+row_name+']').before('<input class="uk-input" placeholder="Новые значения через ;" type="text" name="' + row_name + '_new_list" value="">');
    });

    // получение максимального элемента массива
    function getMaxValue(array){
        var max = array[0];
        for (var i = 0; i < array.length; i++) {
            if (max < array[i]) max = array[i];
        }
        return max;
    }

    $('.uk-sortable-img-plugin').on('moved', function (e) {
        var positions = [];
        var max_position = 0;
        $(e.currentTarget).each(function (key, value) {
            $(value).find('div.uk-grid').each(function (k, v) {
                positions.push($(v).attr('data-id'));
                //console.log($(v).attr('data-id'));
            });
        });
        max_position = getMaxValue(positions);

        $(e.currentTarget).each(function (key, value) {
            $(value).find('div.uk-grid').each(function (k, v) {
                $(v).find('input.position-image').val(--max_position);
            });
        });
    });

    $('button[type=submit]').click(function(){
        notify_show('message', '<i class="uk-icon-spin uk-icon-refresh"></i> Выполняется...');
    });

    $('input[name=anons_merge]').change(function() {
        if(this.checked) {
            $('textarea[name=anons_description]').prop('disabled', true);
            $('.form-group-anons_description').hide();
        }else{
            $('textarea[name=anons_description]').prop('disabled', false);
            $('.form-group-anons_description').show();
        }
    });

    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: "textarea:not(.not-editor)",
            height: 300,
            plugins: [
                "advlist link image imagetools lists charmap hr anchor pagebreak",
                "searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table directionality template paste importcss wordcount nonbreaking visualblocks visualchars codesample"
            ],
            relative_urls: false,
            extended_valid_elements : "table[cellpadding|cellspacing|class],td[class|colspan|rowspan],tr[class]",
            paste_remove_styles: true,
            paste_remove_spans: true,
            paste_auto_cleanup_on_paste: true,
            theme: 'silver',
            image_advtab: true,
            content_css: "/_assets/_front/_css/_min/uikit.min.css,/_assets/_admin/_css/tinymce.css",
            content_style: "table {width: 100%}",
            importcss_append: true,
            language_url : '/_assets/_admin/_js/tinymce.ru.js',
            toolbar_items_size: 'small',
            toolbar: "undo redo | bold italic codesample | alignleft aligncenter alignright | bullist numlist outdent " +
            "indent | link image media pastetext | forecolor backcolor | template | code | defis nonbreaking hr | photonews | typo | removeformat charmap",
            imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions",
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            setup: function(editor) {
                editor.ui.registry.addButton('defis', {
                    text: '—',
                    title: 'Дефис',
                    onAction: function (_) {
                        editor.insertContent('—');
                    }
                });
                editor.ui.registry.addButton('photonews', {
                    text: 'Галерея',
                    title: 'Вставка шортката для галереи',
                    onAction: function (_) {
                        editor.insertContent('{Фото[news]=}');
                    }
                });
                editor.ui.registry.addButton('typo', {
                    text: 'Типограф',
                    title: 'Выделите текст в редакторе и нажмите для типографа',
                    onAction: function (_) {
                        var text = tinyMCE.activeEditor.selection.getContent({format : 'html'});
                        $.ajax({
                            type: "POST",
                            data: { text: text},
                            dataType: 'json',
                            url: "/admin/ajax/TypographLight",
                            success: function (data) {
                                tinyMCE.activeEditor.execCommand('mceReplaceContent',false, ''+data.text);
                            }
                        });
                    }
                });
            },
            templates: [
                {
                    title: 'Вставка фото-галереи :: Новости (Одно большое, другие маленькие)',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[news]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи без FancyBox :: Новости (Одно большое, другие маленькие)',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[nonFancy]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи :: Большие фото с описаниями',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[newsDescription]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи :: Одинаковые блоки',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[blocks]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи :: Большие фото',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[blocksBig]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи :: Сертификаты (небольшие фото с описаниями)',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[sert]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка фото-галереи :: Вывод одинаковыми блоками',
                    description: 'Вставьте после знака "=" имя группы картинок',
                    content: '{Фото[customШиринаxВысота]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка списка разделов сайта',
                    description: 'Вставьте после знака "=" URL категории (вставятся и потомки)',
                    content: '{Категории=}'},
                {
                    title: 'Вставка материалов из документации',
                    description: 'Вставьте после знака "=" URL категории',
                    content: '{Документы[default]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка прикрепленных к материалу файлов',
                    description: 'Вставьте после знака "=" имя группы файлов',
                    content: '{Файлы[default]='+ $('input[name=url]').val() +'}'},
                {
                    title: 'Вставка файлов из директории',
                    description: 'Вставьте после знака "=" имя папки в /public/files/',
                    content: '{Файлы[directory]=}'}
            ]
        });

        //Типограф
        $('.typo-action').click(function(){
            var text = tinyMCE.activeEditor.selection.getContent({format : 'html'});
            $.ajax({
                type: "POST",
                data: { text: text},
                dataType: 'json',
                url: "/admin/ajax/TypographLight",
                success: function (data) {
                    tinyMCE.activeEditor.execCommand('mceReplaceContent',false, ''+data.text);
                }
            });
        });
    }

    function typo() {
        $.ajax({
            type: "POST",
            data: { text: text},
            dataType: 'json',
            url: "/admin/ajax/TypographLight",
            success: function (data) {
                input.val(data.text);
            }
        });
    }

    $('.typo-target').click(function(){
        var input_target = $(this).attr('data-target');
        var input = $('input[name='+input_target+']');
        var text = input.val();
        $.ajax({
            type: "POST",
            data: { text: text},
            dataType: 'json',
            url: "/admin/ajax/TypographLight",
            success: function (data) {
                input.val(data.text);
            }
        });
    });

    $('.typo').focusout(function(){
        var input = $(this);
        var text = $(this).val();
        $.ajax({
            type: "POST",
            data: { text: text},
            dataType: 'json',
            url: "/admin/ajax/TypographLight",
            success: function (data) {
                input.val(data.text);
            }
        });
    });

    $('input[name=date], input.date').pickadate({
        monthSelector: true,
        yearSelector: true,
        formatSubmit: 'yyyy-mm-dd',
        firstDay: 1,
        monthsFull: [ 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' ],
        weekdaysShort: [ 'Вск', 'Пон', 'Вт', 'Ср', 'Чт', 'Пт', 'Суб' ],
        format: 'yyyy-mm-dd'
    });

    $('input.dateDay').pickadate({
        monthSelector: true,
        yearSelector: true,
        formatSubmit: 'yyyy-mm-dd 00:00:00',
        firstDay: 1,
        monthsFull: [ 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' ],
        weekdaysShort: [ 'Вск', 'Пон', 'Вт', 'Ср', 'Чт', 'Пт', 'Суб' ],
        format: 'yyyy-mm-dd 00:00:00'
    });


    /*
     * Универсальный обработчик для выделения блока как ссылки
     * Ищет внутри блока ссылку и присваивает ее всему блоку
     */
    $('.link_block').click(function(){window.location = $(this).find('a').attr('href');});
    $('.link_block_this').click(function(){window.location = $(this).attr('data-href');});

    /** Если присвоить класс change-form селекту в форме, то при клике будет происходить сабмит формы */
    $('select.change-form, input[type=checkbox].change-form').change(function(){
        $(this).parents('form').submit();
    });

    $('input[type=text].change-form').focusout(function(){
        $(this).parents('form').submit();
    });

    $('.btn-group_switch_ajax').find('button').click(function(){
        $(this).parent().find('button').addClass('uk-button-outline');
        $(this).removeClass('uk-button-outline');
        var value_where = $(this).attr('data-value_where');
        var row_where = $(this).attr('data-row_where');
        var value = $(this).attr('data-value');
        var row = $(this).attr('data-row');
        var table = $(this).attr('data-table');
        var data = { value_where: value_where, row_where: row_where, value: value, row: row, table: table };
        hidden_action('/admin/ajax/EditRow', data, false, false, false, true);
    });

    /** Conform alert. Уверены что хотите сделать это?) */
    $('.please_conform').on('click', function () {
        var href = $(this).attr('href');
        return confirm('Уверены?');
    });

    /**
     * Создание URL для страниц
     * @reference function change_url
     */
    $('input[name=title]').focusout(function(){
        var title = $(this).val();
        var form = $(this).closest('form');
        var url_input = $(form).find('input[name=url]').val();
        if(url_input !== undefined){
            if(url_input.length < 1 || (url_input === 'novyy-material' || url_input === 'novyy_material')){
                var table = $(this).attr('data-table');
                change_url_title(title, table, form);
            }
        }
    });
    $('.refresh-url').click(function () {
        var input = $('input[name=title]');
        var title = input.val();
        var form = $(this).closest('form');
        var table = input.attr('data-table');
        change_url_title(title, table, form);
    });
});

function initSearchModule() {
    var request = $.ajax({
        type: "GET",
        dataType: "json",
        url: '/admin/initSearchModule'
    });
    request.done(function (data) {
        $('.form-search-autocomplite').selectize({
            maxItems: 1,
            valueField: 'admin_url',
            labelField: 'title',
            searchField: 'title',
            persist: true,
            createOnBlur: false,
            create: false,
            allowEmptyOption: true,
            placeholder: 'Поиск по сайту',
            options: data,
            render: {
                item: function(item, escape) {
                    return '<div>' +
                        (item.title ? '<span class="title">' + escape(item.title.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        (item.category ? '<span class="category">/' + escape(item.category.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        (item.component ? '<span class="caption">компонент: ' + escape(item.component.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        '</div>';
                },
                option: function(item, escape) {
                    return '<div>' +
                        '<span class="uk-label">' + escape(item.title.replace('&quot;', '').replace('&quot;', '')) + '</span>' +
                        (item.category ? '<span class="caption">в разделе: ' + escape(item.category.replace('&quot;', '').replace('&quot;', '')) + '</span>' :'') +
                        (item.component ? '<span class="caption">компонент: ' + escape(item.component.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        '</div>';
                }
            },
            onChange: function (item) {
                window.location = item;
            }
        });
        $('#searchAdmin').removeClass('uk-hidden');
    });
    request.fail(function (jqXHR, status, statusText) {
        notify_show('error', statusText);
        return false;
    });
}

function ajax_edit_row() {
    /** Input для редактирования поля */
    $('.ajax_edit_row').on('change', function(){
        var value_where = $(this).attr('data-value_where');
        var row_where = $(this).attr('data-row_where');
        var value = $(this).val();
        var row = $(this).attr('name');
        if(row == undefined){
            row = $(this).attr('data-row');
        }
        var table = $(this).attr('data-table');
        var event = 'edit';
        var data = { value_where: value_where, row_where: row_where, value: value, row: row, event: event, table: table };
        //hidden_action(url, send_data, good_message, button, redirect_url, clearcache)
        hidden_action('/admin/ajax/EditRow', data, false, false, false, true);
    });
}


// получение максимального элемента массива
function getMaxValue(array){
    var max = array[0];
    for (var i = 0; i < array.length; i++) {
        if (max < array[i]) max = array[i];
    }
    return max;
}


/**
 * Сортировка материалов по весу через плагин uikit sortable
 */
function sort_rows() {
    $('.uk-sortable-img-plugin').on('moved', function (e) {
        var positions = [];
        var max_position = 0;
        $(e.currentTarget).each(function (key, value) {
            $(value).find('div.uk-grid').each(function (k, v) {
                positions.push($(v).attr('data-id'));
            });
        });
        max_position = getMaxValue(positions);

        $(e.currentTarget).each(function (key, value) {
            $(value).find('div.uk-grid').each(function (k, v) {
                $(v).find('input.position-image').val(--max_position);

                var data = {
                    value_where: $(v).find('input.position-image').attr('data-value_where'),
                    row_where: $(v).find('input.position-image').attr('data-row_where'),
                    value: max_position,
                    row: $(v).find('input.position-image').attr('data-row'),
                    event: 'edit',
                    table: $(v).find('input.position-image').attr('data-table')
                };
                hidden_action('/admin/ajax/EditRow', data, false, false, false, true);
            });
        });
    });
}


/**
 * Метод для выполнения ajax-операций
 * string       @param url              URL для вызова
 * array        @param send_data        Пересылаемые данные /false
 * bool/string  @param good_message     Кастомное сообщение об успехе операции /false
 * object       @param button           объект с кнопкой, на которую нажали /false
 * bool/string  @param redirect_url     Если передан string с адресом страницы, то будет выполнен редирект /false
 * bool         @param clearcache       Если true, то очистит кеш сайта /false
 */
function hidden_action(url, send_data, good_message, button, redirect_url, clearcache) {
    var request = $.ajax({
        data: send_data,
        type: "POST",
        dataType: "json",
        url: url
    });
    request.done(function (msg) {
        if(msg.status === 'blank'){
            return false;
        }

        if(msg.status === 'error'){
            notify_show('error', msg.message);
            return false;
        }

        if(msg.status === 'success'){
            if ((good_message !== false) && (good_message !== undefined)) {
                notify_show('success', good_message);
            }else{
                notify_show('success', msg.message);
            }
        }

        if (clearcache === true) {
            clear_cache();
        }
        if ((button !== false) && (redirect_url !== undefined)) {
            $(button).removeClass('action_button').removeAttr('disabled');
        }
        if ((redirect_url !== false) && (redirect_url !== undefined)) {
            window.location = redirect_url;
        }
        return true;
    });
    request.fail(function (jqXHR, status, statusText) {
        notify_show('error', statusText);
        if ((button !== false) && (redirect_url !== undefined)) {
            $(button).removeClass('action_button').removeAttr('disabled');
        }
        return false;
    });
}

/**
 * Уведомления в сплывающих окнах от процессов
 * string @param type  Тип события (good, error)
 * string @param message   Сообщение на вывод
 */
function notify_show(type, message) {
    if(type === 'error'){
        UIkit.notification({
            message : '<i uk-icon="icon: warning"></i> '+ message,
            status  : 'danger',
            timeout : 0,
            pos     : 'top-center'
        });
    }else{
        if(type === 'message'){
            type = 'info';
        }
        UIkit.notification({
            message : '<i uk-icon="icon: check"></i> '+ message,
            status  : type,
            timeout : 3000,
            pos     : 'top-right'
        });
    }
}

/** Purge site cache function */
function clear_cache() {
    hidden_action('/admin/ajax/ClearCache', false, false, false, false, false);
}

/**
 * Создание url для страницы и вставка значения в input[name=url]
 * string @param title  Текст для транслитерации (обычно input[name=title])
 * string @param table  Имя таблицы для проверки уникальности url (опционально, можно передать пустое значение)
 * string @param form   Форма в которой проводятся операции
 */
function change_url_title(title, table, form){
    $.ajax({
        type: "POST",
        data: { text: title, table: table},
        dataType: 'json',
        url: "/admin/ajax/Translit",
        success: function (data) {
            var url_input = $(form).find('input[name=url]');
            var active_input = $(form).find('input[name=active]');
            if (data.message) {
                url_input.val(data.message);
                active_input.attr('checked', 'checked');
                $('input[name=gallery_img]').val(data.message);
                $('input[name=gallery_file]').val(data.message);
                notify_show('info', 'Материалу будет присвоен url: '+data.message);
            }
        }
    });
}

/**
 * Массовое выделение элементов для их удаления
 * string @param data-id        ID элемента в БД
 * string @param data-target    ID формы производящей действие
 */
function actionSelect() {
    $('.actionSelect').click(function () {
        $(this).toggleClass('uk-active');
        var id = $(this).attr('data-id');
        var target = $(this).attr('data-target');
        var formAction = $('form#'+ target);
        var select = $(formAction).find('select[name="ids[]"]');
        if($(select).find('option[value='+ id +']:selected').val()){
            $(select).find('option[value='+ id +']').prop('selected', false);
        }else{
            $(select).find('option[value='+ id +']').prop('selected', true);
        }

        var countSelected = $(select).find('option:selected').length;
        $(formAction).find('span').html(countSelected);
        if(countSelected > 0){
            $(formAction).removeClass('uk-hidden');
            $(formAction).parentsUntil('tr').slideDown('fast');
        }else{
            $(formAction).addClass('uk-hidden');
            $(formAction).parentsUntil('tr').slideUp('fast');
        }
    });
}