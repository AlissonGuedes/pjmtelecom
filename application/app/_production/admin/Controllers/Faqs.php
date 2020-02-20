<?php

namespace App\Controllers
{

	// Models
	use \App\Models\FaqModel;
	use \App\Models\UserModel;

	// Entities
	use \App\Entities\Faq;

	/**
	 * Controlador que gerencia a página {faqs} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Faqs
	 */
	class Faqs extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\FaqModel
		 */
		private $faq_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> cidade_model = new \App\Models\CidadeModel();
			$this -> faq_model = new FaqModel();

			// Entities
			$this -> faq = new Faq();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/faqs/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('faqs/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/faqs/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> faq_model -> countAll();
			$dados['query'] = $this -> faq_model -> getFaq();
			$dados['recordsFiltered'] = $this -> faq_model -> numRows();
			return $this -> view('faqs/datatable', $dados);
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
		 * @return view_path = /Views/faqs/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário
			// cidades e bairros
			$dados['cidades'] = $this -> cidade_model -> getCidadeUsuario() -> getAll();
			// $dados['bairros'] = $this -> cidade_model -> getBairroUsuario() -> getAll();

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> faq -> fill($this -> faq_model -> getFaq(['id' => $id]) -> getRow());

				return $this -> view('faqs/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('faqs/formulario', $dados);

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

			if ( $this -> faq_model -> create() )
			{
				$type = 'success';
				$msg = 'Faq cadastrado com sucesso.';
			}
			else
			{
				$type = 'error';
				$msg = $this -> faq_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'faqs/index',
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
		 * @return view_path = /Views/faqs/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> faq_model -> edit() )
			{
				$type = 'success';
				$msg = 'Faq atualizado com sucesso.';
			}
			else
			{
				if ( $this -> faq_model -> errors() === NULL )
				{
					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> faq_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'faqs/index',
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
			$faqs = isset($_POST['faqs']) ? $_POST['faqs'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($faqs) . ' ' . ((count($faqs) == 1) ? 'faq selecionado' : 'faqs selecionados') . '?';
			$url = base_url() . 'faqs';

			if ( $delete )
			{
				if ( $this -> faq_model -> remove($faqs) )
				{
					$type = 'success';
					$msg = count($faqs) . ' ' . ((count($faqs) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'faqs/index';
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
				'fields'/*		*/ => $faqs,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
