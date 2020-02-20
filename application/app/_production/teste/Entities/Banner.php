<?php

namespace App\Entities
{

	class Banner extends AppEntity {

		protected $id = null;
		protected $id_tipo;
		protected $id_bairro = null;
		protected $id_cidade = null;
		protected $descricao;
		protected $velocidade_upload = 0.00;
		protected $velocidade_download = 0.00;
		protected $wifi_incluso = 'N';
		protected $cesta_servicos = 'N';
		protected $fibra_optica = 'N';
		protected $nivel = 1.0;
		protected $valor_real = 0.00;
		protected $valor_prom = 0.00;
		protected $data_validade = null;
		protected $data_validade_promocao = null;
		protected $status = '0';

		protected $datamap = array(
			'tipo' => 'id_tipo',
			'bairro' => 'id_bairro',
			'cidade' => 'id_cidade',
		);

		public function setId($id = null)
		{
			$this -> id = $id;
			return $this;
		}

		public function getId()
		{
			return $this -> id;
		}

		public function setIdTipo(int $int = null)
		{
			$this -> id_tipo = $int;
			return $this;
		}

		public function getIdTipo()
		{
			return $this -> id_tipo;
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

		public function setDescricao(string $str)
		{
			$this -> descricao = $str;
			return $this;
		}

		public function getDescricao()
		{
			return $this -> descricao;
		}

		public function setWifiIncluso(string $str)
		{
			$this -> wifi_incluso = $str;
			return $this;
		}

		public function getWifiIncluso()
		{
			return $this -> wifi_incluso;
		}

		public function setCestaServicos(string $str)
		{
			$this -> cesta_servicos = $str;
			return $this;
		}

		public function getCestaServicos()
		{
			return $this -> cesta_servicos;
		}

		public function setFibraOptica(string $str)
		{
			$this -> fibra_optica = $str;
			return $this;
		}

		public function getFibraOptica()
		{
			return $this -> fibra_optica;
		}

		public function setVelocidadeUpload(int $num)
		{
			$this -> velocidade_upload = $num;
			return $this;
		}

		public function getVelocidadeUpload()
		{
			return $this -> velocidade_upload;
		}

		public function setVelocidadeDownload(int $num)
		{
			$this -> velocidade_download = $num;
			return $this;
		}

		public function getVelocidadeDownload()
		{
			return $this -> velocidade_download;
		}

		public function setNivel(float $int)
		{
			$this -> nivel = $int;
			return $this;
		}

		public function getNivel()
		{
			return $this -> nivel;
		}

		public function setValorReal(float $int)
		{
			$this -> valor_real = $int;
			return $this;
		}

		public function getValorReal()
		{
			return $this -> valor_real;
		}

		public function setValorProm(float $int)
		{
			$this -> valor_prom = $int;
			return $this;
		}

		public function getValorProm()
		{
			return $this -> valor_prom;
		}

		public function setDataValidade(string $str = null)
		{

			if ( ! empty($str) )
			{
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d', strtotime($str));
				$this -> data_validade = $str;
			}

			return $this;

		}

		public function getDataValidade(string $format = 'Y-m-d')
		{

			if ( ! empty($this -> data_validade) )
				return date($format, strtotime($this -> data_validade));

			return $this -> data_validade;

		}

		public function setDataValidadePromocao(string $str = null)
		{

			if ( ! empty($str) )
			{
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d', strtotime($str));
				$this -> data_validade_promocao = $str;
			}

			return $this;

		}

		public function getDataValidadePromocao(string $format = 'Y-m-d')
		{

			if ( ! empty($this -> data_validade_promocao) )
				return date($format, strtotime($this -> data_validade_promocao));

			return $this -> data_validade_promocao;

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
