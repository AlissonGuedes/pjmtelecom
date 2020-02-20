<?php

namespace App\Controllers
{

    class Account extends AppController
    {
        public function __construct()
        {
            $this -> user_model = new \App\Models\UserModel();
            $this -> user = new \App\Entities\User();
        }

        public function index()
        {
            if (! isset($_SESSION[USERDATA])) {
                $dados['titulo'] = '.:: Autenticação ::.';
                $dados['method'] = 'post';
                return view('login/index', $dados);
            } else {
                location(base_url() . 'dashboard');
            }
        }

        /**
         * Verificar o login e senha e, caso estejam corretos,
         * realiza a autenticação do
         * usuário no sistema
         */
        public function auth()
        {
            $type = 'error';
            $msg = 'usuário logado com sucesso!';

            $dados['titulo'] = 'Autenticação';

            if ($user = $this -> user_model -> login()) {
                $type = 'success';

                $userdata = [USERDATA => $user];
                $this -> session -> set($userdata);
            } else {
                $msg = $this -> user_model -> errors();
            }

            echo json_encode(array(
                'type' => $type,
                'msg' => $msg,
                'url' => base_url(),
                'redirect' => 'reload'
            ));
        }

        public function logout()
        {
            $this -> session -> remove(USERDATA);
            location(base_url());
        }
    }

}
