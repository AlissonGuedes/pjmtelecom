<?php

namespace App\Validations
{

	class PlanoValidation extends RulesValidation {

		/**
		 * Um array de nomes de campos que podem ser
		 * alterados pelo usuário em inserts/updates.
		 *
		 * @var array
		 */
		public function getAllowedFields()
		{

			if ( ! isset($_POST['_method']) )
				return array();

			return array(
				'id_tipo',
				'id_usuario',
				'titulo',
				'descricao',
				'valor_mensal',
				// 'taxa_instalacao',
				// 'taxa_cancelamento',
				'tempo_fidelidade',
				'velocidade_upload',
				'velocidade_download',
				'wifi_incluso',
				'cesta_servicos',
				'fibra_optica',
				'nivel',
				'status'
			);

		}

		/**
		 * Regras usadas para validar um dado nos métodos
		 * insert, update e save.
		 * O array deve conter o formato de dados passado
		 * para a biblioteca de validação.
		 *
		 * @var array
		 */
		public function getValidationRules()
		{

			if ( ! isset($_POST['_method']) )
				return array();

			$image_rules = 'trim';

			if ( $_POST['_method'] === 'post' )
				$image_rules = 'required|max_size[imagem,1024]';
			elseif ( ! empty($_FILES['imagem']) )
				$image_rules = 'max_size[imagem,1024]';

			return array(
				'tipo' => 'trim|required',
				'bairro' => 'trim|required',
				'cidade' => 'trim|required',
				'titulo' => 'trim|required',
				'descricao' => 'trim|required',
				'velocidade_upload' => 'trim|required',
				'velocidade_download' => 'trim|required',
				'wifi_incluso' => 'trim',
				'cesta_servicos' => 'trim',
				'fibra_optica' => 'trim',
				'nivel' => 'trim',
				'valor_mensal' => 'trim|required|greater_than[0.00]',
				// 'taxa_instalacao' => 'trim|required|greater_than[0.00]',
				// 'taxa_cancelamento' => 'trim|required|greater_than[0.00]',
				'tempo_fidelidade' => 'trim',
				'status' => 'trim'
			);
		}

		public function getRules()
		{

		}

	}

}
