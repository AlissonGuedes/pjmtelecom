<?php

/**
 * Exemplo para criar uma entidade do banco de dados
 *
 * Define a classe de entidade para ser um espelho da tabela no banco de dados.
 *
 * No arquivo onde será utilizado, invocar:
 *  $config = new \App\Entities\Configuracoes();
 */
namespace App\Entities
{

	use CodeIgniter\Entity;

	class Configuracoes extends Entity {

		protected $table = 'tb_configuracoes';
		protected $allowFields = [];

		public $returnTypes = 'App\Entities\Configuracoes';
		protected $userTimesTamps = true;

		public function setNome($nome)
		{
			$this -> nome = $nome;
		}

		public function getNome()
		{
			return $this -> nome;
		}

	}

}
