/**
 * Construção da classe Forms() Esta classe configura os eventos dos botões
 */
function Forms()
{

	var elements = ['.da-files', '.toggle', 'input', 'textarea', 'password', 'select', '.select-wrapper.disabled'];

	var submit = true;
	var result;

	this.getResult = getResult;
	$('form').find(':button:submit').each(function()
	{

		if($(this).hasClass('btn-excluir'))
			submit = false;

		if( !submit)
			elements.push(':button:submit');

	});

	var total_preenchido = 0;
	var total_inputs 	 = 0;

	var items = elements.toString();

	// var plugins = new Plugins();
	// var buttons = new Buttons();

	/**
	 * Este método adiciona um evento para atualizar uma DIV ou parte de uma
	 * página Deve ser adicionado a elementos do tipo {link - <a href="(...)"></a>}
	 * para que possa ser possível obter a URL para onde ir
	 */
	function __submit(form)
	{

		var form = typeof form !== 'undefined' ? form : 'form';

		jQuery(form).submit(function(e)
		{

			if($(this).hasClass('no-request'))
				return;

			e.preventDefault();

			// Antes de enviar o formulário, chamar a função para atualizar o
			// plugin de editor de textos
			// if(document.getElementById('editor') !== null)
			// {
			postForm();
			// }

			var form = $(this);

			__formSubmit(form);

		});

	}

	function __formSubmit(form, params, disable_btn)
	{

		const action = form.attr('action') || undefined;
		const method = form.attr('method') || undefined;
		const button = form.find('input:submit,button:submit');

		var icone = button.find('i').text();
		var data = typeof params !== 'undefined' ? params : null;
		var blockButton = typeof disable_btn !== 'undefined' ? disable_btn : true;

		if( typeof action !== 'undefined')
		{
			form.ajaxSubmit(
			{

				url : action,
				type : method,
				dataType : 'json',
				data : data,

				uploadProgress : function(event, position, total, progress)
				{

					console.log('Tamanho total do arquivo: ' + total);
					console.log('Progresso: ' + progress);

				},
				beforeSend : function()
				{

					// Antes de inserir o atributo disabled="disabled",
					// Verificar se, por padrão, já existe o :input:disabled.
					// Se existir, adicionar ao parent(.input-field) a classe disabled
					// Para não remover o atributo deste input
					form.find(items).each(function()
					{
						if($(this).is(':disabled'))
							$(this).parents('.input-field').addClass('disabled');
					});

					if(blockButton)
						__blocked(button, icone, true);

				},
				success : function(data)
				{

					result = data;
					notificacao(data, form);

					if(data.type !== 'error')
						execute_data(data);

					__blocked(button, icone, false);

					icone = '';

					if(data.action === 'excluir')
					{
						button.attr('disabled', true);
						__remover(data);
					}

					return result;

				},
				error : function(request, status, error)
				{

					swal('Não foi possível concluir a requisição',
					{
						icon : 'error',
					});

					notificacao();

					if(blockButton)
						__blocked(button, icone, false);
				}

			});

			setTimeout(function()
			{
				setResult(result);
			}, 300);

		} // endif

	}

	function setResult(_result)
	{
		result = _result;
	}

	function getResult()
	{
    	return result;
	}

	/**
	 * Método para remoção de registros no banco de dados.
	 *
	 * @param {data} =
	 *            array
	 * @return {removido} = true|false
	 */
	function __remover(data)
	{

		Pace.start();
		var title = data.title;
		var type = data.type;
		var text = data.msg;
		var icon = data.type;
		var url = data.url;
		var fields = data.fields;
		var action = data.action;

		// swal(
		// {
		// title		: title,
		// text		: text,
		// icon		: type,
		// buttons		: ['Cancelar', 'Excluir'],
		// dangerMode	: true,
		// catch		:
		// {
		// fields	: fields,
		// action	: action,
		// url		: url
		// },
		// }).then((willDelete) =>
		// {
		//
		// if(willDelete)
		// {
		$.ajax(
		{
			method : 'post',
			dataType : 'json',
			url : url,
			data :
			{
				fields : fields,
				excluir : true
			},
			success : function(data)
			{

				notificacao(data);
				Pace.stop();

				countCheckState('none');

			},

			error : function(request, status, error)
			{
				swal('Houve um erro ao tentar prosseguir: Um ou mais registros não puderam ser excluídos.',
				{
					icon : 'error',
				});
				countCheckState('none');
			}

		});

		// }
		// else
		// {
		// countCheckState('none');
		// }
		//
		// });

	}

	/**
	 * Método para bloqueio e desbloqueio de elementos do formulário.
	 *
	 * @param {Object}
	 *            elem = nome do elemento
	 * @param {Object}
	 *            boo = true|false
	 */
	function __blocked(elem, icon, boo)
	{

		var submit = elem.closest('form').find(':button:submit').hasClass('btn-excluir');
		var faClass = elem.find('.fa').hasClass('fa-2x') ? 'fa-2x' : '';
		var spinner = '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>';

		if(boo)
		{
			elem.attr('disabled', boo).find('i.material-icons').html(spinner);
	        $('.submit_form').find('button[type="submit"]').attr('disabled', true).find('i.material-icons').html(spinner);
		}
		else
		{
			if( !submit)
			{
				elem.find('i.material-icons').html(icon);
                $('.submit_form').find('button[type="submit"]').attr('disabled', boo).find('i.material-icons').html('save');
			}
			else
			{
			    elem.find('i.material-icons').html('delete');
            }
		}

		elem.closest('form').find(items).each(function()
		{
			if( !$(this).parents('.input-field,.select-wrapper').hasClass('disabled'))
			{
				elem.closest('form').find(this).attr('disabled', boo);
			}
		});

		if( !submit)
			elem.attr('disabled', boo);

    }

	/**
	 *Método para resetar o formulário sempre que houver necessidade
	 *
	 * @param {Object}
	 * 			  form = formulário a ser resetado
	 *
	 */
	function __reset(form)
	{

		notificacao();

		var inputs = ['input[type="button"]', 'input[type="checkbox"]', 'input[type="color"]', 'input[type="date"]', 'input[type="datetime-local"]', 'input[type="email"]', 'input[type="file"]',
		// 'input[type="hidden"]',
		'input[type="image"]', 'input[type="month"]', 'input[type="number"]', 'input[type="password"]', 'input[type="radio"]', 'input[type="range"]',
		// 'input[type="reset"]',
		'input[type="search"]',
		// 'input[type="submit"]',
		'input[type="tel"]', 'input[type="text"]', 'input[type="time"]', 'input[type="url"]', 'input[type="week"]', 'textarea'];

		var items = inputs.toString();

		$(form).find(items).val('');
		// $(form).find('select').select2('destroy');

		var $select = new Plugins();

		// $select.Select2('select');

		// $('.select2').each(function()
		// {
		// $(this).css('width', '348px');
		// });

		$(form).find('input,textarea,select').removeClass('success-control').removeClass('error-control');
		$(form).find('div.da-files.imagem').find('img').attr('src', '');

		var span = $(form).find('div.da-files.arquivo').find('span.placeholder');
		span.text(span.attr('placeholder') || 'Selecionar arquivo');
		//.Files('resetElement');
		//		$('[data-toggle="da-files"]').Files('reset');

		return false;

	}


	this.Submit = __submit;
	this.Blocked = __blocked;
	this.formSubmit = __formSubmit;
	this.reset = __reset;

}( function(window, document, undefined)
	{( function(factory)
			{

				'use strict';

				if( typeof define === 'function' && define.amd)
					define(['jquery'], factory);
				else if(jQuery && !jQuery.fn.files)
					factory(jQuery);

			}(function($)
			{

				'use strict';

				var Files = function(oInit)
				{

					var DEFAULTS =
					{
						value : null,
						placeholder : 'Selecionar arquivo',
						disabled : false,
						title : null
					}

					const self = $(this);
					const $name = self.attr('name');
					const placeholder = self.attr('placeholder') || DEFAULTS.placeholder;

					var $class = self.attr('class');
					var disabled = self.attr('disabled') || DEFAULTS.disabled;
					var title = self.attr('title') || DEFAULTS.title;

					var _resetElement = function()
					{
						if($('div.da-files').length > 0)
						{

							$('div.da-files').remove();

							if(self.hasClass('imagem'))
							{
								self.attr('placeholder', 'fa fa-image');
								self.parent().find('img').attr('src', '');
							}

							if(self.hasClass('arquivo'))
							{
								self.attr('placeholder', placeholder);
							}
						}

					};

					_resetElement();

					var _createElement = function()
					{

						const div = '<div/>';

						if(self.hasClass('imagem'))
							var $input = '<span class="placeholder ' + placeholder + '"> </span><img src="" alt="">';
						else if(self.hasClass('arquivo'))
							var $input = '<span class="placeholder muted" placeholder="' + placeholder + '">' + placeholder + '</span>';

						self.parent().append($(div).attr('disabled', disabled).addClass($class).click(function()
						{
							self.click();
						}).append($input));

						self.on('change', function()
						{
							if(self.hasClass('imagem'))
								_changeImage(true);
							else if(self.hasClass('arquivo'))
								_changeFile(true);
						});

					};

					var _changeFile = function(txt)
					{

						if( !txt && self.attr('data-value') == '')
							return false;

						var texto = !txt ? self.attr('data-value') : self.val();

						self.parent().find('span').removeClass('muted').empty().text(texto);

					};

					var _changeImage = function(src)
					{

						if( !src && self.attr('data-value') == '')
							return false;

						// _exibir_imagem();
						const id = self.attr('id');
						const img = document.getElementById(id).files[0];
						var src = self.attr('data-value') != '' ? self.attr('data-value') : window.URL.createObjectURL(img);

						// Ocultar a imagem e remover
						self.attr('title', title).parent().find('img').animate(
						{
							'opacity' : '0'
						},
						{
							'duration' : 0
						}).parent().find('span').removeClass('fa-image').addClass('fa-spinner').toggleClass('fa-spin')
						// Adicionar nova imagem e re-exibir
						.parents('.da-files.imagem').find('img').attr('src', src).animate(
						{
							'opacity' : '1',
						},
						{
							duration : 0
						});

						self.attr('data-value', '');

						setTimeout(function()
						{
							self.parent().find('span').addClass('fa-image').removeClass('fa-spinner fa-spin');
						}, 100);

					};

					this.createElement = _createElement();

					if($('body').find('.da-files.arquivo').length > 0)
						this.changeFile = _changeFile(false);

					if($('body').find('.da-files.imagem').length > 0)
						this.changeImage = _changeImage(false);

				};

				// jQuery aliases
				$.fn.Files = Files;

			}));

	}(window, document));

function dropzone()
{

	var id = 0;

	$('.dropzone').find('input[type="file"]').on('change', function()
	{

		var self = $(this);
		var view = $('.dropzone_viewer');
		var list = self.context.files;
		var value = self.val();
		var len = self.context.files.length;

		for ( var i = 0; i < len; i ++ )
		{ ++id;
			var img = list[i];
			var src = window.URL.createObjectURL(img);

			// $('#foto' + id ).find(':file[id="foto_' + id + '"]').val()
			// view.find('.placeholder').remove();

			// view.append
			// (
			// $('<div class="fotos" id="foto' + id + '">'
			// ).append(
			// $(':file').attr('id', 'foto_' + id)
			// .attr('name', 'imagem[]')
			// .attr('value', value)
			// .css('display', 'none')
			// ).append(
			// $('img')
			// .attr('src', src)
			// .css('height', '100%')
			// )
			// );

			// view.find('#foto' + id).append($('img').attr('src', src).css('height',
			// '100%'));

		}

	});
}