$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.lilu-checkbox').click(function () {
        $('#myModal-loading').modal('show');
        $('#block_sorters').submit();
    });

    //$('.catalogBlockCategory').find('div').matchHeight();
    //$('.catalogBlockItem').matchHeight();
    //$('.matchHeight').matchHeight();

    $(".fancybox").fancybox({
        helpers	: {
            thumbs	: {
                width	: 50,
                height	: 50
            }
        }
    });

    /* http://maxoffsky.com/code-blog/laravel-shop-tutorial-3-implementing-smart-search/ */
    /* https://github.com/selectize/selectize.js/blob/master/docs/usage.md */
    $('select#searchCatalog').selectize({
        valueField: 'url_to_search',
        labelField: 'title',
        searchField: ['title'],
        maxOptions: 10,
        options: [],
        create: false,
        persist: false,
        optgroups: [
            {value: 'product', label: 'Товары'},
            {value: 'category', label: 'Разделы'}
        ],
        optgroupField: 'class_element',
        optgroupOrder: ['product','category'],
        render: {
            option: function(item, escape) {
                return '<div class="search">' +escape(item.title)+'</div>';
            }
        },
        load: function(query, callback) {
            this.clearOptions();
            if (!query.length) return callback();
            $.ajax({
                url: 'http://'+window.location.hostname+'/search/catalog',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query
                },
                error: function() {
                    noty_show('danger', 'Поиск не работает');
                    callback();
                },
                success: function(res) {
                    if(res.length < 1){
                        noty_show('message', 'По данному слову ничего не найдено');
                    }
                    callback(res);
                }
            });
        },
        onChange: function(){
            noty_show('message', 'Переход к результатам поиска');
            window.location = this.items[0];
            //window.location = '/search/catalog/serp/'+ query;
        }
    });

    //$('.typeahead').typeahead();
    //$('*[data-toggle=tooltip]').tooltip();

    $('input[name=date], input.date').pickadate({
        monthSelector: true,
        yearSelector: true,
        formatSubmit: 'yyyy-mm-dd',
        firstDay: 1,
        monthsFull: [ 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' ],
        weekdaysShort: [ 'Вск', 'Пон', 'Вт', 'Ср', 'Чт', 'Пт', 'Суб' ],
        format: 'yyyy-mm-dd'
    });

    /*
     * Универсальный обработчик для выделения блока как ссылки
     * Ищет внутри блока ссылку и присваивает ее всему блоку
     */
    $('.link_block').click(function(){window.location = $(this).find('a').attr('href');});
    $('.link_block_this').click(function(){window.location = $(this).attr('data-href');});

    $('.show-please').click(function(){
        var target = $(this).attr('data-target');
        var focus_element = $(this).attr('data-focus');
        $('.'+ target).removeClass('hidden');
        $('.'+ focus_element).focus();
        $(this).remove();
    });

    /** Conform alert. Уверены что хотите сделать это?) */
    $('.please_conform').on('click', function () {
        var href = $(this).attr('href');
        return confirm('Уверены?');
    });

    $('.change_limit:not(.active)').click(function(){
        noty_show('message', 'Страница обновляется...');
        $.ajax({
            url: '/ajax/editPerPage',
            type: 'POST',
            data: {
                q: $(this).attr('data-value')
            },
            error: function() {
                alert('ERROR!');
            },
            success: function() {
                window.location.href = window.location.href.split('?')[0];
            }
        })
    });

    $('.change_sort_cost:not(.active)').click(function(){
        noty_show('message', 'Страница обновляется...');
        $.ajax({
            url: '/ajax/sort',
            type: 'POST',
            data: {
                q: $(this).attr('data-value'),
                type: $(this).attr('data-type')
            },
            error: function() {
                alert('ERROR!');
            },
            success: function() {
                location.reload();
            }
        })
    });

    $('.change_catalog_template:not(.active)').click(function(){
        noty_show('message', 'Страница обновляется...');
        $.ajax({
            url: '/ajax/vid',
            type: 'POST',
            data: {
                q: $(this).attr('data-value')
            },
            error: function() {
                alert('ERROR!');
            },
            success: function() {
                location.reload();
            }
        })
    });

    /* Cart */
    $('.add_to_cart, .action_add_to_cart').click(function(){
        $('#ModalToCart').remove();
        $.ajax({
            url: '/ajax/getTovar',
            type: 'POST',
            dataType: 'html',
            data: {
                id: $(this).attr('data-id'),
                in_template: 'true'
            },
            error: function() {
                alert('ERROR!');
            },
            success: function(res) {
                $('header').after(res);
                UIkit.modal("#ModalToCart").show();
            }
        });
        return false;
    });

    $('.add_to_cart_fast').click(function(){
        $.ajax({
            url: '/ajax/cartAdd',
            type: 'POST',
            data: {
                id: parseInt($(this).attr('data-id'))
                //qty: parseInt($('#kolvo-'+ $(this).attr('data-id')).val())
            },
            dataType: 'json',
            error: function() {
                noty_show('alert', 'Возникла ошибка при добавлении товара в корзину');
            },
            success: function(res) {
                if(res.status === 'success'){
                    $('.cart-empty').addClass('hidden');
                    $('.cart-show').removeClass('hidden');
                    $('.total_cart').html(res.total);
                    $('.total_discount_cart').html(res.total_discount);
                    if(parseInt(res.total_discount) < 1){
                        $('.moduleCart-discount_row').hide();
                    }else{
                        $('.moduleCart-discount_row').show();
                    }
                    noty_show('message', res.message);
                }
                if(res.status === 'error'){
                    noty_show('message', res.message);
                }
            }
        });
        return false;
    });

    submit_to_cart();

    $('.removeCartItem').click(function(){
        var rowid = $(this).attr('data-rowid');
        $.ajax({
            url: '/ajax/cartRemove',
            type: 'POST',
            data: {
                rowid: rowid
            },
            error: function() {
                alert('ERROR!');
            },
            success: function(res) {
                if(res < 1){
                    location.reload();
                }else{
                    $('tr[data-rowid='+ rowid +']').remove();
                    $('.total').html(res);
                    $('.total_cart').html(res);
                    noty_show('message', 'Товар удален из корзины');
                }
            }
        });
    });

    $('#ModalToCart-form').find('.input-group-qty').spinner('changing', function(e, newVal, oldVal) {
        var rowid = $(this).attr('data-rowid');
        var qty = newVal;
        if(qty > 0){
            $.ajax({
                url: '/ajax/cartQty',
                type: 'POST',
                dataType: 'json',
                data: {
                    rowid: rowid,
                    qty: qty
                },
                error: function() {
                    noty_show('alert', 'Кол-во не изменено');
                },
                success: function(res) {
                    $('.total').html(res.total);
                    $('tr[data-rowid='+ rowid +']').find('.subtotal span').html(res.subtotal);
                    noty_show('success', 'Кол-во изменено');
                }
            });
        }
    });
    //.input-group-qty

    $('.editQty').change(function(){
        var rowid = $(this).attr('data-rowid');
        var qty = $(this).val();
        if(qty > 0){
            $.ajax({
                url: '/ajax/cartQty',
                type: 'POST',
                dataType: 'json',
                data: {
                    rowid: rowid,
                    qty: qty
                },
                error: function() {
                    noty_show('alert', 'Кол-во не изменено');
                },
                success: function(res) {
                    $('.total').html(res.total);
                    $('tr[data-rowid='+ rowid +']').find('.subtotal span').html(res.subtotal);
                    $('.total_discount').html(res.total_discount);
                    if(res.total_discount < 1){
                        $('.discount_row').hide();
                    }else{
                        $('.discount_row').show();
                    }
                    noty_show('success', 'Кол-во изменено');
                }
            });
        }
    });

    /* Кнопка НАВЕРХ */
    /*jQuery scrollTopTop v1.0 - 2013-03-15*/
    (function(a){a.fn.scrollToTop=function(c){var d={speed:800};c&&a.extend(d,{speed:c});
        return this.each(function(){var b=a(this);
            a(window).scroll(function() {
                var x = a(this).scrollTop();
                $('body').css('background-position', parseInt(-x * 2 / 10) + 'px '+ parseInt(-x * 2 / 10) + 'px');
                if(100 < a(this).scrollTop()){
                    b.fadeIn();
                }else{
                    b.fadeOut()
                }
            });
            b.click(function(b){b.preventDefault();a("body, html").animate({scrollTop:0},d.speed)})})}})(jQuery);
    $("#toTop").scrollToTop();
});

/* http://ned.im/noty/#/about */
function noty_show(type, message, container){
    if(container){
        if(type === 'alert'){
            container.noty({
                theme: 'relax',
                layout: 'center',
                text: message,
                type: 'alert',
                modal: true
            })
        }else{
            container.noty({
                theme: 'relax',
                layout: 'topRight',
                text: message,
                type: type,
                timeout: 2500
            })
        }
    }else{
        if(type === 'alert'){
            noty({
                theme: 'relax',
                layout: 'center',
                text: message,
                type: 'alert',
                modal: true
            })
        }else{
            noty({
                theme: 'relax',
                layout: 'topRight',
                text: message,
                type: type,
                timeout: 2500
            })
        }
    }
}

function submit_to_cart() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.submit_to_cart').click(function () {
        var action = $(this).attr('data-link');
        var tovar_params = {};
        //Сканим параметры товара
        $('.tovar-params').each(function () {
            tovar_params[$(this).attr('data-title')] = $(this).val();
        });
        tovar_params = JSON.stringify(tovar_params);
        $.ajax({
            url: '/ajax/cartAdd',
            type: 'POST',
            dataType: 'json',
            data: {
                id: parseInt($(this).attr('data-id')),
                qty: parseInt($('#kolvo-'+ $(this).attr('data-id')).val()),
                options: tovar_params
            },
            error: function() {
                noty_show('alert', 'Возникла ошибка при добавлении товара в корзину');
            },
            success: function(res) {
                if(res.status === 'success'){
                    $('.cart-empty').hide();
                    $('.cart-show').removeClass('hidden');
                    $('.total_cart').html(res.total);
                    $('.total_discount_cart').html(res.total_discount);
                    if(parseInt(res.total_discount) < 1){
                        $('.moduleCart-discount_row').hide();
                    }else{
                        $('.moduleCart-discount_row').show();
                    }
                    noty_show('message', res.message);
                    if(action === '/cart'){
                        window.location.href = '/cart';
                    }else{
                        $('#ModalToCart').modal('hide');
                    }
                }
                if(res.status === 'error'){
                    noty_show('message', res.message);
                }
            }
        });
    });
}

/*function valid_modal_cart(min_kolvo) {
 $("#ModalToCart-form").validate({
 rules: {
 kolvo: {
 required: true,
 min: parseInt(min_kolvo)
 }
 },
 messages: {
 kolvo: {
 min: "Минимальная партия для заказа "+ min_kolvo
 }
 }
 });
 }*/

function rebuild_cost() {
    $('.cart_item_row').each(function () {
        var nalicie = parseInt($(this).find('.nalichie').attr('data-count'));
        var current_qty = parseInt($(this).find('.editQty').val());
        /*if(nalicie <= current_qty){
         $(this).find('.addon-what').hide();
         }else{
         $(this).find('.addon-what').show();
         }
         if(current_qty === 1){
         $(this).find('.addon-x').hide();
         }else{
         $(this).find('.addon-x').show();
         }*/
    });

    $("#modal-spinner")
        .spinner('changing', function(e, newVal, oldVal) {
            var cost = parseFloat($('#ModalToCart-form').find('.cost').attr('data-cost'));
            $('#ModalToCart-form').find('.cost').html(cost*newVal);
        });

    $(".spinner-qty")
        .spinner('changing', function(e, newVal, oldVal) {
            var qty = newVal;
            var cost = parseFloat($(this).attr('data-cost'));
            var rowid = $(this).attr('data-rowid');
            if(qty > 0){
                $.ajax({
                    url: '/ajax/cartQty',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        rowid: rowid,
                        qty: qty
                    },
                    error: function() {
                        noty_show('alert', 'Кол-во не изменено');
                    },
                    success: function(res) {
                        $('.total').html(res.total);
                        $('.total_cart').html(res.total);
                        if(res.total_discount > 0){
                            $('.total_discount_cart').html(parseInt(res.total) - parseInt(res.total_discount));
                            $('.total_discount').html(res.total_discount);
                            $('.discount_row').show();
                        }else{
                            $('.discount_row').hide();
                        }
                        $('tr[data-rowid='+ rowid +']').find('.subtotal span').html(res.subtotal);
                        noty_show('success', 'Количество изменено');
                    }
                });
            }
        });

    $('#ModalToCart-form').find('input[name=kolvo]').keyup(function () {
        var cost = parseFloat($('#ModalToCart-form').find('.cost').attr('data-cost'));
        var kolvo = parseInt($(this).val());
        $('#ModalToCart-form').find('.cost').html(cost*kolvo);
    })
}