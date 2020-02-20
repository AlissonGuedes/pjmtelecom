<?php

namespace App\Controllers
{

	/**
	 * Controlador que gerencia a página {configuracoes} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Configuracoess
	 */
	class Configuracoes extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\ConfiguracaoModel
		 */
		private $configuracao_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> configuracao_model = new \App\Models\ConfiguracaoModel();

			// Entities
			$this -> configuracoes = new \App\Entities\Configuracao();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/configuracoes/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			$dados['method'] = 'put';

			return $this -> view('configuracoes/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/configuracoes/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> configuracao_model -> countAll();
			$dados['query'] = $this -> configuracao_model -> getConfiguracoes();
			$dados['recordsFiltered'] = $this -> configuracao_model -> numRows();
			return $this -> view('configuracoes/datatable', $dados);
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
		 * @return view_path = /Views/configuracoes/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário
			// cidades e bairros
			$dados['grupos'] = $this -> configuracao_model -> getGrupo() -> getAll();

			if ( is_numeric($id) )
			{

				$dados['method'] = 'put';
				$dados['row'] = $this -> configuracoes -> fill($this -> configuracao_model -> getConfiguracoes(['tb_usuario.id' => $id]) -> getRow());

				return $this -> view('configuracoes/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('configuracoes/formulario', $dados);

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

			if ( $this -> configuracao_model -> create() )
			{
				$type = 'success';
				$msg = 'Configurações salvas com sucesso.';
			}
			else
			{
				$type = 'error';
				$msg = $this -> configuracao_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'configuracoes/index',
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
		 * @return view_path = /Views/configuracoes/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> configuracao_model -> edit() )
			{

				if ( $this -> configuracao_model -> sendMail() )
				{
					$type = 'success';
					$msg = 'Configurações salvas com sucesso.';
				}
				else
				{
					$type = 'success';
					$msg = $this -> configuracao_model -> errors();
				}
			}
			else
			{

				if ( $this -> configuracao_model -> errors() === NULL )
				{
					$type = 'success';
					$msg = 'Configurações salvas com sucesso.';
				}
				else
				{
					$type = 'error';
					$msg = $this -> configuracao_model -> errors();
				}

			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'empresa',
				'redirect'/*	*/ => 'reload'
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
			$configuracoes = isset($_POST['configuracoes']) ? $_POST['configuracoes'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($configuracoes) . ' ' . ((count($configuracoes) == 1) ? 'configuração selecionada' : 'configurações selecionadas') . '?';
			$url = base_url() . 'configuracoes';

			if ( $delete )
			{
				if ( $this -> configuracao_model -> remove($configuracoes) )
				{
					$type = 'success';
					$msg = count($configuracoes) . ' ' . ((count($configuracoes) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'configuracoes/index';
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
				'fields'/*		*/ => $configuracoes,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
