<?php
namespace App\Models
{

	/**
	 * Classe FaqModel
	 *
	 * @package App
	 */
	class FaqModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_faq';

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
		protected $returnType = 'App\Entities\Faq';

		/**
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = 'App\Validations\FaqValidation';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order = array(
			NULL,
			'id',
			'titulo',
			'descricao',
			'datahora_cadastro',
			'status',
			NULL
		);

		//--------------------------------------------------------------------

		/**
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getFaq($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			// Where
			if ( $where )
				$this -> where($where);

			/*
			 * Adicionar outras condições...
			 */

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'id' => $_POST['search']['value'],
					'descricao' => $_POST['search']['value'],
				);

				$this -> groupStart();
                $this -> orLike($or_like);
                $this -> groupEnd();

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

		public function getAutor($where = FALSE, $fields = '*')
		{

			// Select '*'
			if ( $fields === '*' )
			{
				$fields = array('nome', );
			}

			$this -> select($fields);

			// From
			$this -> from('tb_usuario', TRUE);

			/*
			 * Adicionar outras condições...
			 */
			if ( $where )
				$this -> where($where);

			return $this;

		}

		//------------------------------------------------------------------------------

	}

}
