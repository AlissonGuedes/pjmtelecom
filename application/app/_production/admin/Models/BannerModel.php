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
		protected $table = 'tb_banner';

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
		protected $returnType = 'App\Entities\Banner';

		/**
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = 'App\Validations\BannerValidation';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order = array(
			NULL,
			'tb_banner.id',
			'tb_banner.imagem',
			'tb_banner.titulo',
			'(SELECT cidade FROM tb_cidade WHERE tb_banner_bairro.id_cidade = tb_cidade.id)',
			'(SELECT bairro FROM tb_bairro WHERE tb_banner_bairro.id_bairro = tb_bairro.id)',
			'(SELECT nome FROM tb_usuario WHERE tb_usuario.id = tb_banner.id_autor)',
			'tb_banner.data_add',
			'tb_banner.status',
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
		public function getBanner($where = false, $fields = '*')
		{
			// Select '*'
			$fields = array(
				'tb_banner.id',
				'tb_banner.id_autor',
				'tb_banner.titulo',
				'tb_banner.alias',
				'tb_banner.descricao',
				'tb_banner.clicks',
				'tb_banner.url',
				'tb_banner.imagem',
				'tb_banner.imgsize',
				'tb_banner.data_add',
				'tb_banner.ordem',
				'tb_banner.status',
				'tb_usuario.nome AS autor'
			);

			$this -> select($fields);

			// Where
			if ( $where )
				$this -> where($where);

			/*
			 * Adicionar outras condições...
			 */

			if ( $_SESSION[USERDATA]['id_grupo'] >= 2 )
			{
				$this -> where('id_autor', $_SESSION[USERDATA]['id']);
			}

			$this -> join('tb_usuario', 'tb_usuario.id = tb_banner.id_autor', 'left');

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					'tb_banner.id' => $_POST['search']['value'],
					'tb_banner.titulo' => $_POST['search']['value'],
					'tb_banner.descricao' => $_POST['search']['value'],
					'tb_usuario.nome' => $_POST['search']['value']
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

		public function getBannerBairro($where = null)
		{

			$fields = array(
				'P.id_banner',
				'P.id_bairro',
				'P.id_cidade'
			);
			
			$this -> select($fields);

            $this -> from('tb_cidade C, tb_bairro B, tb_banner_bairro P', true)
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
			$this -> cidade_model -> registraBairros('tb_banner_bairro', 'id_banner', $id);

		}

		//------------------------------------------------------------------------------

	}

}
