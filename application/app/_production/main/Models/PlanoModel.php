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
		protected $table = 'tb_plano P';

		/**
		 * A chave primária da tabela.
		 *
		 * @var string
		 */
		protected $primaryKey = 'P.id';

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
			'P.id',
			'P.descricao',
			'P.velocidade_download',
			'P.velocidade_upload',
			'P.wifi_incluso',
			'(SELECT tipo FROM tb_plano_tipo WHERE tb_plano_tipo.id = P.id_tipo)',
			'P.valor_real',
			'P.status',
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
			'bairros',
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
			'bairros' => 'trim|required',
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
			 * @class $this -> plano = new \App\Entities\Plano;
			 * Retorna uma instância da Entidade Plano
			 * (\App\Admin\Entities\Plano)
			 */
			$this -> plano = new \App\Entities\Plano;

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

		public function getPlano($where = false, $fields = '*')
		{
			// Select '*'
			$this -> select($fields);

			$this -> join('tb_plano_tipo T', 'T.id = P.id_tipo', 'left');
			$this -> join('tb_plano_bairro B', 'B.id_plano = P.id', 'left');

			$this -> where('P.status', '1');
			$this -> where('T.status', '1');
			$this -> where('B.id_bairro', $_COOKIE['bairro']);

			if ( $where )
				$this -> where($where);

			return $this;

		}

		//--------------------------------------------------------------------

		/**
		 * Lista os tipos de planos existentes
		 * @method getTipoPlano - `tb_plano_tipo`
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */

		public function getTipoPlano($where = false, $fields = '*')
		{
			// Select '*'
			if ( $fields === '*' )
				$fields = array(
					'T.id',
					'T.tipo',
					'T.sigla',
					'T.status'
				);

			$this -> select($fields);

			$this -> distinct(true);
			$this -> from('tb_plano_tipo T', true);

			$this -> join('tb_plano P', 'P.id_tipo = T.id', 'left');
			$this -> join('tb_plano_bairro B', 'B.id_plano = P.id', 'left');

			$this -> where('T.status', '1');
			$this -> where('B.id_bairro', $_COOKIE['bairro']);

			if ( $where )
				$this -> where($where);

			return $this;

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
		public function getPlanoPorBairro($where = false)
		{

			// Selecionar todas as cidades/bairros que possuem um plano.
			$this -> select('P.id, P.id_tipo, T.tipo, P.valor_real');

			$this -> distinct(true);

			$this -> from('tb_plano_tipo T', true);

			$this -> join('tb_plano P', 'T.id = P.id_tipo', 'left');
			$this -> join('tb_plano_bairro B', 'B.id_plano = P.id', 'left');

			$this -> where('P.status', '1');
			$this -> where('T.status', '1');
			$this -> where('B.id_bairro', $_COOKIE['bairro']);

			if ( $where )
				$this -> where($where);

			return $this;

		}

		//--------------------------------------------------------------------

	}

}
