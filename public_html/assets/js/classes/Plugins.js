/**
 * Construção da classe Plugins() Esta classe realiza o carregamento de todos os
 * plugins que existirem no sistema
 */
function Plugins()
{

	if($.fn.appear)
	{
		$('[data-ride="animated"]').appear();
		if( !$('html').hasClass('ie no-ie10'))
		{
			$('[data-ride="animated"]').addClass('appear');
			$('[data-ride="animated"]').on('appear', function()
			{
				var $el = $(this),
				    $ani = ($el.data('animation') || 'fadeIn'),
				    $delay;
				if( !$el.hasClass('animated'))
				{
					$delay = $el.data('delay') || 0;
					setTimeout(function()
					{
						$el.removeClass('appear').addClass($ani + ' animated');
					}, $delay);
				}
			});
		}
		;
		$('.number-animator').appear();
		$('.number-animator').on('appear', function()
		{
			$(this).animateNumbers($(this).attr('data-value'), true, parseInt($(this).attr('data-animation-duration')));
		});

		$('.animated-progress-bar').appear();
		$('.animated-progress-bar').on('appear', function()
		{
			$(this).css('width', $(this).attr('data-percentage'));
		});
	}

	/** ** Animate Numbers *** */
	if($.fn.animateNumbers)
	{
		$('.animate-number').each(function()
		{
			$(this).animateNumbers($(this).attr('data-value'), true, parseInt($(this).attr('data-animation-duration')));
		});

	}

	$('.animate-progress-bar').each(function()
	{
		$(this).css('width', $(this).attr('data-percentage'));
	});

	$('.portfolio-grid ul li').hover(function()
	{
		var imgHeight = $(this).find('img').height();
		$(this).find('.portfolio-image-wrapper').height(imgHeight);
	});

	/**
	 * Carregamento do plugin iCheck para todos os inputs checkbox ou radio que
	 * possuírem a classe 'icheckbox'
	 *
	 * @param {Object}
	 *            element
	 * @param {Object}
	 *            style
	 * @param {Object}
	 *            color
	 *
	 */
	function iCheckbox(element, style, color)
	{
		if($.fn.iCheck)
		{
			var style = typeof style !== 'undefined' ? style : 'minimal';
			var color = typeof color !== 'undefined' ? color : 'red';
			jQuery(element).iCheck(
			{
				checkboxClass : 'icheckbox_' + style + '-' + color,
				radioClass : 'iradio_' + style + '-' + color
			});
		}
	}

	/**
	 * Carregamento do plugins select2 para todos os elementos {select}
	 * declarados
	 *
	 * @param {Object} element
	 */
	function Select2(element)
	{

		if($.fn.select2)
		{

			$('.select2-container').addClass('waves waves-effect');

			$('body').find(element).each(function()
			{

				var $parent = $(this).parent();

				if($(this).hasClass('no-request'))
					return false;

				var $option =
				{
					placeholder : $(this).attr('placeholder'),
					minimumInputLength : ( typeof $(this).attr('data-minimum') !== 'undefined' ? $(this).attr('data-minimum') : 1 ),
					minimumResultsForSearch : ( typeof $(this).attr('data-results') !== 'undefined' ? $(this).attr('data-results') : 2 ),
				};

				$(this).parent('.select-wrapper').find('input.select-dropdown, .dropdown-content, svg').remove();

				if( typeof $(this).data('url') !== 'undefined' && $option.minimumInputLength > 0 )
				{

					var self = $(this);
					var url = self.data('url');

					$option =
					{
						ajax :
						{
							url : url,
							type : 'post',
							dataType : 'json',
							delay : 250,
							data : function(params)
							{
								var query =
								{
									search : params.term,
									type : 'public'
								};
								return query;
							},
							success : function(data)
							{

								var option = new Option(data.text, data.id, true, true);

								$('.select2-results__options').append(option).trigger('change');

								$('.select2-results__options').trigger(
								{
									type : 'public',
									params :
									{
										data : data
									}
								});

							},
							processResults : function(data)
							{
								var params =
								{
									results : data
								};
								return params;
							},

						},
						placeholder : ( typeof $(this).attr('placeholder') !== 'undefined' && $(this).attr('placeholder') !== null ? $(this).attr('placeholder') : null),
						minimumInputLength : ( typeof $(this).attr('data-minimum') !== 'undefined' ? $(this).attr('data-minimum') : 1 ),
						minimumResultsForSearch : ( typeof $(this).attr('data-results') !== 'undefined' ? $(this).attr('data-results') : 2 ),
						// dropdownParent : $parent,
						// allowClear: true,
						// theme: 'material'
						// readonly : true
					};

					var $selected = typeof $(this).attr('data-selected') !== 'undefined' && $(this).attr('data-selected') !== '' ? $(this).attr('data-selected') : null;

					jQuery(this).select2($option).val($selected).change();

				}
				else
				{
					var $selected = typeof $(this).attr('data-selected') !== 'undefined' && $(this).attr('data-selected') !== '' ? $(this).attr('data-selected') : null;
					jQuery(this).select2($option).val($selected).change();
				}

			});

		}

	}

	/**
	 * Plugin para editor de texto
	 */
	function Redactor(el)
	{

// 		var element = typeof el !== 'undefined' ? el : ['.editor', '.redactor'];
// 		var items = element.toString();

// 		// initialize each instance with posIndex param
// 		$.each($(element), function(index, node)
// 		{
// 			$(node).materialnote(
// 			{
// 				height : 300,
// 				posIndex : index
// 			});
// 		});

	}

	/**
	 * Carregamento do plugin BootstrapToggle para inputs checkbox
	 */
	function Switch()
	{
		if($.fn.bootstrapToggle)
		{
			jQuery('[data-toggle="switch"]').bootstrapToggle('destroy');
			jQuery('[data-toggle="switch"]').bootstrapToggle();
		}
	}

	function Tooltip()
	{
		if($.fn.tooltip)
			$('[data-tooltip]').tooltip(
			{
				delay : 0
			});
	}

	function modal()
	{

		if($.fn.modal)
			$('.modal').modal();

	}

	/**
	 * Inicialização do FrameWork Materializecss
	 */
	function Materialize()
	{

		// /** Scrollpsy **/
		// $('.scrollspy').scrollSpy();
		//
		// /** Ampliar imagens **/
		// $('.materialboxed').materialbox();
		//
		// /** Floating Action Buttons - Botões de ação **/
		// $('.fixed-action-btn').floatingActionButton(
		// {
		// direction : typeof $(this).data('direction') !== 'undefined' &&
		// $(this).data('direction') !== null ? $(this).data('direction') : 'top',
		// hoverEnabled : false
		// });
		//
		// /** Collapsible - Recolher menus **/
		// $('.collapsible').collapsible();
		//
		// if($('.sidenav').length)
		// {
		// /** Sidenav - Para barras de menus escondidas **/
		// $('.sidenav').sidenav();
		// // $('body').perfectScrollbar();
		// // or just with selector string
		// // var ps = new PerfectScrollbar('html');
		// var ps = new PerfectScrollbar('.sidenav');
		// }

	}

	//
	// /**
	// * Carregamento do plguin de Datas nos inputs selecionados.
	// *
	// * @param {Object}
	// *            element
	// */
	// function Datepicker(element)
	// {
	// $('.datepicker').datepicker(
	// {
	// format : 'yyyy-mm-dd'
	// });
	// // if($.fn.datepicker)
	// // {
	// // var maxDays = 1;
	// // var diff = 0;
	// // var dateFormat = 'dd/mm/yyyy';
	// // var date = jQuery(element).datepicker(
	// // {
	// // defaultDate : '+1w',
	// // changeMonth : false,
	// // changeYear : false,
	// // numberOfMonths : 1,
	// // minDate : 0,
	// // });
	// // }
	// }
	//
	/**
	 * Carregamento do plugin para estilo da barra de rolagem
	 *
	 * @param {Object}
	 *            element
	 */
	function Scrollbar(element)
	{
		if($.fn.scrollbar)
		{
			var el = typeof element !== 'undefined' || '#main-menu-wrapper' || '.sidenav';
			$(el).scrollbar();
		}

		/** ** Scroller *** */
		if($.fn.niceScroll)
		{
			var mainScroller = $('html').niceScroll(
			{
				zindex : 999999,
				boxzoom : true,
				cursoropacitymin : 0.5,
				cursoropacitymax : 0.8,
				cursorwidth : '10px',
				cursorborder : '0px solid',
				autohidemode : false
			});
		}

	}

	// /**
	// * Retina Image Loader
	// */
	// if($.fn.unveil)
	// {
	// $('img').unveil();
	// }
	//
	// /** ** Carousel for Testominals *** */
	// if($.fn.owlCarousel)
	// {
	//
	// $('#parceiros').owlCarousel(
	// {
	//
	// items : 5,
	// autoPlay : true,
	// stopOnHover : true,
	// pagination : false,
	//
	// dragBeforeAnimFinish : true,
	// mouseDrag : false,
	// touchDrag : true,
	//
	// addClassActive : false,
	// transitionStyle : false,
	//
	// beforeUpdate : false,
	// afterUpdate : false,
	// beforeInit : false,
	// afterInit : false,
	// beforeMove : false,
	// afterMove : false,
	// afterAction : false,
	// startDragging : false,
	// afterLazyLoad : false
	//
	// });
	//
	// $('#testomonials').owlCarousel(
	// {
	// singleItem : true,
	// autoPlay : true,
	// stopOnHover : true,
	// pagination : false,
	// });
	//
	// }
	//
	// /** ** Mobile Side Menu *** */
	// if($.fn.waypoint)
	// {
	// var $head = $('#ha-header');
	// $('.ha-waypoint').each(function(i)
	// {
	// var $el = $(this),
	// animClassDown = $el.data('animateDown'),
	// animClassUp = $el.data('animateUp');
	//
	// $el.waypoint(function(direction)
	// {
	// if(direction === 'down' && animClassDown)
	// {
	// $head.attr('class', 'ha-header ' + animClassDown);
	// }
	// else if(direction === 'up' && animClassUp)
	// {
	// $head.attr('class', 'ha-header ' + animClassUp);
	// }
	// },
	// {
	// offset : '100%'
	// });
	// });
	// }

	//
	// function thumbs()
	// {
	//
	// if($('#thumbs').length > 0)
	// {
	// var $container = $('#thumbs');
	// $container.isotope(
	// {
	// filter : '*',
	// animationOptions :
	// {
	// duration : 750,
	// easing : 'linear',
	// queue : false
	// }
	// });
	//
	// $(window).resize(function()
	// {
	// var $container = $('#thumbs');
	// $container.isotope(
	// {
	// itemSelector : '.item',
	// animationOptions :
	// {
	// duration : 250,
	// easing : 'linear',
	// queue : false
	// }
	// });
	// });
	//
	// // filter items when filter link is clicked
	// $('#portfolio-nav a, #gallery-nav a').click(function()
	// {
	// var selector = $(this).attr('data-filter');
	//
	// $container.isotope(
	// {
	// filter : selector
	// });
	//
	// $('#portfolio-nav li, #gallery-nav li').removeClass('current');
	// $(this).closest('li').addClass('current');
	//
	// return false;
	// });
	// }
	//
	// }

	/**
	 * Construtor da classe
	 */
	this.Materialize = Materialize();
	this.iCheckbox = iCheckbox;
	this.Select2 = Select2;
	// this.Datepicker = Datepicker;
	this.Scrollbar = Scrollbar;
	this.Switch = Switch;
	this.Tooltip = Tooltip;
	this.Redactor = Redactor;
	this.Modal = modal;

	return this;

}
