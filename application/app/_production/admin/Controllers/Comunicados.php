<?php

namespace App\Controllers
{

	// Models
	use \App\Models\ComunicadoModel;
	use \App\Models\UserModel;
	use \App\Models\CidadeModel;

	// Entities
	use \App\Entities\Comunicado;

	/**
	 * Controlador que gerencia a página {comunicados} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Comunicados
	 */
	class Comunicados extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\ComunicadoModel
		 */
		private $comunicado_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> comunicado_model = new ComunicadoModel();
			$this -> user_model = new UserModel();
			$this -> cidade_model = new CidadeModel();

			// Entities
			$this -> comunicado = new Comunicado();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/comunicados/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('comunicados/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/comunicados/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> comunicado_model -> countAll();
			$dados['query'] = $this -> comunicado_model -> getComunicado();
			$dados['recordsFiltered'] = $this -> comunicado_model -> numRows();
			return $this -> view('comunicados/datatable', $dados);
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
		 * @return view_path = /Views/comunicados/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário
			// cidades e bairros
			$dados['cidades'] = $this -> cidade_model -> getCidadeUsuario() -> getAll();
			$dados['bairros'] = $this -> cidade_model;
			$dados['comunicados']  = $this -> comunicado_model;

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> comunicado -> fill($this -> comunicado_model -> getComunicado(['id' => $id]) -> getRow());

				return $this -> view('comunicados/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('comunicados/formulario', $dados);

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

			if ( $id = $this -> comunicado_model -> create() )
			{
			    
			    $this -> comunicado_model -> registraBairros($id);
				$type = 'success';
				$msg = 'Comunicado cadastrado com sucesso.';
			}
			else
			{
				$type = 'error';
				$msg = $this -> comunicado_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'comunicados/index',
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
		 * @return view_path = /Views/comunicados/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> comunicado_model -> edit() )
			{
			    $this -> comunicado_model -> registraBairros();
				$type = 'success';
				$msg = 'Comunicado atualizado com sucesso.';
			}
			else
			{
				if ( $this -> comunicado_model -> errors() === NULL )
				{
				    $this -> comunicado_model -> registraBairros();
					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> comunicado_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'comunicados/index',
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
			$comunicados = isset($_POST['comunicados']) ? $_POST['comunicados'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($comunicados) . ' ' . ((count($comunicados) == 1) ? 'comunicado selecionado' : 'comunicados selecionados') . '?';
			$url = base_url() . 'comunicados';

			if ( $delete )
			{
				if ( $this -> comunicado_model -> remove($comunicados) )
				{
					$type = 'success';
					$msg = count($comunicados) . ' ' . ((count($comunicados) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'comunicados/index';
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
				'fields'/*		*/ => $comunicados,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
