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
		protected $table = 'tb_comunicado';

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
		protected $returnType = 'App\Entities\Comunicado';

		/**
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = 'App\Validations\ComunicadoValidation';

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
			'datahora_agendamento',
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
		public function getComunicado($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			// Where
			if ( $where )
				$this -> where($where);

			/*
			 * Adicionar outras condições...
			 */
			$this -> where('id_autor', $_SESSION[USERDATA]['id']);

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

		public function getComunicadoBairro($where = null)
		{
		    

			$fields = array(
				'P.id_comunicado',
				'P.id_bairro',
				'P.id_cidade'
			);

			$this -> select($fields);
			
            $this -> from('tb_cidade C, tb_bairro B, tb_comunicado_bairro P', true)
                  -> where('C.id = P.id_cidade')
                  -> where('B.id = P.id_bairro');

            if ( !is_null($where) )
    			$this -> where( $where );

			$this -> orderBy('B.bairro ASC');

			return $this;

		}


		//------------------------------------------------------------------------------

		public function registraBairros(int $id = null)
		{

			$this -> cidade_model = new \App\Models\CidadeModel();
			$this -> cidade_model -> registraBairros('tb_comunicado_bairro', 'id_comunicado', $id);

		}

		//------------------------------------------------------------------------------

	}

}
