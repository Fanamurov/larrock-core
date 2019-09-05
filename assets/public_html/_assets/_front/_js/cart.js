$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    rebuild_cost();
    add_to_cart();
    add_to_cart_fast();
    removeCartItem();
});

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
                        $('.row-total').find('.total').html(res.total);
                        $('.row-clear-total').find('.clear-total').html(res.clear_total);
                        $('.row-total-discount').find('.total-discount').html(res.profit);
                        $('tr[data-rowid='+ rowid +']').find('.subtotal span.subtotal').html(res.subtotal);
                        $('.repeat-total-cost').html(res.total);
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
                qty: qty,
                costValueId: $(this).attr('data-costValueId'),
                cost: $(this).attr('data-cost')
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
                    $('.repeat-total-cost').html(res);
                    noty_show('message', 'Товар удален из корзины');
                }
            }
        });
    });
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
            $('.kupon_text').slideDown().html(res.description);
        }
    });
}
