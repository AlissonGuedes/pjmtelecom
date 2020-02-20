<?php

namespace App\Controllers
{

	/**
	 * Controlador que gerencia a página {usuarios} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Users
	 */
	class Usuarios extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\UserModel
		 */
		private $user_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> user_model = new \App\Models\UserModel();
			$this -> plano_model = new \App\Models\PlanoModel();
			$this -> cidade_model = new \App\Models\CidadeModel();

			// Entities
			$this -> user = new \App\Entities\User();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/usuarios/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('usuarios/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/usuarios/datatable.phtml
		 */
		public function datatable()
		{

			$dados['recordsTotal'] = $this -> user_model -> countAll();
			$dados['query'] = $this -> user_model -> getUser(['tb_usuario.id <> ' => $_SESSION[USERDATA]['id']]);
			$dados['recordsFiltered'] = $this -> user_model -> numRows();
			return $this -> view('usuarios/datatable', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Show Form
		 *
		 * Exibe o formulário para cadastrar ou alterar um registro.
		 * Se o parâmetro 3 da url for um número, exibe os dados para
		 * alteração
		 *
		 * @method show_form()
		 * @return view_path = /Views/usuarios/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário
			// cidades e bairros
			$dados['grupos'] = $this -> user_model -> getGrupo() -> getAll();
			$dados['gestor'] = $this -> user_model -> getGestor(['id_grupo = ' => 3]) -> getAll();
			$dados['cidades'] = $this -> cidade_model -> getCidadeUsuario() -> getAll();
			$dados['bairros'] = $this -> cidade_model;
			$dados['usuarios']  = $this -> user_model;

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> user -> fill($this -> user_model -> getUser(['tb_usuario.id' => $id]) -> getRow());
				$dados['usr_cidades'] = $this -> user_model -> getCidadeUsuario(['U.id_usuario' => $id]) -> getAll();

				return $this -> view('usuarios/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('usuarios/formulario', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Create
		 *
		 * Insere/Salva um novo registro no banco de dados.
		 *
		 * @method create()
		 * @return boolean		true|false se a alteração foi realizada
		 * ou não
		 */
		public function create()
		{

			if ( $id = $this -> user_model -> create() )
			{

				$this -> user_model -> registraBairros($id);

				if ( $this -> user_model -> sendMail() )
				{
					$type = 'success';
					$msg = 'Usuário cadastrado com sucesso.';
				}
				else
				{
					$type = 'success';
					$msg = $this -> user_model -> errors();
				}

			}
			else
			{
				$type = 'error';
				$msg = $this -> user_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'usuarios/index',
				'redirect'/*	*/ => 'refresh'
			));

		}

		//--------------------------------------------------------------------

		/**
		 * @name Update
		 *
		 * Edita um registro já existente no banco de dados.
		 *
		 * @method datatable()
		 * @return view_path = /Views/usuarios/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> user_model -> edit() )
			{

				$this -> user_model -> registraBairros();

				if ( $this -> user_model -> sendMail() )
				{
					$type = 'success';
					$msg = 'Usuário atualizado com sucesso.';
				}
				else
				{
					$type = 'success';
					$msg = $this -> user_model -> errors();
				}
			}
			else
			{
				if ( $this -> user_model -> errors() === NULL )
				{
					$this -> user_model -> registraCidades();
					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> user_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'usuarios/index',
				'redirect'/*	*/ => 'refresh'
			));

		}

		//--------------------------------------------------------------------

		/**
		 * @name Delete
		 *
		 * Exclui registro do banco de dados.
		 *
		 * @method delete()
		 * @return mixed		Apresenta a mensagem de confirmação da
		 * exclusão.
		 * 						Caso seja confirmada, o registro será excluído.
		 */
		public function delete()
		{

			$delete = isset($_POST['excluir']) ? $_POST['excluir'] : true;
			$users = isset($_POST['usuarios']) ? $_POST['usuarios'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($users) . ' ' . ((count($users) == 1) ? 'usuário selecionado' : 'usuários selecionados') . '?';
			$url = base_url() . 'usuarios';

			if ( $delete )
			{
				if ( $this -> user_model -> remove($users) )
				{
					$type = 'success';
					$msg = count($users) . ' ' . ((count($users) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'usuarios/index';
				}
				else
				{
					$type = 'error';
					$msg = 'Nenhum registro pôde ser excluído.';
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => $url,
				'fields'/*		*/ => $users,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

		public function delete_bairros()
		{

			$type = 'error';
			$msg = 'Não foi possível excluir';

			if ( isset($_POST['bairro']) && ! empty($_POST['bairro']) )
			{

				if ( $this -> user_model -> deleteBairros() )
				{
					$type = 'success';
					$msg = 'Bairro excluído!';
				}

			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'redirect'/*	*/ => false,
			));

		}

	}

}
