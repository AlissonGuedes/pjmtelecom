<?php

if ( ! function_exists('DB') )
	require (BASEPATH . 'database' . DS . 'DB.php');

if ( ! function_exists('get_configuracoes') )
	require_once (BASEPATH . 'functions' . DS . 'configuracoes.php');

if ( ! function_exists('session') )
{

	function session()
	{
		$session = hashCode(basename(APPPATH));
		defined('USERDATA') OR define('USERDATA', $session);
	}

}

/**
 * Verifica se a requisição foi feita por Ajax ou não
 */

if ( ! function_exists('isAjax') )
{
	function isAjax()
	{
		return ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false;
	}

}

/**
 * Seta função base_pa() para inclusão de arquivos nas páginas
 */
/**
 * Exibe a raiz da url
 */
if ( ! function_exists('base_url') )
{

	function base_url()
	{

		$base_path = explode('/index.php', $_SERVER['SCRIPT_NAME']);

		if ( basename(APPPATH) != 'main' )
		{
			$url = implode('/' . basename(APPPATH) . '/', $base_path);
		}
		else
		{
			$url = implode('/', $base_path);
		}

		return $url;

	}

}

/**
 * Exibe o diretório atual da aplicação
 * padrões: main / admin
 */
if ( ! function_exists('base_path') )
{

	function base_path($basepath = FALSE)
	{

		$base_path = explode('index.php', $_SERVER['SCRIPT_NAME']);

		if ( $basepath )
			return implode('assets/', $base_path) . get_configuracoes('path') . '/';

		return implode('assets/', $base_path);

	}

}

/**
 * Modifica o diretório de erro padrão
 */
if ( ! function_exists('base_error_path') )
{

	function base_error_path()
	{
		$base_path = explode('index.php', $_SERVER['SCRIPT_NAME']);
		$basepath = (basename(APPPATH) == 'main') ? 'main' : basename(APPPATH);
		return implode('assets/', $base_path);
	}

}

/**
 * Exibe a área pública do site
 */
if ( ! function_exists('site_url') )
{
	function site_url()
	{
		$base_path = explode('index.php', $_SERVER['SCRIPT_NAME']);
		// $base_path = explode('/', dirname(strtolower($base_path[0])));
		return $base_path[0];
		// return $base_path !== '/' ? implode('/', $base_path) . '/' : '..';
	}

}

/**
 * Esta função processa uma matriz de alertas para orientar o usuário sobre as
 * ações do sistema.
 *
 * @var title: string
 * @var $type: error|info|sucess|warning
 * @var $title: string
 * @var $msg: string
 * @var fields: array
 * @var $url: string
 * @var $redirect: string
 *      reload: Atualiza a página após um retorno do TIPO sucesso
 *      refresh: Atualiza dataTable após um retorno do TIPO sucesso
 *      redirect: Redireciona a página após um retorno do TIPO sucesso
 *      bool = TRUE|FALSE
 */
if ( ! function_exists('alert') )
{
	function alert($params = array())
	{

		$message = null;

		$title = ! empty($params['title']) ? $params['title'] : null;
		$type = ! empty($params['type']) ? $params['type'] : 'error';
		$action = ! empty($params['action']) ? $params['action'] : null;
		$msg = ! empty($params['msg']) ? $params['msg'] : null;
		$fields = ! empty($params['fields']) ? $params['fields'] : array();
		$url = ! empty($params['url']) ? $params['url'] : null;
		$style = ! empty($params['style']) ? $params['style'] : null;

		$redirect = (isset($params['redirect']) && ( ! empty($params['redirect']) || $params['redirect'] === false)) ? $params['redirect'] : NULL;

		if ( is_array($msg) )
		{
			foreach ( $msg as $ind => $val )
			{
				if ( is_array($val) )
				{
					foreach ( $val as $v )
					{
						$message[] = array($ind => $v);
					}
				}
				else
				{
					$message = $val;
				}
			}
		}
		else
		{
			$message = $msg;
		}

		$encode = array(
			'title' => $title,
			'type' => $type,
			'action' => $action,
			'msg' => $message,
			'fields' => $fields,
			'url' => $url,
			'redirect' => $redirect,
			'style' => $style
		);

		echo json_encode($encode);

	}

}

/**
 * hashCode
 */
if ( ! function_exists('hashCode') )
{
	function hashCode($senha)
	{
		return ! empty($senha) ? substr(hash('sha512', $senha), 0, 50) : null;
	}

}

/**
 * geraSalt
 */
if ( ! function_exists('geraSalt') )
{
	function geraSalt()
	{
		$salt = new DateTime('now');
		$hash = $salt -> format('Y-m-d H:i:s');
		for ( $i = 0; $i <= 100; $i ++ )
		{
			$senha = hash('sha512', md5($i . $hash) . uniqid());
		}
		return $senha;
	}

}

/**
 * Acessar todas as variáveis públicas de uma classe
 */
if ( ! function_exists('get_vars') )
{

	function get_vars($class, $directory = 'entity')
	{

		$vars = array();

		$CI = &get_instance();
		$cl = load_class($class, $directory);

		$class_vars = get_class_vars(get_class($cl));

		foreach ( $class_vars as $name => $value )
		{
			$var = $name;
			$ind[] = $name;
			$val[] = $cl -> $var;
		}

		if ( ! empty($ind) && ! empty($val) && count($ind) == count($val) )
		{
			$vars = array_combine($ind, $val);
		}

		return $vars;
	}

}

/**
 * Gravar log na base de dados.
 */
if ( ! function_exists('grava_log') )
{
	/**
	 * Error Logging Interface
	 *
	 * We use this as a simple mechanism to access the logging
	 * class and send messages to be logged.
	 *
	 * @param   string  the error level: 'error', 'debug' or 'info'
	 * @param   string  the error message
	 * @return  void
	 */
	function grava_log($level, $message, $tipo = NULL)
	{

		$CI = DB('admin');

		$CI -> db -> set('datahora', date('Y-m-d H:i:s'));
		$CI -> db -> set('ip', getIpAddress());
		$CI -> db -> set('tipo', $tipo);
		$CI -> db -> set('nivel', $level);
		$CI -> db -> set('mensagem', $message);

		$CI -> db -> insert('tb_log');

		log_message($level, $message);

	}

}

/**
 * Permissão de usuários
 */
if ( ! function_exists('get_permissoes') )
{

	function get_permissoes($grupo = NULL, $controle = NULL, $tipo = 'listar')
	{

		$CI = DB();

		$CI -> select('
            P.id, P.id_controle, P.id_grupo, P.listar, P.inserir, P.editar, P.remover, P.status p_status,
            M.id, M.diretorio, M.status m_status,
            C.id, C.id_menu, C.controller, C.route,
            G.id, G.status g_status,
            GM.id, GM.id_grupo, GM.id_modulo
        ');

		$CI -> from('tb_acl_permissao P');
		$CI -> join('tb_acl_controle C', 'P.id_controle = C.id', 'left');
		$CI -> join('tb_acl_grupo G', 'P.id_grupo = G.id', 'left');
		$CI -> join('tb_acl_grupo_modulo GM', 'GM.id_grupo = G.id', 'left');
		$CI -> join('tb_modulo M', 'M.id = GM.id_modulo', 'left');

		$CI -> where('G.id = ', $grupo['id_grupo']);

		$CI -> where('M.diretorio', basename(APPPATH));

		if ( $controle != NULL )
			$CI -> where('C.controller', $controle);

		// if ( $tipo != 'listar' )
		// {
		// $CI -> group_start();
		// $CI -> where('P.listar', 'S');
		// $CI -> where('P.inserir', 'S');
		// $CI -> where('P.editar', 'S');
		// $CI -> or_where('P.remover', 'S');
		// $CI -> group_end();
		// }

		$CI -> where('G.status', '1');
		$CI -> where('P.status', '1');
		$CI -> where('M.status', '1');

		// echo $CI  -> get_compiled_select();

		$query = $CI -> get();

		if ( $controle != NULL )
		{
			$permission = $query -> row();

			if ( (isset($permission) && $permission -> $tipo === 'S' && $permission -> p_status === '1' && $permission -> g_status) )
				return true;
			else
				return false;
		}

		return $query;

	}

}
if ( ! function_exists('redirect_to_login') )
{
	function redirect_to_login()
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

			$true = in_array($d, $login_file, true) ? TRUE : FALSE;

			if ( $true )
				break;

		}

		if ( $true && $RTR -> class !== 'login' && ! is_logged() )
		{

			if ( isAjax() )
				alert([
				'type' => 'error',
				'msg' => 'Sua sessão foi encerrada. Faça login novamente.',
				'redirect' => 'redirect',
				'url' => base_url() . 'login']);
			else
				header('Location: ' . base_url() . 'login');

			exit();

		}

	}

}

/**
 * Verificar o tipo de dispositivo que está sendo utilizado para acessar o
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

if ( ! function_exists('is_logged') )
{
	function is_logged()
	{

		$CI = &get_instance();

		if ( ! isset($CI -> load) )
			return FALSE;

		$CI -> load -> library('Session');

		if ( isset($_SESSION[USERDATA]) )
			return TRUE;

		if ( isset($_COOKIE['fb_username']) && isset($_COOKIE['fb_userid']) && isset($_COOKIE['mc_server']) )
			return TRUE;

		return FALSE;

	}

}

if ( ! function_exists('verifica_login') )
{

	function verifica_login()
	{

		if ( isset_login_file() && ! is_logged() )
		{
			// echo '<script>alert("Login expirado... entre novamente"); location.href = "' .
			// base_url() . 'login";</script>';
			header('Location: ' . base_url() . 'login');
			// exit();

		}

	}

}

if ( ! function_exists('login') )
{

}

if ( ! function_exists('logout') )
{

	function logout()
	{

		$CI = &get_instance();
		$CI -> load -> library('Session');

		if ( isset($_SESSION[USERDATA]) )
		{
			unset($_SESSION[USERDATA]);
		}
		else
		{
			foreach ( $_COOKIE as $ind => $val )
			{
				setcookie($ind, '');
			}
		}

		header('Location: ' . base_url());

	}

}
