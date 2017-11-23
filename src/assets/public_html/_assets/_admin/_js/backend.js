$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('button[type=submit]').click(function(){
        notify_show('message', '<i class="uk-icon-spin uk-icon-refresh"></i> Выполняется...');
    });

    $('input.checked_all').change(function () {
        if($('input.checked_all:checked').val()){
            $('input.ids').attr('checked', true);
        }else{
            $('input.ids').attr('checked', false);
        }
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

    function create_new_column() {
        $('.wizard-db-colomns').change(function () {
            if($(this).val() === 'create-column'){
                var column = $(this).find(':selected').attr('data-column');
                if(column !== undefined){
                    $.ajax({
                        type: "GET",
                        url: '/admin/wizard/createMigration',
                        dataType: 'json',
                        data: { column: column },
                        success: function(data)
                        {
                            if(data.status === 'success'){
                                notify_show('success', data.message);
                                notify_show('success', '! Выберите в поле пункт с названием нового поля !');
                                $('.wizard-db-colomns')
                                    .append($("<option></option>")
                                        .attr("value", column)
                                        .text(column))
                                    .prop('selected', true);
                            }else{
                                notify_show('error', data.message);
                            }
                        },
                        error: function()
                        {
                            alert('Ошибка выполнения запроса');
                        }
                    });
                }else{
                    alert('Column не определена');
                }
            }
        });
    }


    function triggerUpdateCell() {
        notify_show('message', 'Доступно редактирование таблицы');
        $('.tab-content-wizard').css('opacity', '1');
        create_new_column();
        $('input.cell_value').focusout(function () {
            var cell = $(this).attr('data-coordinate');
            var value = $(this).val();
            var old_value = $(this).attr('data-oldvalue');
            var sheet = $(this).attr('data-sheet');
            if(cell !== undefined){
                if(old_value !== value){
                    $.ajax({
                        type: "GET",
                        url: '/admin/wizard/updateXLSX',
                        dataType: 'json',
                        data: { cell: cell, value: value, sheet: sheet },
                        success: function(data)
                        {
                            if(data.status === 'success'){
                                notify_show('success', data.message);
                            }else{
                                notify_show('error', data.message);
                            }
                        },
                        error: function()
                        {
                            alert('Ошибка выполнения запроса');
                        }
                    });
                }
            }else{
                notify_show('notice', 'Ячейка не изменилась');
            }
        });

        $('select.cell_value').change(function () {
            var cell = $(this).attr('data-coordinate');
            var value = $(this).val();
            var old_value = $(this).attr('data-oldvalue');
            var sheet = $(this).attr('data-sheet');
            if(cell !== undefined){
                if(old_value !== value){
                    $.ajax({
                        type: "GET",
                        url: '/admin/wizard/updateXLSX',
                        dataType: 'json',
                        data: { cell: cell, value: value, sheet: sheet },
                        success: function(data)
                        {
                            if(data.status === 'success'){
                                notify_show('success', data.message);
                            }else{
                                notify_show('error', data.message);
                            }
                        },
                        error: function()
                        {
                            alert('Ошибка выполнения запроса');
                        }
                    });
                }
            }else{
                notify_show('notice', 'Ячейка не изменилась');
            }
        });
    }

    //Загрузка листов xlsx
    $('.load_sheet').each(function () {
        var sheet = $(this).attr('data-sheet');
        if(sheet === '0'){
            $('.tab-content-wizard').css('opacity', '.3');
            notify_show('message', 'Выполняется разбор прайса');
            notify_show('message', 'Редактирование таблицы пока не доступно');
        }
        if(sheet !== undefined){
            loadSheet(sheet, $(this));
        }
    });
    function loadSheet(sheet, element){
        $('.sheet'+ sheet).addClass('loadSheet').removeClass('finishSheet').removeClass('errorSheet');
        $.ajax({
            type: "GET",
            url: '/admin/wizard/sheetParse/'+ sheet,
            dataType: 'html',
            success: function(data)
            {
                element.addClass('data_sheet');
                $('#sheet_content'+ sheet).html(data);
                //notify_show('success', 'Страница '+ sheet +' разобрана');
                $('.sheet'+ sheet).removeClass('loadSheet').addClass('finishSheet');
                if($('.data_sheet').length === $('.load_sheet').length){
                    triggerUpdateCell();
                }
            },
            error: function(data)
            {
                $('#sheet_content'+ sheet).html(data);
                $('.sheet'+ sheet).removeClass('loadSheet').addClass('errorSheet');
                notify_show('error', 'Страница '+ sheet +' не разобрана');
            }
        });
    }

    //Импорт прайса
    var current_category_import;
    var current_level_import;
    var current_title_import;

    $('.start_import').click(function () {
        $.ajax({
            type: "GET",
            url: '/admin/wizard/clear',
            dataType: 'json',
            data: [],
            success: function()
            {
                notify_show('success', 'Каталог очищен');
                importXLS();
            },
            error: function()
            {
                notify_show('error', 'Каталог не очищен, импорт не запущен');
            }
        });
    });

    function importXLS() {
        var sheet = $('.import_row').parentsUntil('.load_sheet').parent().attr('data-sheet');
        var last_sheet = 0;
        var count_rows = $('.import_row').length;

        if(count_rows < 1){
            sheet = last_sheet;
        }

        var progress = $('#sheet'+ sheet).find('.uk-progress');
        var progress_bar = $('#sheet'+ sheet).find('.uk-progress-bar');
        var progress_all_rows = parseInt($('.all_rows').html());
        var progress_percent = parseInt(100-(count_rows * 100)/progress_all_rows);

        progress.addClass('uk-progress-striped').addClass('uk-active');

        if(count_rows > 0){
            var form = $('.import_row:first');
            var tr = form.find('tr');

            progress_bar
                .css('width', progress_percent +'%')
                .find('.imported_rows').html(progress_all_rows - count_rows +' '+ progress_percent +'%');

            $.ajax({
                type: "POST",
                url: '/admin/wizard/importrow',
                dataType: 'json',
                data: form.serialize() +'&current_category='+ current_category_import +'&current_level='+ current_level_import +'&current_title='+ current_title_import, // serializes the form's elements.
                success: function(data)
                {
                    tr.addClass('uk-alert-success');
                    form.removeClass('import_row');
                    current_category_import = data.category_id;
                    current_level_import = data.category_level;
                    current_title_import = data.category_title;
                    importXLS();
                },
                error: function()
                {
                    tr.addClass('uk-alert-danger');
                    notify_show('error', 'Импорт не завершен. Ошибка внесения данных');
                    progress_bar.css('width', progress_percent +'%');
                    progress.addClass('uk-progress-danger').removeClass('uk-progress-striped').removeClass('uk-active');
                }
            });
        }else{
            progress_bar
                .css('width', '100%')
                .find('.imported_rows').html(progress_all_rows +' 100%');
            progress.addClass('uk-progress-success').removeClass('uk-progress-striped').removeClass('uk-active');
            notify_show('success', 'Импорт завершен');
        }
    }

    tinymce.init({
        selector: "textarea:not(.not-editor)",
        menu: {
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat'},
            headers: {'title': 'Стили UiKit', items: 'formats | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
            view: {title: 'View', items: 'visualaid visualblocks visualchars | fullscreen'},
            edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext insertdatetime | searchreplace | selectall'},
        },
        height: 300,
        plugins: [
            "advlist link image imagetools lists charmap hr anchor pagebreak",
            "searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality template paste textcolor importcss wordcount nonbreaking visualblocks visualchars codesample"
        ],
        relative_urls: false,
        extended_valid_elements : "table[cellpadding|cellspacing|class],td[class|colspan|rowspan],tr[class]",
        paste_remove_styles: true,
        paste_remove_spans: true,
        paste_auto_cleanup_on_paste: true,
        theme: 'modern',
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
        style_formats: [
            {title: 'Headers', items: [
                {title: 'h1', block: 'h1'},
                {title: 'h2', block: 'h2'},
                {title: 'h3', block: 'h3'},
                {title: 'h4', block: 'h4'},
                {title: 'h5', block: 'h5'},
                {title: 'h6', block: 'h6'}
            ]},

            {title: 'Headers-text', items: [
                {title: 'стиль H1', block: 'p', classes: 'uk-h1'},
                {title: 'стиль H2', block: 'p', classes: 'uk-h2'},
                {title: 'стиль H3', block: 'p', classes: 'uk-h3'},
                {title: 'стиль H4', block: 'p', classes: 'uk-h4'},
                {title: 'стиль H5', block: 'p', classes: 'uk-h5'},
                {title: 'стиль H6', block: 'p', classes: 'uk-h6'}
            ]},

            {title: 'Blocks', items: [
                {title: 'p', block: 'p'},
                {title: 'Увеличенный блок', block: 'blockquote'},
                {title: 'code', block: 'code'},
                {title: 'pre', block: 'pre'}
            ]}
        ],
        setup: function(editor) {
            editor.addButton('defis', {
                text: '—',
                title: 'Дефис',
                icon: false,
                onclick: function() {
                    editor.insertContent('—');
                }
            });
            editor.addButton('photonews', {
                text: 'Галерея',
                title: 'Вставка шортката для галереи',
                icon: false,
                onclick: function() {
                    editor.insertContent('{Фото[news]=}');
                }
            });
            editor.addButton('typo', {
                text: 'Типограф',
                title: 'Выделите текст в редакторе и нажмите для типографа',
                icon: false,
                onclick: function() {
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

    /* Cart */
    $('select.add_to_cart').change(function(){
        $('#ModalToCart').remove();
        $.ajax({
            url: '/admin/ajax/getTovar',
            type: 'POST',
            dataType: 'html',
            data: {
                id: $(this).val(),
                order_id: $(this).attr('data-order_id'),
                in_template: 'true'
            },
            error: function() {
                alert('ERROR!');
            },
            success: function(res) {
                $('#content').after(res);
                UIkit.modal("#ModalToCart").show();
            }
        });
        return false;
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

    $('.show-please').click(function(){
        var target = '.'+ $(this).attr('data-target');
        var focus_element = '.'+ $(this).attr('data-focus');
        $(target).removeClass('uk-hidden');
        $(focus_element).focus();
        $(this).remove();
    });

    /** Если присвоить класс change-form селекту в форме, то при клике будет происходить сабмит формы */
    $('select.change-form, input[type=checkbox].change-form').change(function(){
        $(this).parents('form').submit();
    });

    $('input[type=text].change-form').focusout(function(){
        $(this).parents('form').submit();
    });

    /** Input для редактирования поля */
    ajax_edit_row();
    sort_rows();

    $('.btn-group_switch_ajax').find('button').click(function(){
        $(this).parent().find('button').addClass('uk-button-outline');
        $(this).removeClass('uk-button-outline');
        var value_where = $(this).attr('data-value_where');
        var row_where = $(this).attr('data-row_where');
        var value = $(this).attr('data-value');
        var row = $(this).attr('data-row');
        var table = $(this).attr('data-table');
        var data = { value_where: value_where, row_where: row_where, value: value, row: row, table: table };
        //hidden_action(url, send_data, good_message, button, redirect_url, clearcache)
        hidden_action('/admin/ajax/EditRow', data, false, false, false, true);
    });

    //uk-button uk-button-danger uk-button-small  uk-button-outline
    //uk-button uk-button-danger uk-button-small


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
            if(url_input.length < 1 || url_input === 'novyy-material'){
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

    /**
     * По нажатию на кнопку .new_list будет создан input с name= data-row-name кнопки
     * Позволяет вносить в списки кастомные значения
     * */
    $('.new_list').click(function () {
        $(this).hide();
        var row_name = $(this).attr('data-row-name');
        $('select[name='+row_name+']').before('<input class="form-control" placeholder="Новое значение" type="text" name="' + row_name + '" value="">').remove();
    });
    $('.new_list_multiply').click(function () {
        $(this).hide();
        var row_name = $(this).attr('data-row-name');
        $('select[id=r-row-'+row_name+']').before('<input class="form-control" placeholder="Новые значения через ;" type="text" name="' + row_name + '_new_list" value="">');
        $('select[id='+row_name+']').before('<input class="form-control" placeholder="Новые значения через ;" type="text" name="' + row_name + '_new_list" value="">');
    });

    /** Выделить все чекбоксы для кнопки удалить все  */
    $('input[name=checked_all]:not(.checked)').click(function () {
        $(this).addClass('checked');
        $('input[name^=delete]:visible').attr('checked', 'checked');
    });
});

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

/**
 * Сортировка материалов по весу через плагин uikit sortable
 */
function sort_rows() {
    $('tbody.uk-sortable').on('change.uk.sortable', function(e, fn, el) {
        var old_position = parseInt($(el).find('input[name=position]').val());
        if(old_position){
            var next_position = parseInt($(el).next().find('input[name=position]').val());
            var prev_position = parseInt($(el).prev().find('input[name=position]').val());
            var new_position = 0;
            if(next_position > old_position){
                if(prev_position > old_position){
                    new_position = --prev_position;
                }else{
                    new_position = ++next_position;
                }
            }else{
                if(prev_position){
                    new_position = --prev_position;
                }else{
                    new_position = ++next_position;
                }
            }
            $(el).find('input[name=position]').val(new_position);

            var element = $(el).find('input[name=position]');
            var row = $(element).attr('name');
            if(row === undefined){
                row = $(el).attr('data-row');
            }
            var event = 'edit';
            var data = {
                value_where: $(element).attr('data-value_where'),
                row_where: $(element).attr('data-row_where'),
                value: new_position,
                row: row,
                event: event,
                table: $(element).attr('data-table')
            };
            hidden_action('/admin/ajax/EditRow', data, false, false, false, true);
        }
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

function typographLight(text, input) {
    $.ajax({
        type: "POST",
        data: { text: text },
        dataType: 'json',
        url: "/admin/ajax/TypographLight",
        success: function (data) {
            if (data.text) {
                input.val(data.text);
            }
        }
    });
}

/**
 * Уведомления в сплывающих окнах от процессов
 * string @param type  Тип события (good, error)
 * string @param message   Сообщение на вывод
 */
function notify_show(type, message) {
    if(type === 'error'){
        UIkit.notify({
            message : '<i class="uk-icon-bug"></i> '+ message,
            status  : 'danger',
            timeout : 0,
            pos     : 'top-center'
        });
    }else{
        if(type === 'message'){
            type = 'info';
        }
        UIkit.notify({
            message : '<i class="uk-icon-check"></i> '+ message,
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
                notify_show('info', 'Материалу будет присвоен url: '+data.message);
            }
        }
    });
}

function rebuild_cost() {
    $('#ModalToCart-form').find('input[name=kolvo]').keyup(function () {
        var cost = parseFloat($('#ModalToCart-form').find('.cost').attr('data-cost'));
        var kolvo = parseInt($(this).val());

        $('#ModalToCart-form').find('.cost').html(parseFloat(cost*kolvo).toFixed(2));
    })
}

function selectIdItem(id) {
    $('.actionSelect'+id).toggleClass('uk-icon-check');
    var countSelected = $('.actionSelect.uk-icon-check').length;
    if(countSelected > 0){
        $('form#massiveAction').find('span').html(countSelected);
        $('form#massiveAction').removeClass('uk-hidden');
    }else{
        $('form#massiveAction').addClass('uk-hidden');
    }
    if($('select[name="ids[]"]').find('option[value='+ id +']:selected').val()){
        $('select[name="ids[]"]').find('option[value='+ id +']').prop('selected', false);
    }else{
        $('select[name="ids[]"]').find('option[value='+ id +']').prop('selected', true);
    }
}