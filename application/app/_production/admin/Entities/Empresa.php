<?php

namespace App\Entities
{

	class Empresa extends AppEntity {

		/*
		 * Colunas
		 */
		protected $id = null;
		protected $cpnj = '00.000.000/0000-00';
		protected $inscricao_estadual = '00000000000';
		protected $inscricao_municipal = '00000000000';
		protected $razao_social = null;
		protected $nome_fantasia = null;
		protected $cep = null;
		protected $endereco = null;
		protected $numero = null;
		protected $bairro = null;
		protected $complemento = null;
		protected $cidade = null;
		protected $uf = null;
		protected $descricao = null;
		protected $telefone = null;
		protected $email = null;
		protected $aliquota_imposto = 0.00;
		protected $tributacao = null;
		protected $certificado = null;
		protected $senha_certificado = null;
		protected $ambiente = null;
		protected $sequence_nfe = null;
		protected $sequence_nfce = null;
		protected $serie_nfe = null;
		protected $tokencsc = null;
		protected $csc = null;
		protected $matriz = null;
		protected $data_cadastro = 'now';
		protected $status = '0';

		protected $datamap = array();

		private $datetime;

		public function setId($id = null)
		{
			$this -> id = $id;
			return $this;
		}

		public function getId()
		{
			return $this -> id;
		}

		public function setCnpj(string $str = null)
		{
			$this -> cnpj = $str;
			return $this;
		}

		public function getCnpj()
		{
			return $this -> cnpj;
		}

		public function setInscricaoEstadual(string $str = null)
		{
			$this -> inscricao_estadual = $str;
			return $this;
		}

		public function getInscricaoEstadual()
		{
			return $this -> inscricao_estadual;
		}

		public function setInscricaoMunicipal(string $str = null)
		{
			$this -> inscricao_municipal = $str;
			return $this;
		}

		public function getInscricaoMunicipal()
		{
			return $this -> inscricao_municipal;
		}

		public function setRazaoSocial(string $str = null)
		{
			$this -> razao_social = $str;
			return $this;
		}

		public function getRazaoSocial()
		{
			return $this -> razao_social;
		}

		public function setNomeFantasia(string $str = null)
		{
			$this -> nome_fantasia = $str;
			return $this;
		}

		public function getNomeFantasia()
		{
			return $this -> nome_fantasia;
		}

		public function setCep(string $str = null)
		{
			$this -> cep = $str;
			return $this;
		}

		public function getCep()
		{
			return $this -> cep;
		}

		public function setBairro(string $str = null)
		{
			$this -> bairro = $str;
			return $this;
		}

		public function getBairro()
		{
			return $this -> bairro;
		}

		public function setComplemento(string $str = null)
		{
			$this -> complemento = $str;
			return $this;
		}

		public function getComplemento()
		{
			return $this -> complemento;
		}

		public function setCidade(string $str = null)
		{
			$this -> cidade = $str;
			return $this;
		}

		public function getCidade()
		{
			return $this -> cidade;
		}

		public function setUf(string $str = null)
		{
			$this -> uf = $str;
			return $this;
		}

		public function getUf()
		{
			return $this -> uf;
		}

		public function setTelefone(string $str = null)
		{
			$this -> telefone = $str;
			return $this;
		}

		public function getTelefone()
		{
			return $this -> telefone;
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

		public function setAliquotaImposto(float $num = 0.00)
		{
			$this -> aliquota_imposto = $num;
			return $this;
		}

		public function getAliquotaImposto()
		{
			return $this -> aliquota_imposto;
		}

		public function setTributacao(string $str = null)
		{
			$this -> tributacao = $str;
			return $this;
		}

		public function getTributacao()
		{
			return $this -> tributacao;
		}

		public function setCertificado(string $str = null)
		{
			$this -> certificado = $str;
			return $this;
		}

		public function getCertificado()
		{
			return $this -> certificado;
		}

		public function setSenhaCertificado(string $str = null)
		{
			$this -> senha_certificado = $str;
			return $this;
		}

		public function getSenha(bool $crypt = true)
		{

			if ( empty($this -> senha_certificado) )
				return null;

			if ( ! $crypt )
				return $this -> senha_certificado;

			return hashCode($this -> senha_certificado);

		}

		public function setAmbiente(string $str = null)
		{
			$this -> ambiente = $str;
			return $this;
		}

		public function getAmbiente()
		{
			return $this -> ambiente;
		}

		public function setSequenceNfe(int $int = null)
		{
			$this -> sequence_nfe = $int;
			return $this;
		}

		public function getSequenceNfe()
		{
			return $this -> sequence_nfe;
		}

		public function setSequenceNfce(int $int = null)
		{
			$this -> sequence_nfce = $int;
			return $this;
		}

		public function getSequenceNfce()
		{
			return $this -> sequence_nfce;
		}

		public function setSerieNfe(int $int = null)
		{
			$this -> serie_nfe = $int;
			return $this;
		}

		public function getSerieNfe()
		{
			return $this -> serie_nfe;
		}

		public function setSerieNfce(int $int = null)
		{
			$this -> serie_nfce = $int;
			return $this;
		}

		public function getSerieNfce()
		{
			return $this -> serie_nfce;
		}

		public function setTokenCsc(string $int = null)
		{
			$this -> tokencsc = $int;
			return $this;
		}

		public function getTokenCsc()
		{
			return $this -> tokencsc;
		}

		public function setCsc(string $int = null)
		{
			$this -> csc = $int;
			return $this;
		}

		public function getCsc()
		{
			return $this -> csc;
		}

		public function setMatriz(string $str = null)
		{
			$this -> matriz = $str;
			return $this;
		}

		public function getMatriz()
		{
			return $this -> matriz;
		}

		public function setDataCadastro(string $str = null)
		{

			if ( ! is_null($str) )
				$this -> data_cadastro = $str;
			else
				return $this -> data_cadastro = null;

			$this -> datetime = new \DateTime($this -> data_cadastro);

			return $this;

		}

		public function getDataCadastro(string $format = 'Y-m-d H:i:s')
		{
			if ( ! empty($this -> data_cadastro) )
			{
				return $this -> datetime -> format($format);
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
