/**
 * Classe URI
 * Analisa URLs e determina o tipo de requisição: Ajax ou HTTP
 */
function URI()
{

	/**
	 * __supports_history();
	 * Método Construtor da classe
	 */
	function __supports_history()
	{
		return ! !(window.history && history.pushState);
	}

	/**
	 * __request();
	 * Método para fazer requisições de páginas
	 */
	function __request(url, scroll_top)
	{

		var req;
		var duration = 0;
		var request = Boolean;
		var selector = 'content';
		var body = document.querySelector(selector);

		var to_top = typeof scroll_top !== 'undefined' ? scroll_top : true;
		var html = null;
		var title = null;

		if(window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else
			req = new ActiveXObject('Microsoft.XMLHTTP');

		req.open('GET', url);

		// Iniciar progresso do envio
		req.onloadstart = function(e)
		{
			
		};

		// Progresso do envio
		req.onprogress = function(e)
		{
			// Código...
			preloader('out');
		};

		// Finalizar progresso de envio
		req.onloadend = function(e)
		{
			preloader('in');
		};

		// Exibir o retorno da página ao finalizar
		req.onload = function(e)
		{

			if(req.readyState === 4)
			{

				var parser = new DOMParser();
				var content = parser.parseFromString(req.response, 'text/html');

				if(selector === 'main' || selector === 'content')
				{
					body = document.getElementById(selector);
					html = content.querySelector('#' + selector);
				}

				title = content.querySelector('title');

				if(title !== null)
				{
					// atualizamos o título da página
					document.title = title.innerHTML;
				}

				setTimeout(function()
				{

					if(html !== null && html.innerHTML !== null)
						body.innerHTML = html.innerHTML;
					else
					{
						var ajax = listErrors(JSON.parse(req.response));
						body.innerHTML = ajax;
					}

					jQuery(body);
					autoload();

				}, duration);

				if(req.status !== 200)
				{

					// swal(
					// {
					// icon : 'error',
					// title : req.status,
					// text : req.statusText,
					// });

				}

				request = true;

			}
			else
			{
				swal(
				{
					icon : 'error',
					title : req.status + ' - ' + req.statusText,
					text : req.response,
				});
			}

		};

		// Apresentação de erro na tela, caso haja
		req.onerror = function(e, t)
		{

			try
			{
				swal(
				{
					icon : e.type,
					text : e.type,
				});
			}
			catch(e)
			{
				alert(e);
			}

			request = false;

		};

		var timeout = 30000;
		req.timeout = timeout;
		// Tempo de espera para encerrar a conexão

		// Tempo de espera excedido
		req.ontimeout = function()
		{

			var msg = 'Tempo de resposta para a url ' + url + ' expirou.';

			swal(
			{
				icon : 'warning',
				text : msg,
			});

		};

		//
		req.onreadystatechange = function(e)
		{

		};

		// Enviar requisão
		req.send(null);

		// window.addEventListener('online', () => console.log('came online'));
		// window.addEventListener('offline', () => console.log('came offline'));

		return request;

	}

	/**
	 * Função para listar mensagens de errors vindas da requisição
	 */
	function listErrors(error)
	{
		var div = '<div class="error_exception center-align" style=""><h1 class="center-align">' + error.code + '</h1>';
		div += '<code>';
		div += '<h3 class="center-align">' + error.title + '</h3>';
		div += '<p class="center-align">Mensagem: ' + error.message + '<br>';
		div += 'Arquivo: ' + error.file + '<br>';
		div += 'Linha: ' + error.line + '<br>';

		for ( var i in error.trace )
		{
			div += '<p>Arquivo: ' + error.trace[i].file + '<br>';
			div += 'Linha: ' + error.trace[i].line + '<br>';
			div += 'Função: ' + error.trace[i].function + '<br>';
			div += 'Classe: ' + error.trace[i].class + '<br>';
			div += 'Tipo: ' + error.trace[i].type + '<br><p>';
			for ( var j in error.trace[i].args )
			{
				div += 'Args: ' + error.trace[i].args[j] + '<br>';
			}
			div += 'Função: ' + error.trace[i].function + '<p>';
		}

		div += '</p></code></div>';

		return div;
	}

	/**
	 * Define um método pushState para atualizar a URL no navegdor.
	 */
	function __pushState(url)
	{

		var ln = window.location.href;

		if(__request(url))
		{
			window.history.pushState('', '', url);
		}

	}

	/**
	 * is_url();
	 * Faz a verificação de uma String para confirmar se é uma URL.
	 */
	function is_url(href, target)
	{
		var regx = /^#|javascript+/;
		var defined = typeof href !== 'undefined';

		if( !regx.test(href) && defined)
			return true;

		return false;

	}

	/**
	 * __setup();
	 *
	 * Método para processar todos os links
	 *
	 * @param {Object} boo = true|false
	 */
	function __setup(boo)
	{

		var is_ajax = typeof boo !== 'undefined' ? boo : true;

		if( !is_ajax || !__supports_history)
			return false;

		jQuery();

		return true;

	}

	function jQuery(element)
	{

		var el = ( typeof element !== 'undefined' ) ? element : 'body';

		$(el).find('a,button.btn-link').click(function(e)
		{

			var target = $(this).attr('target') || false;
			// 'undefined';
			var href = $(this).attr('data-href') || $(this).attr('href');
			e.preventDefault();
			if(is_url(href) && !target)
			{
				// Compara a url atual ( BASE_URL + window.location.href ) com o link que
				// foi clicado para checar se não é igual. Se não for, requisitar a página.
				if(BASE_URL + window.location.href.split(BASE_URL)[1] != href)
				{
					__pushState(href);
				}
			}
			else if(target)
			{
				if(href && target !== '_self')
					window.open(href, $(this).attr('target'));
				else if(href && target === '_self')
					window.location.href = href;
				else
					window.location.reload();
			}

		});

	}

	/**
	 * __load()
	 *
	 * Método para atualizar a página quando clicar no botão de voltar do navegador
	 *
	 * @param {Object} boo = true|false
	 */
	function __load(boo)
	{
		if(__setup(boo))
		{

			window.addEventListener('popstate', function()
			{
				__request(location.pathname);
			}, true);

		}
	}

	/**
	 * Outras inicializações
	 */
	this.Loader = __load;
	this.Request = __request;
	this.Setup = __setup;
	this.is_url = is_url;
	this.pushState = __pushState;

}
