<?php

namespace App\Validations
{

	class ConfiguracaoValidation {

		public function __construct()
		{
			$this -> configuracao = new \App\Entities\Configuracao();
		}

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

			$fields = array(
				'meta_title',
				'meta_description',
				'meta_keywords',
				'meta_robots',
				'theme_color',
				( ! empty($_FILES['logomarca']) ? 'logomarca' : NULL),
				'language',
				'msg_manutencao',
				'msg_bloqueio_temporario',
				(isset($_POST['version']) && ! empty($_POST['version']) ? 'version' : NULL),
				'texto_apresentacao',
				'facebook',
				'instagram',
				'twitter',
				'youtube',
				'linkedin',
				'gplus',
				'website',
				'telefone',
				'celular',
				'email',
				'manutencao',
				'publicado'
			);
			

			return array_filter($fields);

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

			$rules = array();

			return array_filter($rules);

		}

	}

}
