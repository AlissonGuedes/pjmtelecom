<?php

namespace App\Models
{

	use CodeIgniter\Model;

	class AppModel extends Model {

		/**
		 * Especificar a classe de entidade do
		 * banco de dados correspondente ao Model.
		 *
		 * @var array
		 */
		protected $entity;
		protected $validator;
		protected $insertId = 0;

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order;

		/**
		 * Um array de nomes de campos que podem ser
		 * alterados pelo usuário em inserts/updates.
		 *
		 * @var array
		 */

		protected $allowedFields = array();

		/**
		 * Regras usadas para validar um dado nos métodos
		 * insert, update e save.
		 * O array deve conter o formato de dados passado
		 * para a biblioteca de validação.
		 *
		 * @var array
		 */
		protected $validationRules = array();

		public function __construct($class_name = null)
		{

			parent :: __construct();

			$this -> request = \Config\Services :: request();

			if ( $this -> returnType )
				$this -> entity = new $this -> returnType();

			if ( $this -> formValidation )
			{
				$this -> validator = new $this -> formValidation();
				$this -> allowedFields = $this -> validator -> getAllowedFields();
				$this -> validationRules = $this -> validator -> getValidationRules();
			}

		}

		/**
		 * Retorna todos os registros como objetos
		 * [Utilizar: foreach]
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
		 * Retorna todos os registros como objetos
		 * [Utilizar: foreach]
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
		 * Retorna todos os registros como array [Utilizar:
		 * foreach]
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

		/**
		 * @name Get Query
		 *
		 * Este método simplesmente retorna a consulta SQL como uma
		 * string.
		 *
		 * @param	string	$query	=	select|insert|update|delete
		 * 			int		$id		=	cláusula where
		 *			mixed	$data	=	campos para alteração
		 * 			boolean	$reset	=	$reset	TRUE: redefinir valores QB
		 * 										FALSE: manter os valores QB
		 * @return string
		 */
		public function getQuery(string $query = 'select', $data = null, $id = null, bool $bool = false)
		{

			$builder = $this -> builder();

			if ( $query == 'select' )
			{

				$query = 'getCompiledSelect';

			}
			else
			{

				$query = 'getCompiled' . ucfirst($query);

				if ( is_object($data) && ! $data instanceof stdClass )
					$data = static :: classToArray($data, $this -> primaryKey, $this -> dateFormat);

				if ( is_object($data) )
					$data = (array)$data;

				$data = $this -> doProtectFields($data);

				if ( $this -> useTimestamps && ! empty($this -> updatedField) && ! array_key_exists($this -> updatedField, $data) )
					$data[$this -> updatedField] = $this -> setDate();

			}

			if ( $id )
			{
				if ( is_array($id) )
					$builder -> set($data) -> whereIn($this -> table . '.' . $this -> primaryKey, $id);
				else
					$builder -> set($data) -> where($this -> table . '.' . $this -> primaryKey, $id);
			}

			exit($builder -> $query($bool));

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna mensagem de validação personalizada do
		 * usuário em caso de erro
		 *
		 * @return int Model::countAllResults()
		 */
		public function error($field, string $message = null)
		{

			$this -> validation -> setError($field, $message);
			return $this -> validation -> getError();

		}

		//--------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return boolean		true	Caso o registro seja cadastrado
		 * normalmente
		 * 						false	Caso haja algum erro ao cadastrar
		 */
		public function create(bool $debug = FALSE)
		{

			$builder = $this -> builder();

			if ( $this -> validate($_POST) === FALSE )
				return FALSE;

			if ( isset($_FILES) )
				$this -> entity -> fill($_FILES);

			$this -> entity -> fill($_POST);

			if ( $debug )
				$this -> getQuery('insert', $this -> entity);

			$insertId = $this -> insert($this -> entity);

			$this -> setLastId($insertId);

			if ( $this -> affectedRows() )
				return $insertId;
			else
				return false;

		}

		public function setLastId($id)
		{
			$this -> insertId = $id;
		}

		public function getLastId()
		{
			return $this -> insertId;
		}

		//--------------------------------------------------------------------

		/**
		 * Editar registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja editado
		 * normalmente
		 * 						false	Caso haja algum erro ao remover
		 */
		public function edit(bool $debug = FALSE)
		{
			if ( $this -> validate($_POST) === FALSE )
				return FALSE;

			if ( isset($_FILES) )
				$this -> entity -> fill($_FILES);

			$this -> entity -> fill($_POST);

			if ( $debug )
				$this -> getQuery('update', $this -> entity, $this -> entity -> getId());

			$this -> update(['id' => $this -> entity -> getId()], $this -> entity);

			// if ( $this -> affectedRows() )
			return true;
			// else
			// return false;

		}

		//--------------------------------------------------------------------

		/**
		 * Remove registros na tabela
		 *
		 * @return boolean		true	Caso o registro seja excluído
		 * normalmente
		 * 						false	Caso haja algum erro ao remover
		 */
		public function remove($fields, bool $debug = FALSE)
		{

			if ( $this -> validate($_POST) === FALSE )
				return FALSE;

			if ( $debug )
				$this -> getQuery('delete', $this -> entity, $this -> entity -> getId());

			$this -> delete($fields);

			if ( $this -> affectedRows() )
				return true;
			else
				return false;
		}

		//--------------------------------------------------------------------

	}

}
