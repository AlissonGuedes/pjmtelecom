<?php
namespace App\Models
{

	/**
	 * Classe ConfiguracaoModel
	 *
	 * @package App
	 */
	class ConfiguracaoModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser
		 * utilizada pela classe.
		 *
		 * @var string
		 */
		protected $table = 'tb_configuracao';

		/**
		 * A chave primária da tabela.
		 *
		 * @var string
		 */
		protected $primaryKey = 'id';

		/**
		 * O formato em que os resultados devem ser
		 * retornados.
		 *
		 * @var string
		 */
		protected $returnType = 'App\Entities\Configuracao';

		/**
		 * Validação para os formulários.
		 *
		 * @var array
		 */
		protected $formValidation = 'App\Validations\ConfiguracaoValidation';

		/**
		 * Especificar por quais colunas da tabela
		 * poderão ser ordenados os dados.
		 *
		 * @var array
		 */
		protected $order = array(
			NULL,
			'tb_configuracao.id',
			'tb_configuracao.nome',
			'tb_configuracao.email',
			'(SELECT grupo FROM tb_grupo WHERE tb_grupo.id = id_grupo)',
			'tb_configuracao.ultimo_login',
			'tb_configuracao.status',
			NULL
		);

		//------------------------------------------------------------------------------

		public function __construct()
		{

			parent :: __construct();

			$this -> user = $this -> entity;

		}

		//------------------------------------------------------------------------------

		/**
		 * Lista registros da tabela
		 *
		 * @param string|array|boolean		$where
		 * @param string|array				$fields
		 *
		 * @return array Model()
		 */
		public function getConfiguracao($where = false, $fields = '*')
		{

			if ( $fields === '*' )
			{
				$fields = array(
					'tb_configuracao.id',
					'id_grupo',
					'nome',
					'email',
					'login',
					'senha',
					'ultimo_login',
					'primeiro_login',
					'listar',
					'editar',
					'excluir',
					'inserir',
					'hide_menu',
					'tb_configuracao.status',
					'grupo',
					'descricao',
					'modulo',
					'nivel',
				);
			}

			// Select '*'
			$this -> select($fields);
			$this -> distinct();

			// From
			$this -> from('tb_configuracao', TRUE);

			/*
			 * Adicionar outras condições...
			 */

			$this -> join('tb_acl_grupo', 'tb_acl_grupo.id = tb_configuracao.id_grupo', 'left');

			if ( $where )
				$this -> where($where);

			if ( isset($_SESSION[USERDATA]) && $_SESSION[USERDATA]['id_grupo'] > 1 )
			{
				$this -> where('id_grupo <> 1');
				$this -> where('id_grupo >= ' . $_SESSION[USERDATA]['id_grupo']);
			}

			// Like
			if ( ! empty($_POST['search']['value']) )
			{

				$or_like = array(
					$this -> table . '.' . 'id' => $_POST['search']['value'],
					'descricao' => $_POST['search']['value'],
				);

				$this -> groupStart();
                $this -> orLike($or_like);
                $this -> groupEnd();

			}

			// Order By
			if ( ! empty($_POST['order']) )
			{
				$orderBy = $this -> order[$_POST['order'][0]['column']];
				$direction = $_POST['order'][0]['dir'];
			}
			else
			{
				$orderBy = $this -> order[2];
				$direction = 'asc';
			}

			if ( ! is_null($orderBy) )
				$this -> orderBy($orderBy, $direction);

			// Limit
			$limit = isset($_POST['length']) ? $_POST['length'] : NULL;

			if ( ! is_null($limit) )
				$this -> limit($limit);

			// Offset
			$start = isset($_POST['start']) ? $_POST['start'] : NULL;

			if ( ! is_null($start) )
				$this -> offset($start);

			return $this;

		}

		//------------------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return query
		 */
		public function getCidadeConfiguracao($where = false, $fields = '*')
		{

			$fields = array(
				'C.id',
				'C.cidade'
			);

			$this -> from('tb_configuracao_cidade U', true);
			$this -> distinct(true);
			$this -> select($fields);
			$this -> join('tb_cidade C', 'C.id = U.id_cidade', 'left');
			$this -> where('U.id_configuracao', $_SESSION[USERDATA]['id']);

			return $this;

		}

		//------------------------------------------------------------------------------

		/**
		 * Cadastra novo registro na tabela
		 *
		 * @return query
		 */
		public function getBairroConfiguracao($where = false, $fields = '*')
		{

			$fields = array(
				'B.id',
				'B.bairro'
			);

			$this -> from('tb_configuracao_cidade U', true);
			$this -> select($fields);
			$this -> join('tb_bairro B', 'B.id = U.id_bairro', 'left');
			$this -> where('U.id_configuracao', $_SESSION[USERDATA]['id']);

			return $this;

		}

		//------------------------------------------------------------------------------

		public function getGrupo($where = false)
		{

			$this -> from('tb_acl_grupo', true);
			$this -> select('id, grupo, nivel, status');
			$this -> where('status', '1');

			if ( ! $where )
			{
				if ( $_SESSION[USERDATA]['id_grupo'] > 1 )
					$this -> where('id <> 1');
			}
			else
			{
				$this -> where($where);
			}

			return $this;

		}

		//------------------------------------------------------------------------------

		/**
		 * Verifica a existência de usuário no banco de dados ao
		 * tentar ao tentar
		 * realizar um login
		 *
		 * @return	boolean		true|false	Caso o usuário falhe durate as
		 * tentativas
		 * 			array		$user		Todas as informações do usuário logado
		 * com sucesso
		 */
		public function login()
		{

			$post = $_POST;

			if ( $this -> validate($post) === FALSE )
				return FALSE;

			$login = array(
				'login' => $post['login'],
				'email' => $post['login']
			);

			$user = $this -> getConfiguracao() -> orWhere($login) -> getRowArray();

			if ( $user )
			{

				$senha = hashCode($post['senha']);

				if ( $senha === $user['senha'] )
				{

					/* Atualiza o campo de último login realizado no site*/
					$this -> allowedFields = ['ultimo_login'];
					$this -> user -> setUltimoLogin('now');
					$this -> set(['ultimo_login' => $this -> user -> getUltimoLogin()]);
					$this -> update(['id' => $user['id']]);

					return $user;
				}

				$this -> error('senha', 'Senha Incorreta');

			}
			else
			{
				$this -> error('login', 'Usuário inexistente ou inativo no sistema');
			}

			return false;

		}

		//--------------------------------------------------------------------

		public function sendMail()
		{

			$this -> user -> fill($_POST);

			if ( ! isset($_POST['enviar_email']) )
				return true;

			$this -> email = \Config\Services :: email();

			// enviar e-mail
			$this -> email -> setMailType('html');
			$this -> email -> setFrom('contato@pjmtelecom.com.br', 'Contato PJM Telecom');
			$this -> email -> setTo($this -> user -> getEmail());
			$this -> email -> setSubject('Você foi cadastrado no site ' . configuracoes('meta_title'));

			$mensagem = '<style></style>';
			$mensagem .= '<div class="email_body">';
			$mensagem .= '<h3> Olá, ' . $this -> user -> getNome() . '!</h3>';
			$mensagem .= '<p>Sua conta foi criada/atualizada no site
						<strong>
						  <a href="http://www.pjmtelecom.com/admin" target="_blank">' . configuracoes('meta_title') . '</a>
						</strong> pelo Administrador.
					  </p>';

			$mensagem .= '<p>Abaixo seguem as informações de acesso para sua área de administração.</p>';
			$mensagem .= '<p>
						<!-- URL: 	   <strong></strong><br> -->';
			$mensagem .= '	Seu Login: <strong><a>' . $this -> user -> getLogin() . '</a></strong><br>';
			$mensagem .= '	Sua Senha: <strong><a>' . $this -> user -> getSenha(false) . '</a></strong><br>';
			$mensagem .= '</p>';

			$mensagem .= '<p>Obrigado.<br><br>Equipe ' . configuracoes('meta_title') . '</p>';
			$mensagem .= '<a href="http://www.pjmtelecom.com/admin" target="_blank" class="btn btn-success btn-large">Acessar Agora</a>';
			$mensagem .= '</div>';

			$this -> email -> setMessage($mensagem);

			if ( $this -> email -> send() )
			{
				return TRUE;
			}

			$this -> error('Não foi possível enviar sua mensagem. Tente novamente mais tarde.');
			return FALSE;

		}

		//--------------------------------------------------------------------

	}

}
