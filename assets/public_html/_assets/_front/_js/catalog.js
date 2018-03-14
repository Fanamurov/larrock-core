$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    change_option_ajax();
    changeCostValue();
    changeParamValue();
});


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

/** Смена модификации товара на его странице */
function changeCostValue() {
    $('.changeCostValue').click(function () {
        var cost = parseFloat($(this).find('input').val());
        var costValueId = $(this).find('input').attr('data-costValueId');
        $('.catalogPageItem').find('.cost_value').html(cost);
        $('.add_to_cart_fast').attr('data-cost', cost);
        $('.add_to_cart_fast').attr('data-costValueId', costValueId);
    });
}

/** Смена модификации товара в blockItem */
function changeParamValue() {
    $('.changeParamValue').click(function () {
        var id = $(this).attr('data-tovar-id');
        var cost = $(this).attr('data-cost');
        var param = $(this).attr('data-param');
        var title = $(this).attr('data-title');

        $('.changeParamValue[data-tovar-id='+ id +']').removeClass('uk-active');
        $(this).addClass('uk-active');
        $('.add_to_cart_fast[data-id='+id+']').attr('data-costValueId', param);
        $('.catalogBlockItem[data-id='+ id +']').find('.costValue').html(cost);
        $('.catalogBlockItem[data-id='+ id +']').find('.costValueTitle').html(title);
    });
}