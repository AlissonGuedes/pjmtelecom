<?php

namespace App\Entities
{

	class User extends AppEntity {

		/*
		 * Colunas
		 */
		protected $id = null;
		protected $id_grupo;
		protected $id_gestor = null;
		protected $nome;
		protected $email;
		protected $login;
		protected $senha;
		protected $salt;
		protected $ultimo_login;
		protected $primeiro_login = '1';
		protected $listar = 'N';
		protected $editar = 'N';
		protected $inserir = 'N';
		protected $excluir = 'N';
		protected $hide_menu = 'N';
		protected $data_cadastro = null;
		protected $status = '0';

		protected $datamap = array(
		    'tipo' => 'id_grupo',
		    'gestor' => 'id_gestor'
        );

		private $datalogin;

		public function setId($id = null)
		{
			$this -> id = $id;
			return $this;
		}

		public function getId()
		{
			return $this -> id;
		}

		public function setIdGrupo(int $int = null)
		{
			$this -> id_grupo = $int;
			return $this;
		}

		public function getIdGrupo()
		{
			return $this -> id_grupo;
		}

		public function getNomeGrupo(int $id)
		{

			$grupo = new \App\Models\UserModel();
			$grupo = $grupo -> getGrupo(['id' => $id]) -> getRow();
			return $grupo -> grupo;
		}

		public function setIdGestor(int $int = null)
		{
			$this -> id_gestor = $int;
			return $this;
		}

		public function getIdGestor()
		{
			return $this -> id_gestor;
		}

		public function getNomeGestor(int $id = null)
		{
			if ( is_null($id) )
				return null;

			$gestor = new \App\Models\UserModel;
			$gestor = $gestor -> getGestor(['tb_usuario.id' => $id]) -> getRow();
			return $gestor -> nome;
		}

		// public function setIdBairro(int $int = null)
		// {
		// $this -> id_bairro = $int;
		// return $this;
		// }
		//
		// public function getIdBairro()
		// {
		// return $this -> id_bairro;
		// }
		//
		// public function setIdCidade($int = null)
		// {
		// $this -> id_cidade = $int;
		// return $this;
		// }
		//
		// public function getIdCidade()
		// {
		// return $this -> id_cidade;
		// }

		public function setNome(string $str = null)
		{
			$this -> nome = $str;
			return $this;
		}

		public function getNome()
		{
			return $this -> nome;
		}

		public function setEmail(string $str = null)
		{
			$this -> email = $str;
			return $this;
		}

		public function getEmail()
		{
			return $this -> email;
		}

		public function setLogin(string $str = null)
		{
			$this -> login = $str;
			return $this;
		}

		public function getLogin()
		{
			return $this -> login;
		}

		public function setSenha(string $str = null)
		{

			$this -> senha = $str;
			return $this;

		}

		public function getSenha(bool $crypt = true)
		{

			if ( empty($this -> senha) )
				return 'Sua senha não foi alterada!';

			if ( ! $crypt )
				return $this -> senha;

			return hashCode($this -> senha);

		}

		public function setSalt(string $str = null)
		{
			$this -> salt = $str;
			return $this;
		}

		public function getSalt()
		{
			return $this -> salt;
		}

		public function setPrimeiroLogin(string $str = null)
		{

			if ( ! empty($str) )
			{
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d H:i:s', strtotime($str));
				$this -> primeiro_login = $str;
			}

			return $this;

		}

		public function getPrimeiroLogin(string $format = 'Y-m-d H:i:s')
		{

			if ( ! empty($this -> primeiro_login) )
				return date($format, strtotime($this -> primeiro_login));

			return $this -> primeiro_login;

		}

		public function setUltimoLogin(string $str = null)
		{

			if ( ! is_null($str) )
				$this -> ultimo_login = $str;
			else
				return $this -> ultimo_login = null;

			$this -> datalogin = new \DateTime($this -> ultimo_login);

			return $this;

		}

		public function getUltimoLogin(string $format = 'Y-m-d H:i:s')
		{
			if ( ! empty($this -> ultimo_login) )
			{
				return $this -> datalogin -> format($format);
			}
		}

		public function setListar(string $str)
		{
			$this -> listar = $str;
			return $this;
		}

		public function getListar()
		{
			return $this -> listar;
		}

		public function setEditar(string $str)
		{
			$this -> editar = $str;
			return $this;
		}

		public function getEditar()
		{
			return $this -> editar;
		}

		public function setInserir(string $str)
		{
			$this -> inserir = $str;
			return $this;
		}

		public function getInserir()
		{
			return $this -> inserir;
		}

		public function setExcluir(string $str)
		{
			$this -> excluir = $str;
			return $this;
		}

		public function getExcluir()
		{
			return $this -> excluir;
		}

		public function setDataCadastro(string $str = null)
		{

			if ( ! empty($str) )
			{
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d H:i:s', strtotime($str));
				$this -> data_cadastro = $str;
			}

			return $this;

		}

		public function getDataCadastro(string $format = 'Y-m-d H:i:s')
		{

			if ( ! empty($this -> data_cadastro) )
				return date($format, strtotime($this -> data_cadastro));

			return $this -> data_cadastro;

		}

		public function setHideMenu(string $str)
		{
			$this -> hide_menu = $str;
			return $this;
		}

		public function getHideMenu()
		{
			return $this -> hide_menu;
		}

		public function setStatus(string $str)
		{
			$this -> status = $str;
			return $this;
		}

		public function getStatus()
		{
			return $this -> status;
		}

	}

}
