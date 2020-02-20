<?php

namespace App\Controllers
{

	use \App\Models\UserModel;

	class Home extends AppController {

		private $user_model;

		public function __construct()
		{
			$this -> user_model = new UserModel();
		}

		public function index()
		{

			$dados['titulo'] = 'Admin';
			$dados['users'] = $this -> user_model -> getUser(['U.id' => '1']);

			return $this -> view('home/index', $dados);

		}

	}

}
