<?php

namespace App\Controllers
{

	// Models
	use \App\Models\BannerModel;
	use \App\Models\UserModel;
	use \App\Models\CidadeModel;

	// Entities
	use \App\Entities\Banner;

	/**
	 * Controlador que gerencia a página {banners} na área
	 * Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Banners
	 */
	class Banners extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\BannerModel
		 */
		private $banner_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			// Models
			$this -> banner_model = new BannerModel();
			$this -> user_model = new UserModel();
			$this -> cidade_model = new CidadeModel();

			// Entities
			$this -> banner = new Banner();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Index
		 *
		 * exibir a Página principal do controlador
		 *
		 * @method index()
		 * @return view_path = /Views/banners/index.phtml
		 */
		public function index()
		{

			$dados['titulo'] = 'Admin';
			return $this -> view('banners/index', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Datatable
		 *
		 * Lista na tabela html de todos os registros cadastrados no
		 * banco de dados
		 *
		 * @method datatable()
		 * @return view_path = /Views/banners/datatable.phtml
		 */
		public function datatable()
		{
			$dados['recordsTotal'] = $this -> banner_model -> countAll();
			$dados['query'] = $this -> banner_model -> getBanner();
			$dados['recordsFiltered'] = $this -> banner_model -> numRows();
			return $this -> view('banners/datatable', $dados);
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
		 * @return view_path = /Views/banners/formulario.phtml
		 */
		public function show_form()
		{

			$id = $this -> uri -> getSegment(3);

			// Listar cidades e bairros para os selects do formulário cidades e bairros
			$dados['cidades'] = $this -> cidade_model -> getCidadeUsuario() -> getAll();
			$dados['bairros'] = $this -> cidade_model;
			$dados['banners'] = $this -> banner_model;

			if ( is_numeric($id) )
			{

				$dados['method'] = 'patch';
				$dados['row'] = $this -> banner -> fill($this -> banner_model -> getBanner(['tb_banner.id' => $id]) -> getRow());

				return $this -> view('banners/formulario', $dados);

			}

			$dados['method'] = 'post';
			return $this -> view('banners/formulario', $dados);

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

			if ( $id = $this -> banner_model -> create() )
			{

				$this -> banner_model -> registraBairros($id);

				$type = 'success';
				$msg = 'Banner cadastrado com sucesso.';

			}
			else
			{
				$type = 'error';
				$msg = $this -> banner_model -> errors();
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'banners/index',
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
		 * @return view_path = /Views/banners/formulario.phtml
		 */
		public function update()
		{

			if ( $this -> banner_model -> edit() )
			{

				$this -> banner_model -> registraBairros();

				$type = 'success';
				$msg = 'Banner atualizado com sucesso.';
			}
			else
			{
				if ( $this -> banner_model -> errors() === NULL )
				{

					$this -> banner_model -> registraBairros();

					$type = 'success';
					$msg = null;
				}
				else
				{
					$type = 'error';
					$msg = $this -> banner_model -> errors();
				}
			}

			echo json_encode(array(
				'type'/*		*/ => $type,
				'msg'/*			*/ => $msg,
				'url'/*			*/ => base_url() . 'banners/index',
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
			$banners = isset($_POST['banners']) ? $_POST['banners'] : $_POST['fields'];

			$type = 'warning';
			$msg = 'Tem certeza que deseja excluir ' . count($banners) . ' ' . ((count($banners) == 1) ? 'categoria selecionada' : 'categorias selecionadas') . '?';
			$url = base_url() . 'banners';

			if ( $delete )
			{
				if ( $this -> banner_model -> remove($banners) )
				{
					$type = 'success';
					$msg = count($banners) . ' ' . ((count($banners) == 1) ? 'registro excluído' : 'registros excluídos') . ' com sucesso ';
					$url = base_url() . 'banners/index';
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
				'fields'/*		*/ => $banners,
				'action'/*		*/ => 'excluir',
				'redirect'/*	*/ => 'refresh',
			));

		}

		//--------------------------------------------------------------------

	}

}
