<?php

/*
 * Mensagem de informação de ambiente de testes (constant: ENVIRONMENT)
 */
$mensagem_ambiente  = '<i class="material-icons left">warning</i>';
$mensagem_ambiente .= '<span class="center-align">Esta é uma versão de teste. Qualquer alteração aqui não afetará nenhuma informação em produção.</span>';
$mensagem_ambiente .= '<button class="btn btn-floating btn-small transparent toast-action waves-effect waves-light z-depth-0">';
$mensagem_ambiente .= '		<i class="material-icons right">close</i>';
$mensagem_ambiente .= '</button>';

define('MENSAGEM_AMBIENTE', $mensagem_ambiente);

// $path = '_production';
$app ='../application/index.php';

require($app);

