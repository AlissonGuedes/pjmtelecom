Apps =
{

	autoInit : function()
	{

		this.select();
		this.closeStart();
		this.comunicado();
		this.texto_apresentacao();
		this.checkAll();
		this.informar_gestor();

		$('.window--table').find('tbody tr').find('button').each(function()
		{

			this.delete_item($(this).attr('id'));

			// Adiciona o ID da cidade ao índice do array "c"
			this.add_cidade($(this).parents('table').attr('id').split('_')[1], $(this).attr('id'));

		});

	},
	
	// -----------------------------------------------------------------------
	// Start Informar Gestor
    informar_gestor : function()
    {
        
        $('#informar_gestor').on('change', function()
        {

            if ( $(this).is(':checked') )
                $('select#gestor').val('').prop('disabled', false).formSelect();
            else
                $('select#gestor').val('').prop('disabled', true).formSelect();

        });

        $('select#gestor').on('change', function(){
           $(this).parent().parent().find('.error').remove(); 
        });
        
    },
    // End Informar Gestor
	// -----------------------------------------------------------------------

	// -----------------------------------------------------------------------
	// Del Button
	delete_item : function(id)
	{

		$('button#' + id).on('click', function()
		{

			var self = $(this);

			if(self.parents('.window--table').find('tbody tr').length > 1)
			{
				$('tr#bairro_' + id).remove();
			}
			else
			{
				self.parents('.window--table').remove();
			}

		});

	},
	// End Del Button
	// -----------------------------------------------------------------------

	checkAll : function()
	{

		/**
		 * Função para selecionar e desselecionar todos os checkboxes de uma tabela para
		 * excluir/alterar.
		 */
		var states = function(cidade)
		{

			var checkAll = 'selectAllBairros_' + cidade;

			// Variável para contar total de checkboxes
			var countChk = $('#cidade_' + cidade).find('tbody').find(':input:checkbox').length;

			// Variável para contar quantos checkbox existem selecionados
			var checkeds = $('#cidade_' + cidade).find('tbody').find(':input:checkbox:checked').length;

			var indeterminateCheckbox = document.getElementById(checkAll);

			if(checkeds > 0)
			{

				if(checkeds < countChk)
				{
					indeterminateCheckbox.indeterminate = true;
				}
				else if(checkeds === countChk)
				{
					indeterminateCheckbox.indeterminate = false;
				}

				$('#cidade_' + cidade).find('thead').find('#' + checkAll).prop('checked', true);

			}
			else
			{

				$('#cidade_' + cidade).find(':input:checkbox:checked').prop('checked', false).parents('tr').removeClass('selected');

				if( typeof indeterminateCheckbox !== 'undefined' && indeterminateCheckbox !== null)
					indeterminateCheckbox.indeterminate = false;
			}

		};

		$('#tab_cidade').find('table').each(function()
		{

			var selectAll = $(this).find('thead').find(':input:checkbox');
			var checkbox = $(this).find('tbody').find(':input:checkbox');

			// Adicionar o atributo 'checked' no checkbox
			checkbox.on('change', function()
			{

				// Adicionar a classe selected na tabela para grifar linha selecionada.
				$(this).prop('checked');

				if($(this).is(':checked'))
					$(this).parents('tr').addClass('selected');
				else
					$(this).parents('tr').removeClass('selected');

				states($(this).val().split(':')[0]);

			});

			// Se o usuário quiser selecionar todos...
			selectAll.on('change', function()
			{

				if(selectAll.is(':checked'))
					$(this).parents('table').find('tbody tr').addClass('selected').find(':input:checkbox').prop('checked', true);
				else
					$(this).parents('table').find('tbody tr').removeClass('selected').find(':input:checkbox:checked').removeClass('selected').prop('checked', false);

				states($(this).attr('id').split('_')[1]);

			});

		});

	},

	select : function()
	{

		var $cidades = $('select#select_cidades');
		var $bairros = $('select#select_bairros');

		var id_cidade;
		var id_bairro;

		var url;
		var placeholder;

		var cidade;
		var bairro;

		var add_button = $('#add_bairro');

		var input_bairros = $('input[name="bairros[]"]');

		// Informar cidade e bairro do cliente ao entrar pela primeira vez no site
		$cidades.on('select2:select, change', function(e)
		{

			$this = $(this);
			id_cidade = $this.val();
			url = typeof $(this).data('url') !== 'undefined' && $(this).data('url') != '' ? $(this).data('url') : BASE_URL + 'bairros';
			placeholder = null;
			$bairros.attr('placeholder');

			if(id_cidade)
			{

				$.ajax(
				{
					url : url,
					type : 'post',
					dataType : 'json',
					data :
					{
						cidade : id_cidade,
					},
					success : function(data)
					{

						$('select#select_bairros').find('option').remove();

						if(data.type !== 'error')
						{

							$bairros.attr('disabled', false).parent('.select-wrapper')
							/*	  */.removeClass('disabled').val('').attr('data-cidade', id_cidade);

							for ( var i in data )
							{

								if($cidades.data('searchable'))
								{

									var option = new Option(data[i].text, data[i].id, true, true);
									$('select#select_bairros').append(option).val('');

								}
								else
								{

									$('select#select_bairros').append($('<option/>',
									{
										'value' : data[i].id,
										'text' : data[i].text
									})).val('');

									var options =
									{
										'placeholder' : placeholder
									};

									var select = document.querySelector('select#select_bairros');
									var instances = M.FormSelect.init(select, options);
									var instance = M.FormSelect.getInstance(select);

								}

							}

							$('select#select_bairros').val(data.bairro_selected);

						}
						else
						{
							$('select#select_bairros').val('').attr('disabled', true).parent('.select-wrapper').addClass('disabled').attr('data-cidade', id_cidade);
						}

					},

				});

			}
			else
			{
				$('select#select_bairros').attr('disabled', true).parent('.select-wrapper').addClass('disabled').val('').attr('data-cidade', id_cidade);
			}

		});

		load_plugins();

		// -----------------------------------------------------------------------
		// Inserir informações na tabela html
		$bairros.on('select2:select, change', function()
		{

			id_bairro = $bairros.val();

			cidade = $('#select2-select_cidades-container').text();
			bairro = $('#select2-select_bairros-container').text();

			add_button.attr('disabled', false);

		});
		// -----------------------------------------------------------------------

		// -----------------------------------------------------------------------
		// Add button
		add_button.on('click', function()
		{

			var acao = 'add';

			var table;
			var thead;
			var tbody;
			var linha;
			var div = $('div.window--child');

			var del_button = $('<button/>').attr('type', 'button')
			/*						 */.attr('id', id_bairro)
			// /*						 */.tooltip('Remover Bairro ' + bairro)
			/*						 */.addClass('btn red btn-mini waves-effect waves-light')
			/*						 */.append($('<i/>').addClass('material-icons').text('delete'));

			if(div.find('table#cidade_' + id_cidade).length === 0)
			{

				table = $('<table/>')
				/*		*/.attr('id', 'cidade_' + id_cidade)
				/*		*/.addClass('table striped bordered highlight dataTable window--table');

				// head
				linha = $('<tr/>').append($('<th/>').attr('colspan', 2).append('Cidade: ' + cidade));
				thead = $('<thead/>').append(linha);

				// body
				linha = $('<tr/>').attr('id', 'bairro_' + id_bairro).append($('<td/>').append(bairro))
				/*				*/.append($('<td/>').addClass('right-align').append(del_button)
				/*				*/.append($('<input/>').attr('type', 'hidden').attr('name', 'bairros[]').val(id_cidade + ':' + id_bairro)));
				tbody = $('<tbody/>').append(linha);

				div.append($(table).append(thead).append(tbody));

			}
			else
			{

				if(div.find('table#cidade_' + id_cidade).find('tbody').find('tr#bairro_' + id_bairro).length === 0)
				{

					// body
					linha = $('<tr/>').attr('id', 'bairro_' + id_bairro).append($('<td/>').append(bairro))
					/*				*/.append($('<td/>').addClass('right-align').append(del_button)
					/*				*/.append($('<input/>').attr('type', 'hidden').attr('name', 'bairros[]').val(id_cidade + ':' + id_bairro)));
					div.find('table#cidade_' + id_cidade).find('tbody').append($(linha));
				}

			}

			// -----------------------------------------------------------------------
			// Del Button

			Apps.delete_item(id_bairro);

			// End Del Button
			// -----------------------------------------------------------------------

			if($('select#select_bairros').find('option').length === 1)
			{
				$('select#select_cidades').val('').trigger('change');
			}

			$('select#select_bairros').val('').trigger('change');
			add_button.attr('disabled', true);

		});
		// End Add Button
		// -----------------------------------------------------------------------

	},

	closeStart : function()
	{
		// Preencher o select da modal de alterar a localização com os dados do COOKIE,
		// caso o usuário cancele ou feche a modal sem submter os dados
		$('#modal-location').modal(
		{
			onCloseStart : function()
			{

				$.ajax(
				{
					type : 'post',
					dataType : 'json',
					url : BASE_URL + 'cidades',
					data :
					{
						'acao' : 'cookies'
					},
					success : function(data)
					{
						var cidade = data.cidade;
						var bairro = data.bairro;

						$('select#select_cidades').val(cidade).trigger('change');

						$.ajax(
						{
							url : BASE_URL + 'bairros',
							type : 'post',
							dataType : 'json',
							data :
							{
								cidade : cidade,
							},
							success : function(data)
							{
								$('select#select_bairros').find('option').remove();
								for ( var i in data )
								{
									var option = new Option(data[i].text, data[i].id, true, true);
									$('select#select_bairros').append(option).val(bairro).trigger('change');
								}
							},

						});

					}

				});
			}

		});
	},

	comunicado : function()
	{
		// Abrir modal para comunicados
		var $URL = null;
		$('a[href="#modal-comunicado"').on('click', function()
		{

			$URL = $(this).data('url');

			$('#modal-comunicado').modal(
			{
				'onCloseStart' : function(data)
				{
					var loading = $(data).find('.loading');
					var content = $(data).find('.content');
					content.fadeOut();
					loading.fadeIn();
				},

				'onOpenStart' : function(data)
				{
					var t = $(data).find('.modal-header h6');
					var h = $(data).find('h4');
					var p = $(data).find('p');
					t.html('');
					h.html('');
					p.html('');
					$.ajax(
					{
						'type' : 'post',
						'dataType' : 'json',
						'url' : $URL,
						'success' : function(data)
						{
							t.html(data.content.modulo);
							h.html(data.content.titulo);
							p.html(data.content.texto);
						}

					});
				},

				'onOpenEnd' : function(data)
				{
					var loading = $(data).find('.loading');
					var content = $(data).find('.content');
					content.fadeIn();
					loading.fadeOut();
				}

			});

		});

	},

	/**
	 * Evento do textarea na página Configurações
	 */
	texto_apresentacao : function()
	{

		$('textarea[name="texto_apresentacao"]').on('focus', function(e)
		{

			var modal = $('#modal-quemsomos');

			$('#modal-quemsomos').modal(
			{

				dismissible : false,
				inDuration : 150,
				outDuration : 200,
				onOpenEnd : function(el)
				{

					modal.find('textarea').focus()

					// require(['https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js'], function(){
					//     modal.find('textarea').editor(
					//     {
					//         height : 400,
					//         // uiLibrary: 'materialdesign'
					//     });
					// });

				},
				onCloseStart : function(el)
				{

					var textarea = $('#' + $(this).attr('id')).find('textarea');
					var texto = $(textarea).val();
					if(texto !== '')
						$('#texto_apresentacao').val(texto).find('~ label[for="texto_apresentacao"]').addClass('active');
					else
						$('#texto_apresentacao').val('').find('~label[for="texto_apresentacao"]').removeClass('active');

				}

			});

			modal.modal('open');

		});
	}

};
