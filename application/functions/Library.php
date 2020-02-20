<?php

namespace Alisson
{

	/**
	 * Minha própria classe criada para ser utilizada,
	 * por todo o sistema, sempre que necessário uma conexão com o banco de dados,
	 * em conjunto com o arquivo
	 *
	 * 		{\functions\Functions.php}
	 *
	 * onde tem todas as funções que invocam os métodos desta classe.
	 *
	 * Sempre utilizar métodos státicos nas funções para invocar
	 * os métodos da classe, como:
	 *
	 * Library::method_name($optionals_parameters);
	 *
	 * @author	Alisson Guedes
	 * @since	2019-09-17
	 * @since	Release 01
	 * @version	1.0
	 */
	class Library {

		/**
		 * Obtém a conexão com o banco de dados.
		 * Sempre que for necessário, invocar:
		 *
		 *		Library :: getConnect(nome_da_tabela)
		 *
		 * @param $table = nome da tabela na conexão
		 * @return $db -> table(nome_da_tabela)
		 *
		 */
		private static function getConnect($table)
		{

			$db = \Config\Database :: connect((ENVIRONMENT !== 'development' ) ? 'default' : 'tests');
			return $db -> table($table);

		}

		/**
		 * Obtém informações das configurações do site.
		 * Busca as colunas para serem exibidas nas páginas, onde
		 * poderiam ter as mesmas informações, como telefone de contato, e-mail,
		 * nome do autor, etc. em todas em que seria necessário
		 * uma consulta sempre que o usuário abrir uma página diferente.
		 *
		 * Quando invocar a função \functions\Functions::configuracoes(),
		 * é esta função que será retornada.
		 *
		 * @param $column
		 * 		`meta_description`, `meta_title`, `meta_name` [...]
		 * @param $table
		 * 		`tb_configuracao`, `tb_usuario`, entre outras tabelas
		 *
		 * @return $column
		 */
		public static function configuracoes($column, $table = 'tb_configuracao', $where = array())
		{

			$db = Library :: getConnect($table);

			$db -> select($column);
			$db -> limit(1);

			if ( ! empty($where) )
			{
				foreach ( $where as $ind => $val )
					$db -> where($ind, $val);
			}

			return $db -> get()
			/*		*/ -> getRow()
			/*		*/ -> $column;
		}

	}

}
