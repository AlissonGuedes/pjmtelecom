<?php

namespace App\Entities
{

	class Comunicado extends AppEntity {

		/*
		 * Colunas
		 */
		protected $id = null;
		protected $id_autor;
		protected $autor;
		protected $titulo;
		protected $descricao;
		protected $datahora_cadastro = 'now';
		protected $datahora_agendamento = null;
		protected $status = '0';

		protected $datamap = array(
			'data_cadastro' => 'datahora_cadastro',
			'data_agendamento' => 'datahora_agendamento'
		);

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

		public function setTitulo(string $str = NULL)
		{
			$this -> titulo = $str;
			return $this;
		}

		public function getTitulo()
		{
			return $this -> titulo;
		}

		public function setDescricao(string $str = null)
		{
			$this -> descricao = $str;
			return $this;
		}

		public function getDescricao()
		{
			return $this -> descricao;
		}

		public function setDataHoraAgendamento(string $str = null)
		{

			$str = str_replace('/', '-', $str);

			$this -> datahora_agendamento = $str;

			$this -> agendamento = new \DateTime($this -> datahora_agendamento);

			return $this;

		}

		public function getDataHoraAgendamento(string $format = 'Y-m-d H:i:s')
		{

			if ( ! empty($this -> datahora_agendamento) )
			{
				return $this -> agendamento -> format($format);
			}

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

		public function setIdBairro(int $int = null)
		{
			$this -> id_bairro = $int;
			return $this;
		}

		public function getIdBairro()
		{
			return $this -> id_bairro;
		}

		public function setIdCidade(int $int = null)
		{
			$this -> id_cidade = $int;
			return $this;
		}

		public function getIdCidade()
		{
			return $this -> id_cidade;
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
