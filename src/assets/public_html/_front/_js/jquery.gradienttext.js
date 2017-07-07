/**
 * @author Leechy (leechy@leechy.ru)
 * @link www.artlebedev.ru
 * @requires jQuery
 *
 * Description:
 * gradientText is a jQuery plugin that paints text in gradient colors
 *
 * Usage:
 * $(selector).gradientText(config);
 *
 * config is an object contents configuraton paramenters:
 * 	{Array} colors - array of hex colors, e.g. ['#000000', '#FFFFFF'];
 * 	{Array} toProcess - array of jQuery selectors, matched elements will be toProcessed
 */

(function($){
	// Параметры по умолчанию
	$.gradientText = $.gradientText || {version: '1.0'};

	$.gradientText.conf = {
		colors: ['#5f3db6', '#c10000'],
		toProcess: []
	};

	$.gradientTextSetup = function(conf) {
		$.extend($.gradientText.conf, conf);
	};

	$.fn.gradientText = function(conf) {
		
		// already constructed --> return API
		var el = this.data("gradientText");
		if (el) { return el; }
				
		// concatinate defined conf object with the user's one
		if (!conf) {
			conf = $.gradientText.conf;
		} else {
			if (typeof(conf.colors) == 'undefined') {
				conf.colors = $.gradientText.conf.colors;
			}
		}

		var aLetters = [];

		this.each(function(i) {
			aLetters[i] = new GradientLetters($(this), conf);
			$(this).data("gradientText", aLetters[i]);	
		});
		
		$(window).load(function() {
			var iLetters_amount = aLetters.length;
			for (var i = 0; i < iLetters_amount; i++) {
				aLetters[i].update();
			}
		});

		return conf.api ? el: this; 
	};


	function GradientLetters(jContainer, conf) {
		/**
		 * 	Если плагин уже поработал над элементом,
		 * 	то заново дробить его не нужно
		 */
		if (jContainer.find('span.gr-text').size() == 0) {
			/**
			 * 	getting nodes, good enough
			 * 	to be spliced in letters
			 */
			var jTextNodes = jContainer.contents().filter(function() {
				return (this.nodeType == 3 && /\S/.test(this.nodeValue))
			}).wrap('<span class="gr-text" />');

			if (typeof(conf.toProcess) != 'undefined') {
				var tags = conf.toProcess.toString();

				if (tags) {
					jTextNodes = jContainer.find(tags).contents().filter(function() {
						return (this.nodeType == 3 && /\S/.test(this.nodeValue))
					}).wrap('<span class="gr-text" />');
				}
			}

			/**
			 * 	width of the content can be less than jContainer's width
			 * 	that's why we have to use inline wrapper like span
			 */
			jContainer.html('<span class="gr-wrap">' + jContainer.html() + '</span>');
			jContainer = jContainer.find('.gr-wrap');

			/**
			*	Оборачиваем каждую букву в span.gr-letter.
			*	Пробелы заменяем на пробел нулевой ширины
			*/
			jContainer.find('span.gr-text').each(function(){
				var aText = $(this).text().split('');
				var sHTML = '';
				var iText_amount = aText.length;

				for (var i = 0; i < iText_amount; i++) {
					if (aText[i] != ' ') {
						sHTML += '<span class="gr-letter">' + aText[i] + '</span>';
					} else {
						sHTML += '<span class="gr-letter"><span style="display:none;">&#8203;</span> </span>';
					}
				}
				$(this).html(sHTML);
			});
		}

		var jWords = jContainer.find('span.gr-text');
		var jLetters = jContainer.find('span.gr-letter');
		var iHeight = 0;

		// Convert defined hex colors to rgb-colors
		conf.RGBcolors = [];
		for (var i = 0; i < conf.colors.length; i++) conf.RGBcolors[i] = hex2Rgb(conf.colors[i]);

		/**
		 * 	Measurer — некий объект, который понимает не только когда изменяется ширина окна,
		 * 	но и когда меняется размер шрифта.
		 *
		 * 	Плагин использует:
		 *	- jcommon, если он есть;
		 *	- measurer, если нет jcommon и подключен файл с measurer,
		 *	- resize, если по какой-то причине ни того, ни другого не обнаружено.
		 */
		if (typeof($c) != 'undefined') $c.measurer.bind(updateColors);
		else if (typeof($measurer) != 'undefined') $measurer.bind(updateColors);
		else $(window).resize(updateColors);

		PaintUnderlines();

		function updateColors() {
			var iRootLeftOffset = Math.round(jContainer[0].offsetLeft),
				iRootWidth = getMaxRootWidth(iRootLeftOffset),
				jLetters_amount = jLetters.size();

			if (iRootWidth < 200) iRootWidth = 200;

			for( var i = jLetters_amount; i--; ) {
				jLetters[i].style.color = getColor(Math.round(jLetters[i].offsetLeft - iRootLeftOffset), iRootWidth);
			}
		}

		function getMaxRootWidth(iRootLeftOffset) {
			var iMaxWidth = 0;
			jWords.each(function(index) {
				var iRightEdge = Math.round(this.offsetWidth + this.offsetLeft) - iRootLeftOffset;
				if (iRightEdge > iMaxWidth) iMaxWidth = iRightEdge;
			});
			return iMaxWidth;
		}

		function getColor(iLeftOffset, iRootWidth) {
			var
				fLeft = (iLeftOffset > 0)? (iLeftOffset / iRootWidth) : 0;
			for (var i = 0; i < conf.colors.length; i++) {
				fStopPosition = (i / (conf.colors.length - 1));
				fLastPosition = (i > 0)? ((i - 1) / (conf.colors.length - 1)) : 0;

				if (fLeft == fStopPosition) {
					return conf.colors[i];
				} else if (fLeft < fStopPosition) {
					fCurrentStop = (fLeft - fLastPosition) / (fStopPosition - fLastPosition);
					return getMidColor(conf.RGBcolors[i-1], conf.RGBcolors[i], fCurrentStop);
				}
			}
			return conf.colors[conf.colors.length - 1];
		}

		function getMidColor(aStart, aEnd, fMidStop) {
			var aRGBColor = [];

			for (var i = 0; i < 3; i++) {
				aRGBColor[i] = aStart[i] + Math.round((aEnd[i] - aStart[i]) * fMidStop)
			}

			return rgb2Hex(aRGBColor)
		}


		/**
		* To paint underline of gradiented text in right colors
		* every .gr-letter element has to have css rule:
		* 	text-decoration: underline;
		* so this function searching for .gr-text that is child
		* of underlined element
		*/
		function PaintUnderlines () {
			/* When gradiented element contains underlined child */
			jContainer.find('.gr-text').each(function(){
				if ($(this).parent().css('text-decoration') == 'underline') {
					$(this).parent().find('.gr-letter').css('text-decoration', 'underline');
				}
			});

			/* When gradiented element is underlined */
			if (jContainer.parent().css('text-decoration') == 'underline') {
				jContainer.find('.gr-letter').css('text-decoration', 'underline');
			}
		}

		return {
			update: updateColors
		}
	}

	/**
	 * Преобразует HEX-представление цвета в RGB.
	 * @param {String} hex
	 * @return {Array}
	 */
	function hex2Rgb(hex) {
		if ('#' == hex.substr(0, 1)) {
			hex = hex.substr(1);
		}
		if (3 == hex.length) {
			hex = hex.substr(0, 1) + hex.substr(0, 1) + hex.substr(1, 1) + hex.substr(1, 1) + hex.substr(2, 1) + hex.substr(2, 1);
		}

		return [parseInt(hex.substr(0, 2), 16), parseInt(hex.substr(2, 2), 16), parseInt(hex.substr(4, 2), 16)];
	}

	/**
	 * Преобразует RGB-представление цвета в HEX.
	 * @param {Array} rgb
	 * @return {String}
	 */
	function rgb2Hex(rgb) {
		var s = '0123456789abcdef';

		return '#' + s.charAt(parseInt(rgb[0] / 16)) + s.charAt(rgb[0] % 16) + s.charAt(parseInt(rgb[1] / 16)) +
			s.charAt(rgb[1] % 16) + s.charAt(parseInt(rgb[2] / 16)) + s.charAt(rgb[2] % 16);
	}
})( jQuery );