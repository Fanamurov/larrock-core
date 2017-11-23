/**
 * Всплывающие уведомления в интерфейсе
 * @param type
 * @param message
 */
function noty_show(type, message){
    $('#modalNotify').addClass('uk-open').addClass('uk-display-block');
    if(type === 'error' || type === 'alert'){
        UIkit.notify({
            message : '<i class="uk-icon-bug"></i> '+ message,
            status  : 'danger',
            timeout : 0,
            pos     : 'top-center'
        });
    }else{
        UIkit.notify({
            message : '<i class="uk-icon-check"></i> '+ message,
            status  : 'primary',
            timeout : 3000,
            pos     : 'top-right'
        });
    }

    setTimeout(function () {
        $('#modalNotify').removeClass('uk-open').removeClass('uk-display-block');
    }, 3000);
}

function rebuild_cost() {
    $("#modal-spinner")
        .spinner('changing', function(e, newVal) {
            var cost = parseFloat($('#ModalToCart-form').find('.cost').attr('data-cost'));
            $('#ModalToCart-form').find('.cost').html(parseFloat(cost*newVal).toFixed(2));
        });

    $(".spinner-qty")
        .spinner('changing', function(e, newVal) {
            var qty = newVal;
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
                            $('.total_discount_cart').html(parseInt(res.total).toFixed(2) - parseInt(res.total_discount).toFixed(2));
                            $('.total_discount').html(parseFloat(res.total_discount));
                            $('.discount_row').show();
                        }else{
                            $('.discount_row').hide();
                        }
                        $('tr[data-rowid='+ rowid +']').find('.subtotal span.subtotal').html(parseFloat(res.subtotal).toFixed(2));
                    }
                });
            }
        });

    $('#ModalToCart-form').find('input[name=kolvo]').keyup(function () {
        var cost = parseFloat($('#ModalToCart-form').find('.cost').attr('data-cost'));
        var kolvo = parseInt($(this).val());
        $('#ModalToCart-form').find('.cost').html(parseFloat(cost*kolvo).toFixed(2));
    })
}

/**
 * LarrockCart
 * Вызов модального окна для добавления товара в корзину
 *
 * attr data-id - ID товара каталога
 */
function add_to_cart() {
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
}

/**
 * LarrockCatalog
 * Изменение параметров каталога (или любых других через контроллер Ajax)
 *
 * attr data-option - метод
 * attr data-value - передаваемое значение
 * attr data-type - другое передаваемое значение
 */
function change_option_ajax() {
    $('.change_option_ajax:not(.uk-active)').click(function () {
        var option = $(this).attr('data-option');
        noty_show('message', 'Страница обновляется...');
        $.ajax({
            url: '/ajax/'+ option,
            type: 'POST',
            data: {
                q: $(this).attr('data-value'),
                type: $(this).attr('data-type')
            },
            error: function() {
                alert('ERROR!');
            },
            success: function() {
                window.location.href = window.location.href.split('?')[0];
            }
        })
    });
}

/**
 * LarrockCart
 * Добавление товара в корзину без всплывающего окна
 * Использование: присваиваем html-элементу класс add_to_cart_fast и добавляем аттрибут data-id c ID товара
 *
 * attr data-id - ID товара
 * attr data-qty - количество добавляемого товара
 */
function add_to_cart_fast() {
    $('.add_to_cart_fast').click(function(){
        noty_show('message', 'Добавляем в корзину');
        var qty = 1;
        if(parseInt($(this).attr('data-id')) > 0){
            qty = parseInt($(this).attr('data-qty'));
        }
        $.ajax({
            url: '/ajax/cartAdd',
            type: 'POST',
            data: {
                id: parseInt($(this).attr('data-id')),
                qty: qty
            },
            dataType: 'json',
            error: function() {
                noty_show('alert', 'Возникла ошибка при добавлении товара в корзину');
            },
            success: function(res) {
                if(res.status === 'success'){
                    $('.cart-empty').addClass('uk-hidden');
                    $('.cart-show').removeClass('uk-hidden');
                    if(parseFloat(res.total) < 1){
                        $('.cart-text').html('В корзине товаров: '+ res.count);
                    }else{
                        $('.cart-text').html('В корзине на сумму <span class="total_cart text">'+ res.total +'</span> р.');
                    }
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
}

/**
 * LarrockCart
 * Удаление элемента корзины
 *
 * attr data-rowid - rowID элемента корзины
 */
function removeCartItem() {
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
                if(parseInt(res) < 1){
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
}

/**
 * LarrockCart
 * Изменение количества товара в корзине в модальном окне
 *
 * attr data-rowid - rowID элемента корзины
 * value qty - Нужное количество товара (input value)
 */
function editQty() {
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
                }
            });
        }
    });
}

function link_block() {
    /** Ищет внутри блока ссылку и присваивает ее всему блоку */
    $('.link_block').click(function(){window.location = $(this).find('a').attr('href');});

    /** Ищет в элементе аттрибут data-href и делает блок ссылкой */
    $('.link_block_this').click(function(){window.location = $(this).attr('data-href');});
}

function checkKuponDiscount() {
    var keyword = $('input[name=kupon]').val();
    $.ajax({
        url: '/ajax/checkKuponDiscount',
        type: 'POST',
        dataType: 'json',
        data: {
            keyword: keyword
        },
        error: function() {
            noty_show('alert', 'Такого купона нет');
            $('.kupon_text').slideUp().html();
        },
        success: function(res) {
            noty_show('success', 'Купон "'+ keyword +'" будет применен');
            $('.kupon_text').slideDown().html(res.message);
        }
    });
}

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("[data-fancybox], .fancybox").fancybox({
        infobar : true,
        buttons : [
            'slideShow',
            'fullScreen',
            'thumbs',
            'close'
        ],
        hash: true,
        thumbs : {
            autoStart   : true,   // Display thumbnails on opening
            hideOnClose : true     // Hide thumbnail grid when closing animation starts
        },
        lang : 'ru',
        i18n : {
            'ru' : {
                CLOSE       : 'Закрыть',
                NEXT        : 'Дальше',
                PREV        : 'Назад',
                ERROR       : 'The requested content cannot be loaded. <br/> Please try again later.',
                PLAY_START  : 'Начать слайдшоу',
                PLAY_STOP   : 'Пауза',
                FULL_SCREEN : 'В полный экран',
                THUMBS      : 'Превью'
            }
        },
        caption : function() {
            return $(this).data('caption') || $(this).attr('title') || '';
        }
    });

    add_to_cart();
    add_to_cart_fast();
    change_option_ajax();
    removeCartItem();
    editQty();
    link_block();

    $('.showModalLoading').click(function () {
        UIkit.modal("#modalProgress").show();
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

    /* Кнопка НАВЕРХ */
    /*jQuery scrollTopTop v1.0 - 2013-03-15*/
    (function(a){a.fn.scrollToTop=function(c){var d={speed:800};c&&a.extend(d,{speed:c});
        return this.each(function(){var b=a(this);
            a(window).scroll(function() {
                /*var x = a(this).scrollTop();
                $('body').css('background-position', parseInt(-x * 2 / 10) + 'px '+ parseInt(-x * 2 / 10) + 'px');*/
                if(100 < a(this).scrollTop()){
                    b.fadeIn();
                }else{
                    b.fadeOut()
                }
            });
            b.click(function(b){b.preventDefault();a("body, html").animate({scrollTop:0},d.speed)})})}})(jQuery);
    $("#toTop").scrollToTop();

    $('#modalNotify').click(function () {
        $('#modalNotify').removeClass('uk-open');
    });
});