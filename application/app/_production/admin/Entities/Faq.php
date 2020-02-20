<?php

namespace App\Entities
{

	class Faq extends AppEntity {

		protected $id = null;
		protected $id_autor;
		protected $autor;
		protected $titulo;
		protected $descricao;
		protected $datahora_cadastro;
		protected $status = '0';

		protected $datamap = array();

		private $cadastro;
		private $agendamento;

		public function setId($id = null)
		{
			$this -> id = $id;
			return $this;
		}

		public function getId()
		{
			return $this -> id;
		}

		public function setIdAutor($id = null) {
			$this -> id_autor = ! is_null($id) ? $id : $_SESSION[USERDATA]['id'];
			return $this;
		}

		public function getIdAutor(){
			return $this -> id_autor;
		}

		public function setAutor($id = null)
		{
			$this -> autor = $id;
			return $this;
		}
		
		public function getAutor()
		{
			return $this -> autor;
		}

		public function getNomeAutor(int $id)
		{
			$user = new \App\Models\FaqModel;
			$autor = $user -> getAutor(['tb_usuario.id' => $id]) -> getRow();
			return $autor -> nome;
		}

		public function setTitulo(string $str = null)
		{
			$this -> titulo = $str;
			return $this;
		}

		public function getTitulo()
		{
			return $this -> titulo;
		}

		public function setDescricao(string $str)
		{
			$this -> descricao = $str;
			return $this;
		}

		public function getDescricao()
		{
			return $this -> descricao;
		}

		public function setDataHoraCadastro(string $str = null)
		{

			$this -> datahora_cadastro = $str;

			return $this -> cadastro = new \DateTime($this -> datahora_cadastro);

		}

		public function getDataHoraCadastro(string $format = 'Y-m-d H:i:s')
		{
			if ( ! empty($this -> datahora_cadastro) )
			{
				return $this -> cadastro -> format($format);
			}
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
