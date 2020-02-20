<?php

namespace App\Controllers
{

	use \App\Models\PlanoModel;
	use \App\Entities\Plano;

	/**
	 * Controlador que gerencia a página {planos} na área Administrativa
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

			$this -> plano = new Plano();
			$this -> plano_model = new PlanoModel();

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
		 * Lista na tabela html de todos os registros cadastrados no banco de dados
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
		 * Se o parâmetro 3 da url for um número, exibe os dados para alteração
		 *
		 * @method show_form()
		 * @return view_path = /Views/planos/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

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
		 * @return boolean		true|false se a alteração foi realizada ou não
		 */
		public function create()
		{

			if ( $this -> plano_model -> create() )
			{
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
				$type = 'success';
				$msg = 'Plano atualizado com sucesso.';
			}
			else
			{
				if ( $this -> plano_model -> errors() === NULL )
				{
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
		 * @return mixed		Apresenta a mensagem de confirmação da exclusão.
		 * 						Caso seja confirmada, o registro será excluído.
		 */
		public function delete()
		{

			parse_str(file_get_contents('php://input'), $_DELETE);

			$delete = isset($_DELETE['excluir']) ? $_DELETE['excluir'] : NULL;
			$planos = isset($_DELETE['planos']) ? $_DELETE['planos'] : $_DELETE['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($planos) . ' ' . ((count($planos) == 1) ? 'categoria selecionada' : 'categorias selecionadas') . '?';
			$url = base_url() . 'planos';

			if ( $delete )
			{
				if ( $this -> plano_model -> remove() )
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
