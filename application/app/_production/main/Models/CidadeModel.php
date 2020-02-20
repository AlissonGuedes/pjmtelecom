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
		protected $table = 'tb_cidade C';

		/**
		 * A chave primária da tabela.
		 *
		 * @var string
		 */
		protected $primaryKey = 'C.id';

		/**
		 * O formato em que os resultados devem ser
		 * retornados.
		 *
		 * @var string
		 */
		protected $returnType = 'App\Entities\Cidade';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		private $order = array(
			NULL,
			'C.id',
			'C.descricao',
			'C.velocidade_download',
			'C.velocidade_upload',
			'C.wifi_incluso',
			'(SELECT tipo FROM tb_cidade_tipo WHERE tb_cidade_tipo.id = C.id_tipo)',
			'C.valor_real',
			'C.status',
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
			 * @class $this -> cidade = new \App\Entities\Cidade;
			 * Retorna uma instância da Entidade Cidade (\App\Admin\Entities\Cidade)
			 */
			// $this -> cidade = new \App\Entities\Cidade;

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
		public function getCidade($where = false, $fields = '*')
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
					'C.id' => $_POST['search']['value'],
					'C.descricao' => $_POST['search']['value'],
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
		 * Lista todos os planos por cidades
		 * Apenas os que estão cadastrados na tabela `tb_plano`
		 *
		 * @return boolean		true	Caso o registro seja cadastrado normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function getCidadesPorPlano($where = false, $fields = '*')
		{

            // Selecionar todas as cidades/bairros que possuem um plano.
            $this -> select('C.id, C.cidade, C.uf')
                  -> distinct(true)
                  -> from('tb_plano_bairro P', true)
                  -> join('tb_cidade C', 'C.id = P.id_cidade', 'inner')
                  -> join('tb_bairro B', 'B.id = P.id_bairro', 'inner')
                  -> join('tb_plano Pl', 'Pl.id = P.id_plano', 'inner');

            return $this;

		}

		//--------------------------------------------------------------------

		/**
		 * Lista todos os planos por cidades
		 * Apenas os que estão cadastrados na tabela `tb_plano`
		 *
		 * @return boolean		true	Caso o registro seja cadastrado normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function getBairrosPorPlano($where = false, $fields = '*')
		{

    		// Selecionar todas as cidades/bairros que possuem um plano.
            $this -> select('B.id, B.bairro')
                  -> distinct(true)
                  -> from('tb_plano_bairro P', true)
                  -> join('tb_cidade C', 'C.id = P.id_cidade', 'inner')
                  -> join('tb_bairro B', 'B.id = P.id_bairro', 'inner')
                  -> join('tb_plano Pl', 'Pl.id = P.id_plano', 'inner');
            
            if ( $where )
                $this -> where('B.id_cidade', $_POST['cidade']);
                               
            return $this;

		}

		//--------------------------------------------------------------------

	}

}
