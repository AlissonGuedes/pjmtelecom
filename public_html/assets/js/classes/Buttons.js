/**
 * Construção da classe Buttons()
 * Esta classe configura os eventos dos botões
 */

function Buttons()
{

	var plugins = new Plugins();

	var params = [];

	/**
	 * Este método adiciona um evento para atualizar uma DIV ou parte de uma página
	 * Deve ser adicionado a elementos do tipo {link - <a href="(...)"></a>} para que
	 * possa ser possível obter a URL para onde ir
	 */
	function __reload()
	{

		$('.reload,[data-toggle="modal"]').click(function(e)
		{

			e.preventDefault();

			var link = $(this).attr('href');
			var url = typeof link != 'undefined' && link[0] == '#' ? $(this).attr('data-url') || null : link;
			var elements = '.tiles,.grid,.modal';
			var el = $(this).parents(elements);

			if (url !== null)
			{

				blockUI(el);

				$.ajax(
				{
					type : 'post',
					url : url,
					dataType : 'json',
					success : function(data)
					{

						execute_data(data);
						animation_progress();
						plugins.Switch();

						unblockUI(el);

					},

					error : function()
					{

						unblockUI(el);

					}

				});

			}

		});

	}

	/**
	 * Alterna a exibição entre os campos de {CPF} e {CNPJ} na página de clientes
	 */
	function __tipo_cadastro()
	{

		jQuery(':checkbox[name="tipo"][data-toggle="toggle"]').change(function(e)
		{
			var checked = $(this).prop('checked');

			var cpf = 'cpf_cnpj';
			var rg_ie = $('rg_ie');

			if (checked)
			{
				$('#tipo_cpf').hide().children().each(function()
				{
					$(this).find('input[type="text"]').val('').attr('disabled', true);
				});

				$('#tipo_cnpj').show().children().each(function()
				{
					$(this).find('input[type="text"]').val('').attr('disabled', false);
				});

				$('[for="razao_social"]').text('Razão Social:');
				$('[for="nome_fantasia"]').text('Nome Fantasia:');

				$('input[name="razao_social"]').attr('placeholder', 'RAZÃO SOCIAL');
				$('input[name="nome_fantasia"]').attr('placeholder', 'NOME FANTASIA');

			}
			else
			{
				$('#tipo_cpf').show().children().each(function()
				{
					$(this).find('input[type="text"]').val('').attr('disabled', false);
				});

				$('#tipo_cnpj').hide().children().each(function()
				{
					$(this).find('input[type="text"]').val('').attr('disabled', true);
				});

				$('[for="razao_social"]').text('Nome:');
				$('[for="nome_fantasia"]').text('Nome Social:');

				$('input[name="razao_social"]').attr('placeholder', 'NOME COMPLETO');
				$('input[name="nome_fantasia"]').attr('placeholder', 'PESSOA TRANSEXUAL OU TRAVESTI');

			}

		});

	}

	

	this.Reload = __reload;
	this.TipoCad = __tipo_cadastro;


}
