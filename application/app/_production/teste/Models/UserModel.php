<?php
namespace App\Models
{

	/**
	 * Classe UserModel
	 *
	 * @package App
	 */
	class UserModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_usuario U';

		/**
		 * A chave primária da tabela.
		 *
		 * @var string
		 */
		protected $primaryKey = 'id';

		/**
		 * O formato em que os resultados devem ser
		 * retornados.
		 *
		 * @var string
		 */
		protected $returnType = 'App\Entities\Plano';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		private $order = array(
			NULL,
			'id',
			'nome',
			'email',
			'(SELECT grupo FROM tb_grupo WHERE tb_grupo.id = id_grupo)',
			'ultimo_login',
			'status',
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
		protected $validationRules = array(
			'login' => 'trim|required|min_length[5]|max_length[255]',
			'senha' => 'trim|required|min_length[5]|max_length[20]'
		);

		//------------------------------------------------------------------------------

		/**
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getUser($where = false, $fields = '*')
		{

			if ( $fields === '*' )
			{
				$fields = array(
					'U.id',
					'U.id_grupo',
					'U.nome',
					'U.email',
					'U.login',
					'U.senha',
					'U.ultimo_login',
					'U.primeiro_login',
					'U.listar',
					'U.editar',
					'U.excluir',
					'U.inserir',
					'U.hide_menu',
					'U.status',
					'G.grupo',
					'G.descricao',
					'G.modulo',
					'G.nivel'
				);
			}

			// Select '*'
			$this -> select($fields);

			/*
			 * Adicionar outras condições...
			 */

			$this -> join('tb_acl_grupo G', 'G.id = U.id_grupo', 'left');

			if ( $where )
				$this -> where($where);

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'id' => $_POST['search']['value'],
					'descricao' => $_POST['search']['value'],
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

		//------------------------------------------------------------------------------

		/**
		 * Verifica a existência de usuário no banco de dados ao tentar ao tentar
		 * realizar um login
		 *
		 * @return	boolean		true|false	Caso o usuário falhe durate as tentativas
		 * 			array		$user		Todas as informações do usuário logado com sucesso
		 */
		public function login()
		{

			$post = $_POST;

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$login = array('login' => $post['login']);

			$user = $this -> getUser($login) -> getRowArray();

			if ( $user )
			{

				$senha = hashCode($post['senha']);

				if ( $senha === $user['senha'] )
					return $user;

				return $this -> error('senha', 'Senha Incorreta');

			}
			else
			{
				return $this -> error('login', 'Usuário inexistente ou inativo no sistema');
			}

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

			$plano = new \App\Entities\User;
			$plano -> fill($post);

			$this -> insert($plano);

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
		 *
		 *   $userModel->save($data);
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

			$plano = new \App\Entities\User;
			$plano -> fill($post);

			$this -> update(['id' => $post['id']], $plano);

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
