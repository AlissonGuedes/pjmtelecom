'use strict';

var navbar,
	btn_menu,
	hide_on_modal,
	modal,
	form,
	$form,
	Form;

var Dashboard =
{

	init: function () {

		navbar = $('.page-topbar').find('.navbar-main');
		btn_menu = $('body').find('a[data-target="slide-out"]');
		hide_on_modal = $('.hide-on-display-modal');
		modal = $('body').find('.modal').attr('id');

		this.searchs();
		this.modal_close();
		// 	$('button[data-toggle="modal"],table[data-toggle="modal"] tr td').on('click', function(e)
		// 	{
		// 		console.log(e.currentTarget.dataset);
		// 		var title = e.currentTarget.dataset.title || e.currentTarget.dataset.tooltip;
		// 		alert(title);
		// 			if(e.currentTarget.dataset.target !== 'modal-location')
		// 		Dashboard.modal_form.open(title);
		// 	});

	},

	searchs: function () {

		var filter = $('.dataTables_filter');
		var search = filter.find('input[type="search"]');
		var input = $('.header-search-input,.search-box-sm');
		var placeholder = search.attr('placeholder');

		if (filter.length > 0) {

			input.attr('placeholder', placeholder).on('keyup', function () {
				search.val(this.value).keyup();
			});

			$('.search-sm-close').on('click', function () {
				input.val('');
				search.val('').keyup();
			});

			search.on('keyup', function () {
				input.val(this.value);
			});

			filter.parent().parent().parent().parent().hide();

		}
		else {

			input.on('keyup', function () {

				$.ajax(
					{
						'url': BASE_URL + 'search',
						'type': 'post',
						'dataType': 'html',
						'data':
						{
							q: $(this).val()
						},
						success: function (data) {
							$('#main').hide();
							$('#content').remove('#results').append($('<div id="results"/>').html('Nada encontrado'));
						}

					});

			});

		}

	},

	modal_form:
	{
		open: function (title) {

			// Remover todas as mensagens de alertas
			$('#toast-container').find('.toast').remove();

			// limpar o campo de pesquisa se estiver aberto (devices)
			$('.search-sm').hide().find('.search-sm-close').click();

			// ocultar todos os elementos do cabeçalho
			animate(hide_on_modal, 'fadeOut fast', function (e) {
				e.hide();
			});

			/*** Título da página ***/

			animate(navbar.find('.page-title').html($('<h2/>').html(title)), 'fadeIn fast delay-200ms', function (e) {
				e.show();
			});

			/*** Botão submit ***/
			$('.submit_form').removeClass('display-none');

			animate($('.submit_form'), 'fadeIn fast delay-200ms', function (e) {
				Form.Blocked(e.find('button[type="submit"]'), 'save', false);
			});

			/*** Botão fechar ***/

			// animar botão de fechar a modal
			btn_menu.find('i').text('close');
			btn_menu.removeClass('hide-on-large-only').addClass('btn-modal-close');
			animate(btn_menu, 'rotateIn slow', function (e) {
				e.removeAttr('data-target');
				e.removeClass('sidenav-trigger sidebar-collapse');
			});

		},

		close: function (self) {

			/*** Título da página ***/
			animate(navbar.find('.page-title'), 'fadeOut fast', function (e) {
				e.empty();
			});

			/*** Botão submit ***/
			animate($('.submit_form'), 'fadeOut fast', function (e) {
				e.addClass('display-none');
			});

			/*** Botão fechar ***/

			// Animar botão fechar e voltar exibir o menu
			animate(self, 'rotateOut slow', function (e) {
				e.attr('data-target', 'slide-out').find('i').text('menu');
				e.addClass('sidenav-trigger sidebar-collapse hide-on-large-only').removeClass('btn-modal-close');
				animate(e, 'rotateIn faster');
			});

			// Remover modal
			$('button[type="reset"].modal-close').click();

			/*** Re-Exibir todos os elementos do cabeçalho. ***/
			animate(hide_on_modal.show(), 'fadeIn fast delay-200ms', function (e) {
				e.show();
			});

		},

	},

	modal_close: function () {

		var result;

		form = $('#' + modal).find('form').attr('id');
		$form = '#' + form;

		Form = new Forms();

		$('nav .submit_form').find('button[type="submit"]').on('click', function () {
			var $form = $('#' + modal).find('form').attr('id');

			Form.formSubmit($('#' + $form));

			setTimeout(function () {
				result = Form.getResult();
				if (typeof result !== 'undefined' && result.type === 'success')
					Dashboard.modal_form.close(btn_menu);
				console.log('--- result - file: app.js ---');
				console.log(result);
			}, 500);


		});

		btn_menu.on('click', function (e) {

			e.preventDefault();

			var self = $(this);

			if (self.hasClass('sidenav-trigger'))
				return;

			swal(
				{

					title: 'Você tem certeza?',
					text: 'As alterações não foram salvas.',
					dangerMode: true,
					closeOnClickOutside: true,
					closeOnEsc: true,
					buttons:
					{
						cancel:
						{
							text: 'Cancelar',
							value: false,
							visible: true,
						},
						delete:
						{
							text: 'Excluir',
							value: true,
							visible: true,
						}
					}

				}).then(function (excluir) {

					if (excluir)
						Dashboard.modal_form.close(self);

				});

		});

	}

};

