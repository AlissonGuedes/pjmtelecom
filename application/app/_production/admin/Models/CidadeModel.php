<?php
namespace App\Models
{

	/**
	 * Classe CidadeModel
	 *
	 * @package App
	 */
	class CidadeModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_cidade';

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
		protected $returnType = false;

		/**
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = false;
		//'App\Validations\CidadeValidation';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order;

		//------------------------------------------------------------------------------

		/**
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getCidade($where = false, $fields = '*')
		{

			if ( $fields === '*' )
			{
				$fields = array(
					'tb_cidade.id',
					'tb_cidade.cidade',
					'tb_cidade.uf',
				);
			}

			// Select '*'
			$this -> select($fields);
			$this -> distinct();

			// From
			$this -> from('tb_cidade', TRUE);
			$this -> join('tb_bairro', 'tb_bairro.id_cidade = tb_cidade.id', 'inner');

			/*
			 * Adicionar outras condições...
			 */

			if ( $where )
				$this -> where($where);

			$this -> where('uf', 'PB');

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'tb_cidade.id' => $_POST['search']['value'],
					'tb_cidade.cidade' => $_POST['search']['value'],
				);

				$this -> orLike($or_like);

			}

			// Order By
			$this -> order = array(
				NULL,
				'tb_cidade.id',
				'tb_cidade.cidade',
				'tb_cidade.uf',
				NULL
			);

			if ( ! empty($_POST['order']) )
			{
				$orderBy = $this -> order[$_POST['order'][0]['column']];
				$direction = $_POST['order'][0]['dir'];
			}
			else
			{
				$orderBy = $this -> order[2];
				$direction = 'asc';
			}

			if ( ! is_null($orderBy) )
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
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getBairro($where = false, $fields = '*')
		{

			if ( $fields === '*' )
			{
				$fields = array(
					'tb_bairro.id',
					'tb_bairro.bairro',
					'tb_bairro.id_cidade'
				);
			}

			// Select '*'
			$this -> select($fields);
			$this -> distinct();

			// From
			$this -> from('tb_bairro', TRUE);

			$this -> join('tb_cidade', 'tb_cidade.id = tb_bairro.id_cidade', 'left');

			/*
			 * Adicionar outras condições...
			 */
			if ( $where )
				$this -> where($where);

			$this -> where('tb_cidade.uf', 'PB');

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'tb_bairro.id' => $_POST['search']['value'],
					'tb_bairro.bairro' => $_POST['search']['value'],
				);

				$this -> orLike($or_like);

			}

			// Order By
			$this -> order = array(
				NULL,
				'tb_bairro.id',
				'tb_bairro.bairro',
				'tb_bairro.uf',
				NULL
			);

			if ( ! empty($_POST['order']) )
			{
				$orderBy = $this -> order[$_POST['order'][0]['column']];
				$direction = $_POST['order'][0]['dir'];
			}
			else
			{
				$orderBy = $this -> order[2];
				$direction = 'asc';
			}

			if ( ! is_null($orderBy) )
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
		 * Cadastra novo registro na tabela
		 *
		 * @return query
		 */
		public function getCidadeUsuario($where = false, $fields = '*')
		{

			$fields = array(
				'C.id',
				'C.cidade'
			);

			if ( $_SESSION[USERDATA]['id_grupo'] > 2 )
			{
				$this -> from('tb_usuario_bairro U', true);
				$this -> distinct(true);
				$this -> select($fields);
				$this -> join('tb_cidade C', 'C.id = U.id_cidade', 'left');
				$this -> where('U.id_usuario', $_SESSION[USERDATA]['id']);
			}
			else
			{
				$this -> from('tb_cidade C', true);
				$this -> distinct(true);
				$this -> where('C.uf = "PB"');
			}

			$this -> orderBy('C.cidade ASC');

			return $this;

		}

		//------------------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return query
		 */
		public function getBairroUsuario($where = false, $fields = '*')
		{

			$fields = array(
				'B.id',
				'B.bairro'
			);

			if ( $_SESSION[USERDATA]['id_grupo'] > 2 )
			{
				$this -> from('tb_usuario_bairro U', true);
				$this -> select($fields);
				$this -> join('tb_bairro B', 'B.id = U.id_bairro', 'left');
				$this -> where('U.id_usuario', $_SESSION[USERDATA]['id']);
			}
			else
			{
				$this -> from('tb_bairro B', true);
				$this -> distinct(true);
			}

            if (! is_null($where) )
                $this -> where($where);

			$this -> orderBy('B.bairro ASC');

			return $this;

		}

		//------------------------------------------------------------------------------

		public function registraBairros(string $table, string $column, int $id = null)
		{

			$builder = $this -> builder();

			$id = isset($_POST['id']) && ! empty($_POST['id']) ? $_POST['id'] : $id;

			if ( isset($_POST['bairros']) && ! empty($_POST['bairros']) )
			{

				foreach ( $_POST['bairros'] as $cidades )
				{

					$bairros = explode(':', $cidades);

					$id_cidade = $bairros[0];
					$id_bairro = $bairros[1];

					$fields[] = array(
						$column => $id,
						'id_cidade' => $id_cidade,
						'id_bairro' => $id_bairro
					);

				}

				if ( isset($fields) )
				{

					// Verificar se o registro atual já existe, se não existir,
					// deve ser cadastrado
					foreach ( $fields as $row )
					{

						$builder -> where($column, $row[$column]);
						$builder -> where('id_cidade', $row['id_cidade']);
						$builder -> where('id_bairro', $row['id_bairro']);

						$exists = $builder -> select('id, ' . $column . ', id_bairro, id_cidade')
						/*				*/ -> from($table, true)
						/*			    */ -> get()
						/*			    */ -> getRowObject();

						if ( ! isset($exists) )
						{
							$result = $builder -> from($table, true)
							/*				*/ -> set($column, $row[$column])
							/*				*/ -> set('id_cidade', $row['id_cidade'])
							/*				*/ -> set('id_bairro', $row['id_bairro'])
							/*				*/ -> insert();
						}

					}

					// Verificar todos os registros para exclusão:
					$builder -> where($column, $row[$column]);
					foreach ( $fields as $row )
					{
						$builder -> groupStart();
						$builder -> Where('id_cidade <>', $row['id_cidade']);
						$builder -> orWhere('id_bairro <>', $row['id_bairro']);
						$builder -> groupEnd();
					}

                    $deletar_bairros = $builder -> select('id')
					/*						 */ -> from($table, true)
					/*						 */ -> get()
    				/*						 */ -> getResult();

					if ( isset($deletar_bairros) )
					{
						foreach ( $deletar_bairros as $row )
						{
							$result = $builder -> from($table, true)
							/*				*/ -> where('id', $row -> id)
							/*				*/ -> delete();
						}
					}

				}

			}
			else
			{

				// Se não vier nenhum bairro do formulário, excluir todos os
				// registros cadastrados na tabela referente ao plano
				$builder -> from($table, true)
				/*	  */ -> where($column, $id)
				/*	  */ -> delete();
			}

		}

		//------------------------------------------------------------------------------

	}

}
