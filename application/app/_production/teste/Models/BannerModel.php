<?php
namespace App\Models
{

	/**
	 * Classe BannerModel
	 *
	 * @package App
	 */
	class BannerModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_banner B';

		/**
		 * A chave primária da tabela.
		 *
		 * @var string
		 */
		protected $primaryKey = 'B.id';

		/**
		 * O formato em que os resultados devem ser
		 * retornados.
		 *
		 * @var string
		 */
		protected $returnType = 'App\Entities\Banner';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		private $order = array(
			NULL,
			'B.id',
			'B.descricao',
			'B.velocidade_download',
			'B.velocidade_upload',
			'B.wifi_incluso',
			'(SELECT tipo FROM tb_banner_tipo WHERE tb_banner_tipo.id = B.id_tipo)',
			'B.valor_real',
			'B.status',
			NULL
		);

		/**
		 * Um array de nomes de campos que podem ser
		 * alterados pelo usuário em inserts/updates.
		 *
		 * @var array
		 */
		protected $allowedFields = array();

		/**
		 * Regras usadas para validar um dado nos métodos
		 * insert, update e save.
		 * O array deve conter o formato de dados passado
		 * para a biblioteca de validação.
		 *
		 * @var array
		 */
		protected $validationRules = array();

		public function __construct()
		{

			parent :: __construct();

			/**
			 * @class $this -> banner = new \App\Entities\Banner;
			 * Retorna uma instância da Entidade Banner
			 * (\App\Admin\Entities\Banner)
			 */
			$this -> banner = new \App\Entities\Banner;
			
			$this -> builder = $this -> builder();

		}

		//--------------------------------------------------------------------

		/**
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getBanner($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			// begin Where
			$this -> where('B.status', '1');

			if ( $where )
				$this -> where($where);
			// and Where

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'B.id' => $_POST['search']['value'],
					'B.descricao' => $_POST['search']['value'],
				);

				$this -> orLike($or_like);

			}

			// Order By
			if ( ! empty($_POST['order']) )
			{
				$orderBy = $this -> order[$_POST['order'][0]['column']];
				$direction = $_POST['order'][0]['dir'];
			}
			else
			{
				$orderBy = $this -> order[1];
				$direction = 'desc';
			}

			$this -> orderBy($orderBy, $direction);

			// Limit
			$limit = isset($_POST['length']) ? $_POST['length'] : NULL;

			if ( ! is_null($limit) )
				$this -> limit($limit);

			// Offset
			$start = isset($_POST['start']) ? $_POST['start'] : NULL;

			if ( ! is_null($start) )
				$this -> offset($start);

			return $this;

		}

		//--------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return boolean		true	Caso o registro seja cadastrado
		 * normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function getBannerByCity($where = false, $fields = '*')
		{

			$fields = array(
				'B.id',
				'B.imagem'
			);

			$this -> getBanner($where, $fields);
            $this -> builder 
                             -> join('tb_banner_bairro', 'B.id = tb_banner_bairro.id_banner', 'left')
                             -> where('tb_banner_bairro.id_bairro', $_COOKIE['bairro'])
                             -> where('B.status', '1');


			return $this;

		}

		//--------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return boolean		true	Caso o registro seja cadastrado
		 * normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function create()
		{

			$post = $this -> request -> getPost();

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$this -> banner -> fill($post);

			$this -> insert($this -> banner);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;

		}

		//--------------------------------------------------------------------

		/**
		 * Editar registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja editado
		 * normalmente
		 * 						false	Caso haja algum erro ao remover
		 */
		public function edit()
		{

			$post = getPut();

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$this -> banner -> fill($post);

			$this -> update(['id' => $post['id']], $this -> banner);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;

		}

		//--------------------------------------------------------------------

		/**
		 * Remove registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja excluído
		 * normalmente
		 * 						false	Caso haja algum erro ao remover
		 */
		public function remove()
		{

			$post = getDelete();

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$fields = $post['fields'];
			$this -> delete($fields);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;
		}

		//--------------------------------------------------------------------

	}

}
