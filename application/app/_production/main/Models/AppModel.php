<?php
namespace App\Models
{

	use CodeIgniter\Model;

	class AppModel extends Model {

		public function __construct()
		{

			parent :: __construct();

			$this -> request = \Config\Services :: request();

		}

		/**
		 * Retorna todos os registros como objetos [Utilizar: foreach]
		 *
		 * @param bool $debug Exibir a query compilada
		 *
		 * @return array Model::getResult()
		 */
		public function getAll($debug = FALSE)
		{

			if ( $debug )
				exit($this -> getCompiledSelect());

			return $this -> get()
			/*		  */ -> getResult();

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna todos os registros como objetos [Utilizar: foreach]
		 * Similar à função getAll
		 *
		 * @param bool $debug Exibir a query compilada
		 *
		 * @return array Model::getResult()
		 */
		public function result($debug = FALSE)
		{

			if ( $debug )
				exit($this -> getCompiledSelect());

			return $this -> get()
			/*		  */ -> getResult();

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna todos os registros como array [Utilizar: foreach]
		 *
		 * @param bool $debug Exibir a query compilada
		 *
		 * @return array Model::getResultArray()
		 */
		public function resultArray($debug = FALSE)
		{

			if ( $debug )
				exit($this -> getCompiledSelect());

			return $this -> get()
			/*		  */ -> getResultArray();

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna os resultados como objetos
		 *
		 * @param bool $debug Exibir a query compilada
		 *
		 * @return array Model::getRowObject()
		 */
		public function getRow($debug = FALSE)
		{

			if ( $debug )
				exit($this -> getCompiledSelect());

			return $this -> get()
			/*		  */ -> getRowObject();

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna os resultados como array
		 *
		 * @param bool $debug Exibir a query compilada
		 *
		 * @return array Model::getRowArray()
		 */
		public function getRowArray($debug = FALSE)
		{

			if ( $debug )
				exit($this -> getCompiledSelect());

			return $this -> get()
			/*		  */ -> getRowArray();

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna o total de registros na consulta
		 *
		 * @return int Model::countAllResults()
		 */
		public function numRows()
		{

			return $this -> countAllResults(false);

		}

		//--------------------------------------------------------------------

		public function getQuery($query = 'select')
		{
			$query = 'getCompiled' . ucfirst($query);
			exit($this -> $query());
		}

		//--------------------------------------------------------------------

		/**
		 * Retorna mensagem de validação personalizada do usuário em caso de erro
		 *
		 * @return int Model::countAllResults()
		 */
		public function error(string $field, $message)
		{
			$this -> validation -> setError($field, $message);
			return $this -> validation -> getError();

		}

	}

}
