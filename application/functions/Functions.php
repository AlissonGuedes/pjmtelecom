<?php

use \Alisson\Library;

/*------------------------------------------------------------------------------------------------*/

/**
 * Criar uma variável global semelhante a $_POST e $_GET para
 * requisições RESTful
 * a fim de evitar adicionar informações extras para definir
 * as ações que serão
 * realizadas.
 *
 * Antes, as requisições para Adicionar, Editar, Deletar ou
 * Listar que
 * poderiam vir, por exemplo, como:
 *
 * 		http://localhost/usuario/add
 * 		http://localhost/usuario/datatable
 *		http://localhost/usuario/editar
 * 		http://localhost/usuario/excluir/1
 *
 * Todas passarão a ter o seguinte padrão:
 *
 *		http://localhost/usuario 		- Para ações de listar,
 * adicionar
 * 		http://localhost/usuario/1 		- Para ações de listar
 * apenas um determinado
 *											item, editar ou excluir
 *
 * as ações que devem ser tomadas pelo sistema deverão
 * constar no atributo method
 * de cada formulário que poderão ser do tipo:
 *	GET [sem passagem do ID] 				- retorna uma página ou todos
 * os itens
 *												registrados
 *	GET [com passagem do ID] 				- retorna apenas o recurso
 * especificado
 *	POST					 				- utilizado para criar um recurso
 *	DELETE [passagem do ID obrigatória]		- utilizado para
 * remover um recurso
 *	PUT [Normalmente utilizado com ID]		- utilizado para
 * editar um recurso sem
 *												especificar qual foi alterado
 *	PATCH [Normalmente utilizado com ID]	- Semelhante ao PUT,
 * mas envia apenas o
 *												recurso que de fato foi alterado
 */
function request_methods()
{

	$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;

	switch( $method )
	{
		case 'PATCH' :
			parse_str(file_get_contents('php://input'), $_PATCH);
			break;

		case 'PUT' :
			parse_str(file_get_contents('php://input'), $_PUT);
			break;

		case 'DELETE' :
			parse_str(file_get_contents('php://input'), $_DELETE);
			break;
	}

}

/*------------------------------------------------------------------------------------------------*/

/**
 * Referencia \functions\Library::configuracoes()
 * @return Library :: configuracoes()
 */
function configuracoes($column, $table = 'tb_configuracao', $where = array())
{

	return Library :: configuracoes($column, $table, $where);

};

/*------------------------------------------------------------------------------------------------*/

/**
 * Transforma o nome do diretório de trabalho do usuário em
 * uma hash única.
 * Determina qual SESSÃO será removida, caso exista, quando o
 * usuário fizer
 * logout
 * @return constant USERDATA
 */
function define_session()
{
	$session = hashCode(basename(APPPATH));
	defined('USERDATA') OR define('USERDATA', $session);
}

/*------------------------------------------------------------------------------------------------*/

/**
 * Verifica se a requisição da página foi feita ou não via
 * Ajax
 * @return boolean
 */
function isAjax()
{
	return ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false;
}

/*------------------------------------------------------------------------------------------------*/

/**
 * Exibe a URL do site a partir do diretório atual em que o
 * usuário se encontra:
 * 		- application
 * 			- app
 * 				- main	-> URL: localhost/home/index
 * 				- admin	-> URL: localhost/admin/home/index
 * 				- teste	-> URL: localhost/teste/home/index
 * @return $path
 */
if ( ! function_exists('base_url') )
{

	function base_url()
	{

		$base_path = explode('/index.php', $_SERVER['SCRIPT_NAME']);

		if ( basename(APPPATH) != 'main' )
		{
			$path = implode('/' . basename(APPPATH) . '/', $base_path);
		}
		else
		{
			$path = implode('/', $base_path);
		}

		return $path;

	}

}

/*------------------------------------------------------------------------------------------------*/

/**
 * Exibe a URL do site a partir do diretório atual em que o
 * usuário se encontr
 * até os scripts do site:
 * 		- application
 * 			- app
 * 				- main	-> URL: localhost/assets/...
 * 				- admin	-> URL: localhost/admin/assets/...
 * 				- teste	-> URL: localhost/teste/assets/...
 * @return $path
 */
if ( ! function_exists('base_path') )
{

	function base_path($basepath = FALSE, $assets = '')
	{

		$path = explode('/index.php', $_SERVER['SCRIPT_NAME']);

		if ( $basepath )
            return implode($assets, $path) . configuracoes('path') . '/';

		return implode('/assets/', $path);

	}

}

/*------------------------------------------------------------------------------------------------*/

/**
 * Exibe a URL principal do site independentemente do
 * diretório atual em que o
 * usuário se encontre:
 * 		- application
 * 			- app
 * 				- main	-> URL: localhost/home/index
 * 				- admin	-> URL: localhost/home/index
 * 				- teste	-> URL: localhost/home/index
 * @return $path
 */
if ( ! function_exists('site_url') )
{

	function site_url()
	{
		return explode('index.php', $_SERVER['SCRIPT_NAME'])[0];
	}

}

/*------------------------------------------------------------------------------------------------*/

/**
 * Função para criptografar uma string, geralmente para
 * utilização com senha ou
 * modificar nomes de arquivos para uploads
 */
if ( ! function_exists('hashCode') )
{
	function hashCode($str)
	{
		return ! empty($str) ? substr(hash('sha512', $str), 0, 50) : null;
	}

}

/*------------------------------------------------------------------------------------------------*/

if ( ! function_exists('location') )
{

	/**
	 * Redireciona o usuário para outra página informada.
	 * Utilização em substituição
	 * à função nativa do PHP `header` e `redirect` nativa do
	 * CodeIgniter
	 * para redirecionar o usuário sempre para a URL base
	 *
	 * @param  $location      string: Nome da página de destino
	 * @return header('Location: ' . $href);
	 */
	function location($href)
	{
		header('Location: ' . $href);
		exit ;
	}

}

/*------------------------------------------------------------------------------------------------*/

/**
 * Função para verificar se o usuário está logado no sistema
 */
if ( ! function_exists('is_logged') )
{

	function is_logged()
	{

		\Config\Services :: session();

		if ( isset($_SESSION[USERDATA]) )
			return TRUE;

		if ( isset($_COOKIE['fb_username']) && isset($_COOKIE['fb_userid']) && isset($_COOKIE['mc_server']) )
			return TRUE;

		// if ( isset($_COOKIE['cidade']) )
		// return TRUE;

		return FALSE;

	}

}

/*------------------------------------------------------------------------------------------------*/

/**** ATUALIZAR A PARTIR DAQUI *****/

if ( ! function_exists('redirect_to_login') )
{
	function redirect_to_login()
	{

		global $RTR;

		helper('filesystem');

		$login_file = array(
			'Account.php',
			'Login.php'
		);

		$path = APPPATH . 'Controllers' . DS;

		$dir = directory_map($path);
		$true = false;

		foreach ( $dir as $d )
		{

			$true = in_array($d, $login_file, true) ? TRUE : FALSE;

			if ( $true )
				break;

		}

		$current_request = (basename($_SERVER['REQUEST_URI']));

		$script = '<script>
						swal(
						{
							title: "",
							text: "Sua sessão foi modificada. Faça login novamente!",
							icon: "warning",
							timer: 2500,
							buttons : {
								ok	: "Entrar Novamente"
							},
							catch : {
								url : "' . base_url() . '"
							}
						}).then((ok) => {
							location.href = url;
						});
		</script>';

		if ( $true && $current_request !== 'login' && ! is_logged() )
		{

			if ( isAjax() )
				exit($script);
			else
				location(base_url() . 'login');

		}
		else if ( $true && $current_request === 'login' && is_logged() )
		{
			if ( $_SERVER['REQUEST_URI'] == base_url() )
				location(base_url() . 'dashboard');
		}

	}

}

/**
 * Verificar o tipo de dispositivo que está sendo utilizado
 * para acessar o
 * site/sistema
 */
if ( ! function_exists('is_mobile') )
{

	function is_mobile()
	{

		$useragent = $_SERVER['HTTP_USER_AGENT'];

		if ( preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)) )
			return true;

		return false;

	}

}

if ( ! function_exists('isset_login_file') )
{

	function isset_login_file()
	{

		global $RTR;

		$CI = &get_instance();
		$CI -> load -> library('Session');
		$CI -> load -> helper('directory');

		$login_file = [
		'Account.php',
		'Login.php'];

		$path = APPPATH . 'controllers' . DS;

		$dir = directory_map($path);
		$true = false;

		foreach ( $dir as $d )
		{

			$isset_login_file = in_array($d, $login_file, true) ? TRUE : FALSE;

			if ( $isset_login_file )
				break;

		}

		if ( $isset_login_file && $RTR -> class !== 'login' )
			return true;

	}

}

/** UNIDADES DE MEDIDAS **/

/**
 * Retorna o tamanho de arquivos em unidades b, Mb, Kb, Gb,
 * Tb
 */
function getfilesize($size)
{
	if ( $size < 2 )
	{
		return $size . " byte";
	}
	$units = array(
		" bytes",
		" KB",
		" MB",
		" GB",
		" TB"
	);

	for ( $i = 0; $size > 1024; $i ++ )
	{
		$size /= 1024;
	}

	return round($size, 2) . $units[$i];

}

/**
 * Retorna uma unidade de medida
 */
function get_unidade_medida($size, $un = '')
{
	for ( $i = 0; $size > 1024; $i ++ )
	{
		$size /= 1024;
	}
	return round($size, 0) . $un;
}

/**
 * retorna o tamanho de arquivos em bytes para a conversão
 */
function return_bytes($val)
{

	$val = trim($val);
	$last = strtolower($val{strlen($val) - 1});

	switch($last)
	{
		case "g" :
			$val *= 1024;
			break;
		case "m" :
			$val *= 1024;
			break;
		case "k" :
			$val *= 1024;
			break;
	}

	return $val;

}

/**
 *--------------------------------------------------------------------
 * Editado por Alisson guedes
 *--------------------------------------------------------------------
 */
function getPut($param = null)
{

	parse_str(file_get_contents('php://input'), $_PUT);

	if ( ! is_null($param) && ! isset($_PUT[$param]) )
		return false;

	if ( $param )
		return $_PUT[$param];

	return $_PUT;

}

function getPatch($param = null)
{

	parse_str(file_get_contents('php://input'), $_PATCH);

	if ( ! is_null($param) && ! isset($_PATCH[$param]) )
		return false;

	if ( $param )
		return $_PATCH[$param];

	return $_PATCH;

}

function getDelete($param = null)
{

	parse_str(file_get_contents('php://input'), $_DELETE);

	if ( ! is_null($param) && ! isset($_DELETE[$param]) )
		return false;

	if ( $param )
		return $_DELETE[$param];

	return $_DELETE;

}

//--------------------------------------------------------------------
// Fim por Alisson guedes
//--------------------------------------------------------------------

/**
 * Remove caratecres especiais
 * Converte todos os caracteres de um arquivo para caixa
 * baixa
 * Remove espaçamentos
 */
function limpa_string($string, $replace = '-')
{

	$output = array();
	$a = array(
		'Á' => 'a',
		'À' => 'a',
		'Â' => 'a',
		'Ä' => 'a',
		'Ã' => 'a',
		'Å' => 'a',
		'á' => 'a',
		'à' => 'a',
		'â' => 'a',
		'ä' => 'a',
		'ã' => 'a',
		'å' => 'a',
		'a' => 'a',
		'Ç' => 'c',
		'ç' => 'c',
		'Ð' => 'd',
		'É' => 'e',
		'È' => 'e',
		'Ê' => 'e',
		'Ë' => 'e',
		'é' => 'e',
		'è' => 'e',
		'ê' => 'e',
		'ë' => 'e',
		'Í' => 'i',
		'Î' => 'i',
		'Ï' => 'i',
		'í' => 'i',
		'ì' => 'i',
		'î' => 'i',
		'ï' => 'i',
		'Ñ' => 'n',
		'ñ' => 'n',
		'O' => 'o',
		'Ó' => 'o',
		'Ò' => 'o',
		'Ô' => 'o',
		'Ö' => 'o',
		'Õ' => 'o',
		'ó' => 'o',
		'ò' => 'o',
		'ô' => 'o',
		'ö' => 'o',
		'õ' => 'o',
		'ø' => 'o',
		'œ' => 'o',
		'Š' => 'o',
		'Ú' => 'u',
		'Ù' => 'u',
		'Û' => 'u',
		'Ü' => 'u',
		'U' => 'u',
		'ú' => 'u',
		'ù' => 'u',
		'û' => 'u',
		'ü' => 'u',
		'Y' => 'y',
		'Ý' => 'y',
		'Ÿ' => 'y',
		'ý' => 'y',
		'ÿ' => 'y',
		'Ž' => 'z',
		'ž' => 'z'
	);
	$string = strtr($string, $a);
	$regx = array(
		" ",
		".",
		"+",
		"@",
		"#",
		"!",
		"$",
		"%",
		"¨",
		"&",
		"*",
		"(",
		")",
		"_",
		"-",
		"+",
		"=",
		";",
		":",
		",",
		"\\",
		"|",
		"£",
		"¢",
		"¬",
		"/",
		"?",
		"°",
		"´",
		"`",
		"{",
		"}",
		"[",
		"]",
		"ª",
		"º",
		"~",
		"^",
		"\'",
		"\""
	);

	$replacement = str_replace($regx, '|', trim(strtolower($string)));
	$explode = explode('|', $replacement);

	for ( $i = 0; $i < count($explode); $i ++ )
	{
		if ( ! empty($explode[$i]) )
			$output[] = trim($explode[$i]);
	}

	return implode($replace, $output);

}
