<?php

namespace App\Controllers
{

	use \App\Models\CidadeModel;
	use \App\Entities\Plano;

	/**
	 * Controlador que gerencia a página {ajax} na área Administrativa
	 *
	 * @author Alisson Guedes <alissonguedes87@gmail.com>
	 * @version 2
	 * @access public
	 * @package PJM Telecom
	 * @example classe Ajax
	 */
	class Ajax extends AppController {

		/**
		 * Instância do banco de dados
		 *
		 * @var \App\Models\PlanoModel
		 */
		private $cidade_model;

		//--------------------------------------------------------------------

		/**
		 * Método construtor da classe
		 *
		 * @method __construct()
		 */
		public function __construct()
		{

			if ( ! isAjax() )
				location(base_url());

			// Models
			$this -> cidade_model = new CidadeModel();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Cep
		 *
		 * Realiza a busca de endereços por CEP
		 *
		 * @method cep()
		 * @return Array [Rua, Bairro, Cep, Cidade, Estado]
		 */
		 public function verifica_ambiente()
		 {
		     if ( isset($_POST['checkenv']) )
		     {

                echo json_encode(array(
                    'type'  => 'success',
                    'debug' => ENVIRONMENT !== 'production',
                    'msg'   => MENSAGEM_AMBIENTE
                ));

		     }
		 }

		//--------------------------------------------------------------------

		/**
		 * @name Cep
		 *
		 * Realiza a busca de endereços por CEP
		 *
		 * @method cep()
		 * @return Array [Rua, Bairro, Cep, Cidade, Estado]
		 */
		public function cep()
		{

			$cep = $this -> input -> post('cep') ? $this -> input -> post('cep') : $this -> uri -> getSegment(3);
			return buscar_endereco($cep);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Busca Bairros
		 *
		 * Realiza a busca por bairros através do ID da cidade.
		 *
		 * @method busca_bairros()
		 * @return Array [ Bairro, Id]
		 */
		public function busca_bairros()
		{

			$dados['bairros'] = $this -> cidade_model -> getBairrosPorPlano(['B.id_cidade' => $_POST['cidade']]);

			return $this -> view('ajax/bairros', $dados);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Configuração de Editor de Textos
		 *
		 * Realiza a configuração para inserir imagens no plugin para textarea
		 *
		 * @method editor()
		 * @return	array		Conjunto de informações contendo url, nome do diretório etc.
		 * 			boolean		true|false se a alteração foi realizada ou não
		 */
		public function editor()
		{

			$this -> load -> helper('directory');

			// Excluir a imagem do diretório
			if ( $this -> input -> post() )
			{
				$src = urldecode($this -> input -> post('src'));
				$src = explode('assets', $src);
				if ( unlink('./assets/' . $src[1]) )
					return TRUE;
				else
					return FALSE;
			}

			// Listar todas as imagens no diretório
			$url = $this -> uri -> getSegment(3) ? $this -> uri -> getSegment(3) : null;

			if ( $url === 'load-files' )
			{

				$files = array();

				$usr_dir = get_configuracoes('path') . '/img/editor/';
				$basepath = base_path(TRUE) . 'img/editor/';
				if ( is_dir('./assets/' . $usr_dir) )
				{
					$id = 0;
					foreach ( directory_map('./assets/' . $usr_dir) as $row )
					{
						$id ++;
						$files[] = array(
							'url' => $basepath . $row,
							'thumb' => $basepath . $row,
							'name' => $basepath . $row,
							'type' => '',
							'id' => $id,
							'tag' => ''
						);
					}

				}

				if ( empty($files) )
					$files = FALSE;

				echo json_encode($files);

				return FALSE;

			}

			// Realiza o upload do arquivo/imagem
			require_once (BASEPATH . 'libraries' . DS . 'froalaeditor' . DS . 'FroalaEditor.php');

			$file = 'file';
			$path = base_path(TRUE) . 'img/editor/';

			$type = explode('/', $_FILES['file']['type']);

			if ( $type[0] === 'image' )
				$response = FroalaEditor_Image :: upload($path);
			elseif ( $type[0] === 'video' )
				$response = FroalaEditor_Video :: upload($path);
			else
				$response = FroalaEditor_File :: upload($path);

			echo json_encode($response);

		}

		//--------------------------------------------------------------------

		/**
		 * @name Dropzone Uploads
		 *
		 *
		 *
		 * @method	dropzone_uploads()
		 * @return	array		Dados sobre o upload da Imagem
		 * 			boolean		False	caso haja algum erro na validação
		 */
		public function dropzone_uploads()
		{

			$path = $_POST['path'];
			$file = 'file';

			if ( ! is_dir($path) )
				mkdir($path, 0777, TRUE);

			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|png|jpeg|jpg|svg';
			$config['max_size'] = 1024 * 5;
			$config['encrypt_name'] = TRUE;

			$this -> upload -> initialize($config);

			if ( ! $this -> upload -> do_upload($file) )
				return FALSE;
			else
				$this -> imagem = $this -> upload -> data();

		}

		//--------------------------------------------------------------------

		/**
		 * @name Faq Details
		 *
		 * Exibe detelhes na seção Perguntas Frequentes do site.
		 *
		 * @method	faq_details()
		 * @return	array 		Informações de título, conteúdo, autor, data/hora
		 */
		public function faq_details()
		{

			if ( ! isAjax() )
				header('Location: ' . base_url());

			$faq_model = new \App\Models\FaqModel();

			$id = $this -> uri -> getSegment(2);

			$query = $faq_model -> getFaq(['id' => $id]) -> getRow();

			if ( isset($query) )
				echo json_encode(array(
					'type' => 'success',
					'content' => array(
						'modulo' => 'Dúvidas Frequentes',
						'titulo' => $query -> titulo,
						'texto' => $query -> descricao
					),
					'datahora' => $query -> datahora_cadastro,
					'autor' => $query -> autor
				));
			else
				echo json_encode(array(
					'type' => 'error',
					'content' => array(
						'modulo' => 'Dúvidas Frequentes',
						'titulo' => 'Nada Encontrado',
						'texto' => 'Nenhuma informação no momento'
					)
				));

		}

		//--------------------------------------------------------------------

		/**
		 * @name Comunicados Details
		 *
		 * Exibe detelhes na seção Comunicados do site.
		 *
		 * @method	comunicados()
		 * @return	array 		Informações de título, conteúdo, autor, data/hora
		 */
		public function comunicados()
		{

			if ( ! isAjax() )
				header('Location: ' . base_url());

			$comunicado_model = new \App\Models\ComunicadoModel();

			$id = $this -> uri -> getSegment(2);

			$query = $comunicado_model -> getComunicado(['id' => $id]) -> getRow();

			if ( isset($query) )
				echo json_encode(array(

					'type' => 'success',
					'content' => array(
						'modulo' => 'Comunicados',
						'titulo' => $query -> titulo,
						'texto' => $query -> descricao
					),
					'datahora' => $query -> datahora_cadastro,
					'agendamento' => $query -> datahora_agendamento
				));
			else
				echo json_encode(array(
					'type' => 'error',
					'content' => array(
						'modulo' => 'Comunicados',
						'titulo' => 'Nada Encontrado',
						'texto' => 'Nenhum comunicado no momento'
					)
				));

		}

		//--------------------------------------------------------------------

	}

}
