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

function link_block() {
    /** Ищет внутри блока ссылку и присваивает ее всему блоку */
    $('.link_block').click(function(){window.location = $(this).find('a').attr('href');});

    /** Ищет в элементе аттрибут data-href и делает блок ссылкой */
    $('.link_block_this').click(function(){window.location = $(this).attr('data-href');});
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
