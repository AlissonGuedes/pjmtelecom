<?php

namespace App\Controllers
{

    use \App\Models\PlanoModel;
    use \App\Models\CidadeModel;

    use \App\Entities\Plano;

    /**
     * Controlador que gerencia a página {ajax} na área
     * Administrativa
     *
     * @author Alisson Guedes <alissonguedes87@gmail.com>
     * @version 2
     * @access public
     * @package PJM Telecom
     * @example classe Ajax
     */
    class Ajax extends AppController
    {

        /**
         * Instância do banco de dados
         *
         * @var \App\Models\PlanoModel
         */
        private $plano_model;

        //--------------------------------------------------------------------

        /**
         * Método construtor da classe
         *
         * @method __construct()
         */
        public function __construct()
        {
            if (! isAjax()) {
                location(base_url());
            }

            // Models
            $this -> plano_model = new PlanoModel();
            $this -> cidade_model = new CidadeModel();
        }

        //--------------------------------------------------------------------

        /**
         * @name Busca Bairros
         *
         * Realiza a busca por bairros através do ID da cidade.
         *
         * @method busca_bairros()
         * @return Array [ Bairro, Id]
         */
        public function busca_bairros()
        {
            if (isset($_POST['cidade']) && isset($_POST['bairro']) && isset($_POST['acao'])) {
                if ($_POST['acao'] === 'add') {
                    $this -> view('ajax/add_bairro');
                } elseif ($_POST['acao'] === 'del') {
                    $this -> view('ajax/del_bairro');
                }

                exit ;
            }

            $dados['bairros'] = $this -> cidade_model -> getBairro(['tb_bairro.id_cidade' => $_POST['cidade']]);

            return $this -> view('ajax/bairros', $dados);
        }

        //--------------------------------------------------------------------
    }

}
