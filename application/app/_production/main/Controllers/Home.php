<?php

namespace App\Controllers
{

	use \App\Models\PlanoModel;
	use \App\Models\BannerModel;
	use \App\Models\CidadeModel;

	class Home extends AppController {

		private $user_model;

		public function __construct()
		{

			if ( isAjax() && isset($_POST) && isset($_POST['acao']) && $_POST['acao'] == 'localizacao' )
			{

				setcookie('cidade');
				setcookie('bairro');

				if ( $_POST['cidade'] && $_POST['bairro'] )
				{
					setcookie('cidade', $_POST['cidade'], strtotime('+30 days'));
					setcookie('bairro', $_POST['bairro'], strtotime('+30 days'));
					$type = 'success';
					$msg = 'Redirecionando...';
				}
				else
				{

					$type = 'error';
					$msg = 'Informe a cidade e o bairro mais próximo de sua residência.';

				}

				alert(array(
					'type' => $type,
					'msg' => $msg,
					'url' => base_url(),
					'redirect' => 'reload'
				));

				exit(1);
			}

			// Models
			$this -> plano_model = new PlanoModel();
			$this -> banner_model = new BannerModel();
			$this -> cidade_model = new CidadeModel();

		}

		public function index()
		{

			if ( ! isset($_COOKIE['cidade']) && ! isset($_COOKIE['bairro']) )
			{
				$dados['cidades'] = $this -> cidade_model -> getCidadesPorPlano();
				return $this -> view('home/index', $dados);
			}
			else
			{

				// banners do site
				$dados['banners'] = $this -> banner_model -> getBannerByCity();

				// tipo dos planos
				$dados['tipo_planos'] = $this -> plano_model -> getTipoPlano();

				return $this -> view('home/home', $dados);

			}

		}

		public function localizacao()
		{

			$dados['cidades'] = $this -> cidade_model -> getCidadesPorPlano();

			return $this -> view('home/localizacao', $dados);
		}

		public function quem_somos()
		{

			return $this -> view('quem_somos/index');

		}

	}

}
