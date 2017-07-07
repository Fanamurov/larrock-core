/**
 * Оджинали аппиаред ин джейКоммон
 * 
 * @author Vlad Yakovlev (red.scorpix@gmail.com)
 * @copyright Art.Lebedev Studio (http://www.artlebedev.ru)
 * @version 0.3 alpha 7
 * @date 2009-12-29
 * @requires jQuery 1.3.2
 * 
 * Отслеживает изменение размеров окна браузера и масштабирование текста.
 * Отслеживание запускается только при добавлении первого хэндлера.
 *
 * @example
 * function funcBind() { alert('yoop'); }
 * measurer.bind(funcBind);
 * @description Теперь функция будет выполняться всякий раз, когда изменится размер окна браузера или размер текста.
 * measurer.unbind(funcBind);
 * @description А теперь — нет.
 *
 * @version 1.0
 */
$measurer = function() {

	var
		callbacks = [],
		interval = 500,
		curHeight,
		el = null,
		isInit = false,
		isDocReady = false;

	$(function() {
		isDocReady = true;
		isInit && initBlock();
	});

	function createBlock() {
		if (el == null) {
			el = $('<div></div>').css('height', '1em').css('left', '0').css('lineHeight', '1em').css('margin', '0').
			css('position', 'absolute').css('padding', '0').css('top', '-1em').css('visibility', 'hidden').
			css('width', '1em').appendTo('body');

			curHeight = el.height();
		}
	}

	function getHeight() {
		return curHeight;
	}

	function initBlock() {
		createBlock();

		$(window).resize(callFuncs);

		/**
		 * В IE событие <code>onresize</code> срабатывает и на элементах.
		 */
		/*if ($.browser.msie) {
			el.resize(callFuncs);
			return;
		}*/

		/**
		 * Для остальных браузеров периодически проверяем изменение размера текста.
		 */
		curHeight = el.height();
		setInterval(function() {
			var newHeight = el.height();

			if (newHeight != curHeight) {
				curHeight = newHeight;
				callFuncs();
			}
		}, interval);
	}

	function callFuncs() {
		for(var i = 0; i < callbacks.length; i++) {
			callbacks[i]();
		}
	}

	return {
		/**
		 * Ручная инициализация события изменения размеров элементов на странице.
		 */
		resize: callFuncs,

		/**
		 * Добавляет обработчик события.
		 * @param {Function} func Ссылка на функцию, которую нужно выполнить.
		 */
		bind: function(func) {
			if (!el) {
				isInit = true;
				isDocReady && initBlock();
			}

			callbacks.push(func);
		},

		/**
		 * Удаляет обработчик события.
		 */
		unbind: function(func) {
			for(var i = 0; i < callbacks.length; i++) {
				callbacks[i] == func && callbacks.splice(i, 1);
			}
		},
		
		getHeight: getHeight,
		createBlock: createBlock
	};
}();