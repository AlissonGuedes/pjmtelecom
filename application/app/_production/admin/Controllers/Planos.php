<?php

namespace App\Controllers
{

	// Models
	use \App\Models\PlanoTipoModel;
	use \App\Models\PlanoModel;
	use \App\Models\UserModel;
	use \App\Models\CidadeModel;

	// Entities
	use \App\Entities\Plano;

	/**
	 * Controlador que gerencia a página {planos} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Planos
	 */
	class Planos extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\PlanoModel
		 */
		private $plano_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> user_model = new UserModel();
			$this -> tipo_plano_model = new PlanoTipoModel();
			$this -> plano_model = new PlanoModel();
			$this -> cidade_model = new CidadeModel();

			// Entities
			$this -> plano = new Plano();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/planos/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('planos/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/planos/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> plano_model -> countAll();
			$dados['query'] = $this -> plano_model -> getPlano();
			$dados['recordsFiltered'] = $this -> plano_model -> numRows();
			return $this -> view('planos/datatable', $dados);
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
		 * @return view_path = /Views/planos/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			$dados['planos']  = $this -> plano_model;
			$dados['tipos']   = $this -> tipo_plano_model -> getTipoPlano() -> getAll();

			// Listar cidades e bairros para os selects do formulário cidades e bairros
			$dados['cidades'] = $this -> cidade_model -> getCidadeUsuario() -> getAll();
			$dados['bairros'] = $this -> cidade_model;

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> plano -> fill($this -> plano_model -> getPlano(['id' => $id]) -> getRow());

				return $this -> view('planos/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('planos/formulario', $dados);

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

			if ( $id = $this -> plano_model -> create() )
			{

				$this -> plano_model -> registraBairros($id);
				$type = 'success';
				$msg = 'Plano cadastrado com sucesso.';
			}
			else
			{
				$type = 'error';
				$msg = $this -> plano_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'planos/index',
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
		 * @return view_path = /Views/planos/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> plano_model -> edit() )
			{

				$this -> plano_model -> registraBairros();

				$type = 'success';
				$msg = 'Plano atualizado com sucesso.';
			}
			else
			{
				if ( $this -> plano_model -> errors() === NULL )
				{
					$this -> plano_model -> registraBairros();

					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> plano_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'planos/index',
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
			$planos = isset($_POST['planos']) ? $_POST['planos'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($planos) . ' ' . ((count($planos) == 1) ? 'plano selecionado' : 'planos selecionados') . '?';
			$url = base_url() . 'planos';

			if ( $delete )
			{
				if ( $this -> plano_model -> remove($planos) )
				{
					$type = 'success';
					$msg = count($planos) . ' ' . ((count($planos) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'planos/index';
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
				'fields'/*		*/ => $planos,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
