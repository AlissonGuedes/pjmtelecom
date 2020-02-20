<?php

namespace App\Entities
{

	class Plano extends AppEntity {

		protected $id = null;
		protected $id_tipo;
		protected $id_usuario = null;
		protected $id_bairro = null;
		protected $id_cidade = null;
		protected $descricao;
		protected $valor_mensal = 0.00;
		protected $taxa_instalacao = 0.00;
		protected $taxa_cancelamento = 0.00;
		protected $tempo_fidelidade = null;
		protected $velocidade_upload = 0.00;
		protected $velocidade_download = 0.00;
		protected $wifi_incluso = 'N';
		protected $cesta_servicos = 'N';
		protected $fibra_optica = 'N';
		protected $nivel = 1.0;
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

		public function setIdUsuario(int $id = null)
		{
			$this -> id_usuario = is_null($id) ? $_SESSION[USERDATA]['id'] : $id;
			return $this;
		}

		public function getIdUsuario()
		{
			return $this -> id_usuario;
		}

		public function setTitulo(string $str)
		{
			$this -> titulo = $str;
			return $this;
		}

		public function getTitulo()
		{
			return $this -> titulo;
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

		public function setValorMensal($float = NULL)
		{
			$this -> valor_mensal = $float;
			return $this;
		}

		public function getValorMensal()
		{
		    return preg_match('/^[0-9]+\.[0-9]+$/', $this -> valor_mensal) ? number_format( $this -> valor_mensal, 2, ',', '.' ) : str_replace(',', '.', str_replace('.', '', $this -> valor_mensal));
		}

		public function setTaxaCancelamento($float = NULL)
		{
			$this -> taxa_cancelamento = $float;
			return $this;
		}

		public function getTaxaCancelamento()
		{
			return preg_match('/^[0-9]+\.[0-9]+$/', $this -> taxa_cancelamento) ? number_format( $this -> taxa_cancelamento, 2, ',', '.' ) : str_replace(',', '.', str_replace('.', '', $this -> taxa_cancelamento));
		}

		public function setTaxaInstalacao($float = NULL)
		{

			$this -> taxa_instalacao = $float;
			return $this;

		}

		public function getTaxaInstalacao()
		{
			return preg_match('/^[0-9]+\.[0-9]+$/', $this -> taxa_instalacao) ? number_format( $this -> taxa_instalacao, 2, ',', '.' ) : str_replace(',', '.', str_replace('.', '', $this -> taxa_instalacao));
		}

		public function setTempoFidelidade(string $str = null)
		{

			if ( ! empty($str) )
			{
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d', strtotime($str));
				$this -> tempo_fidelidade = $str;
			}

			return $this;

		}

		public function getTempoFidelidade(string $format = 'Y-m-d')
		{

			if ( ! empty($this -> tempo_fidelidade) )
				return date($format, strtotime($this -> tempo_fidelidade));

			return $this -> tempo_fidelidade;

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
