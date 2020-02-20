<?php

namespace App\Entities
{

	use CodeIgniter\Entity;

	class AppEntity extends Entity {

		protected $validationRules = [];
		protected $allowedFields = [];
		protected $order = [];

		public function __construct($data = null)
		{

			$this -> request = \Config\Services :: request();

			if ( ! is_null($data) )
				$this -> fill($data);

		}

		public function fill($data = null)
		{

			if ( ! empty($data) )
				$data = (array)$data;
			else
				$data = $_POST;

			$attributes = array();
			$vars = get_class_vars(get_class($this));

			// Obter os dados vindos do formulário
			foreach ( $data as $key => $val )
			{

				$k = $this -> mapProperty($key);

				$set = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $k)));
				$get = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $k)));

				if ( method_exists($this, $set) && ! is_null($set) )
				{

					if ( ! is_array($val) )
					{
						$this -> $set($data[$key]);
						$attributes[$k] = $this -> $get();
					}
					else
					{
						if ( isset($_POST[$key]) )
						{
							$this -> $set($val);
							$attributes[$k] = $this -> $get();
						}
						elseif ( isset($_FILES[$key]) )
						{
							$this -> $set($data);
							$attributes[$k] = $this -> $get();
						}
					}

				}

			}

			// Obter todos os atributos da classe para executar todos os
			// métodos
			// mesmo que não sejam alteradas via formulário as variáveis
			foreach ( $vars as $key => $val )
			{

				$set = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
				$get = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

				if ( method_exists($this, $set) && method_exists($this, $get) && ! in_array($key, array_keys($attributes)) )
				{

					$this -> $set($val);
					$attributes[$key] = $this -> $get();

				}

			}

			// Atribuir ao atributo $this->attributes, herdado da class
			// Entity,
			// Todas as variáveis da classe filha.
			foreach ( $attributes as $key => $val )
			{
				$key = $this -> mapProperty($key);
				$this -> attributes[$key] = $val;
			}

			return $this;

		}

		public function getAllowedFields()
		{
			return $this -> allowedFields;
		}

		public function getValidationRules()
		{
			return $this -> validationRules;
		}

		public function getOrder()
		{
			return $this -> order;
		}

	}

}
