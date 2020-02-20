<?php

namespace App\Validations
{

	class BannerValidation {

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
				(isset($_POST['autor']) && $_POST['autor'] === $_SESSION[USERDATA]['id'] ? 'id_autor' : NULL),
				'titulo',
				'alias',
				'descricao',
				( ! empty($_FILES['imagem']) ? 'imagem' : NULL),
				($_POST['_method'] === 'post' ? 'data_add' : NULL),
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
				'titulo' => 'trim',
				'imagem' => $image_rules
			);
		}

		public function getRules()
		{

		}

	}

}
