<?php

namespace App\Validations
{

	class EmpresaValidation {

		public function __construct()
		{
			$this -> empresa = new \App\Entities\Empresa();
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
				'id_grupo',
				'nome',
				'email',
				'login',
				( ! empty($_POST['senha']) ? 'senha' : NULL),
				'hide_menu',
				'status'
			);

			return $fields;

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

			if ( isset($_POST['acao']) && $_POST['acao'] === 'entrar' )
				return array(
					'login' => array(
						'trim',
						'required',
						'min_length[5]',
						'max_length[255]'
					),
					'senha' => array(
						'trim',
						'required',
						'min_length[5]',
						'max_length[255]'
					),
				);

			if ( ! isset($_POST['_method']) )
				return array();

			$rules = array(
				'tipo' => array(
					'trim',
					'required'
				),
				'nome' => array(
					'trim',
					'required',
					'min_length[5]',
					'max_length[255]'
				),
				'email' => array(
					'trim',
					'required',
					'min_length[5]',
					'max_length[255]',
					'is_unique[tb_usuario.email,id,' . (isset($_POST['id']) ? $_POST['id'] : NULL) . ']'
				),
				'login' => array(
					'trim',
					'required',
					'min_length[5]',
					'max_length[255]',
					'is_unique[tb_usuario.login,id,' . (isset($_POST['id']) ? $_POST['id'] : NULL) . ']'
				),
			);

			return $rules;

		}

	}

}
