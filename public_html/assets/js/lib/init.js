function Init()
{

	var i = 0;
	var interval;

	$('[data-tooltip]').tooltip().on('click', function()
	{
		$(this).tooltip('close');
	});

	if( typeof habilidades === 'function')
	{
		habilidades();
	}

	require([BASEPATH + 'js/lib/materialize.js'], function()
	{
		Materializecss();
	});

	var h;

	recalcula_scrollbar();

	$(window).resize(function()
	{
		h = $('.pdv').outerHeight();
		recalcula_scrollbar();
	});

	$(document).ready(function()
	{
		$('body').find('form').find('.autofocus').focus();

		$('.autofocus').focus();

	});

	$('.reload').on('click', function()
	{
		preloader('out', false);
		$(this).attr('disabled', 'disabled');
		uri = new URI();
		uri.pushState(CURRENT_URL);
		// location.href = window.location.href;
	});

	function recalcula_scrollbar()
	{

		$('.coluna').each(function()
		{
			h = $('body').find('.pdv').length ? $('.pdv').outerHeight() : $(this).attr('data-height');

			if(h != null || h != "")
			{
				if($(this).length > 0)
					$(this).css(
					{
						'min-height' : h,
						'max-height' : h
					});
				else
					$(this).css(
					{
						'min-height' : h,
						'max-height' : h
					});
			}

		});
	}

	function Tabs()
	{

		$('.tabs').tabs();

		// $('.nav-tabs a').each(function()
		// {

		// var tab = $(this).parents('.nav-tabs');
		// var position = typeof tab.attr('data-position') !== 'undefined' ?
		// tab.attr('data-position') : 'horizontal';

		// if(position === 'vertical')
		// tab.addClass("ui-tabs-vertical
		// ui-helper-clearfix").find('li').removeClass('ui-corner-top').addClass('ui-corner-left');

		// $(this).addClass('no-request');

		// }).on('click', function(e)
		// {

		// //			e.preventDefault();
		// //
		// //			var elemento = $(this);
		// //			var url = $(this).attr('href');
		// //			var href = url.split('#');
		// //			var acao = href [ 1 ];
		// //			var title = typeof $(this).attr('title') !== undefined ?
		// // $(this).attr('title') : $(this);
		// //			var tab_id = $(this).parents('.nav-tabs').attr('id');
		// //
		// //			$('.nav-tabs a').parent().removeClass('active');
		// //			$(this).parent().addClass('active');
		// //
		// //			$('.tab-content').find('.tab-pane').attr('id', acao).addClass('active');
		// //
		// //			var len = title.split(' ').length - 1;
		// //			var titulo = title.split(' ').length > 2 ? title.split(' ') [ 0 ] + ' ' +
		// // title.split(' ') [ 1 ] : title.split(' ') [ 0 ];
		// //
		// //			$('.tab-content').find('.grid-title h4').html(titulo + ' <span
		// // class="semi-bold">' + title.split(' ') [ len ] + '</span>');
		// //
		// //			if(tab_id != 'relatorios' && tab_id != 'categorias_cardapios')
		// //			{
		// //				make_datatable(href [ 0 ],
		// //				{
		// //					'tab' : acao
		// //				});
		// //			}
		// //			else
		// //			{
		// //
		// //				var items = [ ];
		// //				var $div = null;
		// //				var data = [ ];
		// //
		// //				var id = elemento.attr('id');
		// //				var idComanda = $('input[name="comanda"]').val();
		// //
		// //				switch(tab_id)
		// //				{
		// //				case 'categorias_cardapios':
		// //					$('input[name="cardapios"]').val('');
		// //
		// //					$.ajax(
		// //					{
		// //						type : 'post',
		// //						dataType : 'json',
		// //						url : BASE_URL + 'categorias',
		// //						data :
		// //						{
		// //							categoria : id,
		// //							comanda : idComanda
		// //						},
		// //						success : function(data)
		// //						{
		// //							items.push($('#print_cardapio').html(data));
		// //							// commands();
		// //							add_item();
		// //							remover_item();
		// //						}
		// //
		// //					});
		// //
		// //					break;
		// //
		// //				case 'relatorios':
		// //
		// //					$.ajax(
		// //					{
		// //						url : href [ 0 ],
		// //						type : 'post',
		// //						dataType : 'json',
		// //						data :
		// //						{
		// //							situacao : acao,
		// //							comanda : idComanda
		// //						},
		// //						success : function(data)
		// //						{
		// //							items.push($('#print_relatorios').empty().append(data.div));
		// //
		// //
		// items.push($('#receber_valor').find('.label_total').text(data.descricao));
		// //
		// items.push($('#receber_valor').find('.total_pagar').text(parseFloat(data.total_geral).formatMoney(2,
		// // '', '.', ',')));
		// //
		// //							// commands();
		// //						}
		// //
		// //					});
		// //					break;
		// //				}
		// //
		// //			}
		// //
		// //			countCheckState('none');
		// //			preloader('out');
		// //
		// });

	}

	function load_cep()
	{

		$('input[name="cep"]').blur(function()
		{
			console.log("Buscando endereço...");
			var cep = $(this).val();
			var url = MAIN + 'ajax/cep';

			$.ajax(
			{
				type : "post",
				url : url,
				dataType : "json",
				data :
				{
					cep : cep
				},
				success : function(data)
				{

					if(data.type == 'success')
					{
						$.each(data.fields, function(ind, val)
						{
							$("input[name=" + ind + "]").val(val);
						});
					}
					else
					{
						notificacao(data);
						$.each(data.fields, function(ind, val)
						{
							$("input[name=" + ind + "]").val('');
						});
					}
				}

			});
		});
	}


	this.tabs = Tabs();
	this.load_cep = load_cep();

}

function autoload()
{

	load_plugins();
	load_forms();
	load_functions();

	load_datatable();

	fixar_menu();
	modal_edit();
	init_autocomplete();

	var init = new Init();

	Dashboard.init();

	var menu = new Menu();
	menu.openMenu();

	// Importação de scripts específicos de cada projeto
	require([BASEPATH + 'js/apps.js'], function()
	{
		Apps.autoInit();
	});

	preloader('in');

	jQuery('.social-icon').find(':input:checkbox').on('click', function()
	{
		if(jQuery(this).is(':checked'))
		{
			jQuery(this).parent('label').addClass('waves-red');
			jQuery(this).parent('label').find('i.material-icons').addClass('red-text').text('favorite');
		}
		else
		{
			jQuery(this).parent('label').removeClass('waves-red');
			jQuery(this).parent('label').find('i.material-icons').removeClass('red-text').text('favorite_border');
		}
	});

	$('.slider').slider(
	{
		full_width : false,
		indicators : false
	});

}

// Plugin initialization

function load_plugins()
{
	var plugins = new Plugins;

	plugins.iCheckbox('input:checkbox.icheckbox,input:radio.icheckbox');
	plugins.Scrollbar();
	plugins.Switch();
	plugins.Tooltip();
	plugins.Redactor();

	update_status();

	// Carrega a função para inicializar as ações do checkbox
	countChecked();

	$("select").formSelect();
	// $('[data-searchable="false"]').formSelect();

	var plugins = new Plugins();
	plugins.Select2('[data-searchable="true"]');

	if($('body').find('textarea').length > 0)
		M.textareaAutoResize($('textarea'));

	/*
	* Masonry container for Gallery page
	*/

	// var originalWidth = $(this).width();
	// var originalHeight = origin.height();

	//popup-gallery
	$('.materialbox').materialbox(
	{
		'inDuration' : 250,
		'outDuration' : 200,
		'originalWidth' : 1,
		'originalHeight' : 1
	});
	$(".popup-gallery").magnificPopup(
	{
		delegate : "a",
		type : "image",
		closeOnContentClick : true,
		fixedContentPos : true,
		tLoading : "Carregando imagem #%curr%...",
		mainClass : "mfp-img-mobile mfp-no-margins mfp-with-zoom",
		gallery :
		{
			enabled : true,
			navigateByImgClick : true,
			preload : [0, 1] // Will preload 0 - before current, and 1 after the current
			// image
		},
		image :
		{
			verticalFit : true,
			tError : "<a href=\"%url%\">A imagem #%curr%</a> não pôde ser carregada.",
			titleSrc : function(item)
			{
				console.log(item.el);
				return item.el.attr("title") + "<small>" + $(item.el).find('img').attr('alt') + "</small>";
			},
			zoom :
			{
				enabled : true,
				duration : 300 // don\'t foget to change the duration also in CSS
			}
		}
	});

}

function load_forms()
{

	var forms = new Forms();
	forms.Submit();

	var inputs = [
	// 'input[type="button"]',
	// 'input[type="checkbox"]',
	'input[type="color"]', 'input[type="date"]', 'input[type="datetime-local"]', 'input[type="email"]',
	// 'input[type="file"]',
	// 'input[type="hidden"]',
	// 'input[type="image"]',
	'input[type="month"]', 'input[type="number"]', 'input[type="password"]',
	// 'input[type="radio"]',
	'input[type="range"]',
	// 'input[type="reset"]',
	'input[type="search"]',
	// 'input[type="submit"]',
	'input[type="tel"]', 'input[type="text"]', 'input[type="time"]', 'input[type="url"]', 'input[type="week"]', 'textarea'];

	var items = inputs.toString();

	$('form').find(items).blur(function()
	{
		if($(this).val() == '')
		{
			$(this).parent().find('label').removeClass('active');
		}
		else
		{
			$(this).parent().find('label').addClass('active');
		}
	});

}

function dropzone()
{
	require([BASEPATH + 'plugins/dropzone/dropzone.js'], function()
	{
		$('.dropzone').each(function()
		{

			var id = $(this).attr('id');
			var action = $(this).attr('action');
			var name = $(this).find(':input:file').attr('name');

			var meuDropzone = new Dropzone(this,
			{
				paramName : name,
				url : action,
				success : function(a, b)
				{
					var data = JSON.parse(b);
					if(data.type === 'error')
					{
						notificacao(data);
					}
					else
					{
					}
				}

			});
		});
	});
}

function load_functions()
{

	App.aplicarMascaras();

	load_file();
	stars();

	if( typeof banner_editor === 'function')
		var banner = new banner_editor();

	$('[data-role="addselect"]').keydown(function(e)
	{

		if(e.keyCode == 13 && $(this).val() !== '')
		{

			e.preventDefault();

			var option = new Option($(this).val(), $(this).val(), true, true);
			$(this).parent().next().find('.select2-offscreen').append(option).trigger('change');

			$(this).val('');

		}

	});

}

function relogio()
{
	function relogio()
	{
		$('#relogio').html(moment().format('LLLL'));
	}

	relogio();

	setInterval(function()
	{
		relogio();
	}, 1000);
}