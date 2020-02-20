<?php
namespace App\Models
{

	/**
	 * Classe ComunicadoModel
	 *
	 * @package App
	 */
	class ComunicadoModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_comunicado B';

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
		protected $returnType = 'App\Entities\Comunicado';

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
			'(SELECT tipo FROM tb_comunicado_tipo WHERE tb_comunicado_tipo.id = B.id_tipo)',
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
		protected $allowedFields = array(
			'id_tipo',
			'id_bairro',
			'id_cidade',
			'descricao',
			'velocidade_upload',
			'velocidade_download',
			'wifi_incluso',
			'cesta_servicos',
			'fibra_optica',
			'nivel',
			'valor_real',
			'valor_prom',
			'data_validade',
			'data_validade_promocao',
			'status'
		);

		/**
		 * Regras usadas para validar um dado nos métodos
		 * insert, update e save.
		 * O array deve conter o formato de dados passado
		 * para a biblioteca de validação.
		 *
		 * @var array
		 */
		protected $validationRules = array(
			'tipo' => 'trim|required',
			'bairro' => 'trim|required',
			'cidade' => 'trim|required',
			'descricao' => 'trim|required',
			'velocidade_upload' => 'trim|required',
			'velocidade_download' => 'trim|required',
			'wifi_incluso' => 'trim',
			'cesta_servicos' => 'trim',
			'fibra_optica' => 'trim',
			'nivel' => 'trim',
			'valor_real' => 'trim',
			'valor_prom' => 'trim',
			'data_validade' => 'trim',
			'data_validade_promocao' => 'trim',
			'status' => 'trim'
		);

		public function __construct()
		{

			parent :: __construct();

			/**
			 * @class $this -> comunicado = new \App\Entities\Comunicado;
			 * Retorna uma instância da Entidade Comunicado (\App\Admin\Entities\Comunicado)
			 */
			// $this -> comunicado = new \App\Entities\Comunicado;

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
		public function getComunicado($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			// Where
			if ( $where )
				$this -> where($where);
        
        	$this -> where('status', '1');
        	
			/*
			 * Adicionar outras condições...
			 */

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
		 * @return boolean		true	Caso o registro seja cadastrado normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function getComunicadoByCity($where = false, $fields = '*')
		{

			$fields = [
			'C.id',
			'C.cidade'];

			$this -> getComunicado($where, $fields);

			$this -> distinct(true);

			$this -> join('tb_cidade C', 'C.id = B.id_cidade', 'left');

			return $this;

		}

		//--------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return boolean		true	Caso o registro seja cadastrado normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function create()
		{

			$post = $this -> request -> getPost();

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$this -> comunicado -> fill($post);

			$this -> insert($this -> comunicado);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;

		}

		//--------------------------------------------------------------------

		/**
		 * Editar registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja editado normalmente
		 * 						false	Caso haja algum erro ao remover
		 *
		 *   // Defined as a model property
		 *   $primaryKey = 'id';
		 *
		 *   // Does an insert()
		 *   $data = [
		 *           'username' => 'darth',
		 *           'email'    => 'd.vader@theempire.com'
		 *   ];
		 *
		 *   $userModel->save($data);
		 *
		 *   // Performs an update, since the primary key, 'id', is found.
		 *   $data = [
		 *           'id'       => 3,
		 *           'username' => 'darth',
		 *           'email'    => 'd.vader@theempire.com'
		 *   ];
		 *   $userModel->save($data);
		 *
		 *   $data = [
		 *        'username' => 'darth',
		 *        'email'    => 'd.vader@theempire.com'
		 *    ];
		 *
		 *    $userModel->update($id, $data);
		 *    $data = [
		 *        'active' => 1
		 *    ];
		 *    $userModel->update([1, 2, 3], $data);
		 *
		 *    $userModel
		 *        ->whereIn('id', [1,2,3])
		 *        ->set(['active' => 1]
		 *        ->update();
		 *
		 */
		public function edit()
		{

			$post = getPut();

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$this -> comunicado -> fill($post);

			$this -> update(['id' => $post['id']], $this -> comunicado);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;

		}

		//--------------------------------------------------------------------

		/**
		 * Remove registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja excluído normalmente
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
