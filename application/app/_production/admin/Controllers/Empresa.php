<?php

namespace App\Controllers
{

	/**
	 * Controlador que gerencia a página {empresa} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Empresas
	 */
	class Empresa extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\EmpresaModel
		 */
		private $empresa_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> empresa_model = new \App\Models\EmpresaModel();

			// Entities
			$this -> empresa = new \App\Entities\Empresa();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/empresa/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('empresa/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/empresa/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> empresa_model -> countAll();
			$dados['query'] = $this -> empresa_model -> getEmpresa();
			$dados['recordsFiltered'] = $this -> empresa_model -> numRows();
			return $this -> view('empresa/datatable', $dados);
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
		 * @return view_path = /Views/empresa/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário
			// cidades e bairros
			$dados['grupos'] = $this -> empresa_model -> getGrupo() -> getAll();

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> empresa -> fill($this -> empresa_model -> getEmpresa(['tb_usuario.id' => $id]) -> getRow());

				return $this -> view('empresa/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('empresa/formulario', $dados);

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

			if ( $this -> empresa_model -> create() )
			{
				$type = 'success';
				$msg = 'Usuário cadastrado com sucesso.';
			}
			else
			{
				$type = 'error';
				$msg = $this -> empresa_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'empresa/index',
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
		 * @return view_path = /Views/empresa/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> empresa_model -> edit() )
			{

				if ( $this -> empresa_model -> sendMail() )
				{
					$type = 'success';
					$msg = 'Usuário atualizado com sucesso.';
				}
				else
				{
					$type = 'success';
					$msg = $this -> empresa_model -> errors();
				}
			}
			else
			{
				if ( $this -> empresa_model -> errors() === NULL )
				{
					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> empresa_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'empresa/index',
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
			$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($empresa) . ' ' . ((count($empresa) == 1) ? 'empresa selecionada' : 'empresa selecionadas') . '?';
			$url = base_url() . 'empresa';

			if ( $delete )
			{
				if ( $this -> empresa_model -> remove($empresa) )
				{
					$type = 'success';
					$msg = count($empresa) . ' ' . ((count($empresa) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'empresa/index';
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
				'fields'/*		*/ => $empresa,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
