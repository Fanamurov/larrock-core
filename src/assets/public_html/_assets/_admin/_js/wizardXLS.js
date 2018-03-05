$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
});