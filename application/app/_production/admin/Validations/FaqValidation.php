<?php

namespace App\Validations
{

	class FaqValidation {

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
				($_POST['autor'] === $_SESSION[USERDATA]['id'] ? 'id_autor' : NULL),
				'titulo',
				'descricao',
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

			return array(
				'titulo' => 'trim|required',
				'descricao' => 'trim|required',
				'status' => 'trim'
			);

		}

	}

}
