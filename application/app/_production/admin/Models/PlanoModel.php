<?php
namespace App\Models
{

	/**
	 * Classe PlanoModel
	 *
	 * @package App
	 */
	class PlanoModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_plano';

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
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = 'App\Validations\PlanoValidation';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order = array(
			NULL,
			'id',
			'descricao',
			'velocidade_download',
			'velocidade_upload',
			'wifi_incluso',
			'(SELECT tipo FROM tb_plano_tipo WHERE tb_plano_tipo.id = id_tipo)',
			'valor_mensal',
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
		public function getPlano($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			// Where
			if ( $where )
				$this -> where($where);

			/*
			 * Adicionar outras condições...
			 */
			$this -> where('id_usuario', $_SESSION[USERDATA]['id']);

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

//         public function getPlanoCidade($where = null)
//         {
            
//             $fields = array(
//                 'C.id',
//                 'C.cidade'
//             );
            
//             if ( $_SESSION[USERDATA]['id_grupo'] > 2)
//             {
//                 $this -> select($fields);
//                 $this -> from('tb_cidade C, tb_plano_bairro P', true)
//                       -> where('C.id = P.id_cidade');

//                 if ( !is_null($where) )
//         			$this -> where( $where );
    
//     			$this -> orderBy('B.bairro ASC');
//             }

// 			return $this;
//         }

		public function getPlanoBairro($where = null)
		{

			$fields = array(
				'P.id_plano',
				'P.id_bairro',
				'P.id_cidade'
			);
			
            $this -> from('tb_cidade C, tb_bairro B, tb_plano_bairro P', true)
                  -> where('C.id = P.id_cidade')
                  -> where('B.id = P.id_bairro');

            if ( $_SESSION[USERDATA]['id_grupo'] > 2)
                $this -> where('P.id_bairro = B.id');

            if ( !is_null($where) )
    			$this -> where( $where );

			$this -> orderBy('B.bairro ASC');

			return $this;

		}

		//------------------------------------------------------------------------------

		public function registraBairros(int $id = null)
		{

			$this -> cidade_model = new \App\Models\CidadeModel();
			$this -> cidade_model -> registraBairros('tb_plano_bairro', 'id_plano', $id);

		}

	}

}
