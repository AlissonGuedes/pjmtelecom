<?php

/**
 * Validation language strings.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2014-2019 British Columbia Institute of Technology (https://bcit.ca/)
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://codeigniter.com
 * @since      Version 4.0.0
 * @filesource
 *
 * @codeCoverageIgnore
 */

return [
	// Core Messages
   'noRuleSets'            => 'Nenhuma regra foi especificada nas configurações de validação.',
   'ruleNotFound'          => '{0} não é uma regra válida.',
   'groupNotFound'         => '{0} não é um grupo de regras de validação.',
   'groupNotArray'         => '{0} grupo de regras deve ser uma matriz.',
   'invalidTemplate'       => '{0} não é um modelo de validação válido.',

	// Rule Messages
   'alpha'                 => 'O campo {field} pode conter apenas caracteres alfabéticos.',
   'alpha_dash'            => 'O campo {field} pode conter apenas caracteres alfanuméricos, sublinhados e traços.',
   'alpha_numeric'         => 'O campo {field} pode conter apenas caracteres alfanuméricos.',
   'alpha_numeric_space'   => 'O campo {field} pode conter apenas caracteres alfanuméricos e espaços.',
   'alpha_space'           => 'O campo {field} pode conter apenas caracteres e espaços alfabéticos.',
   'decimal'               => 'O campo {field} deve conter um número decimal.',
   'differs'               => 'O campo {field} deve ser diferente do campo {param}.',
   'equals'                => 'O campo {field} deve ser exatamente: {param}.',
   'exact_length'          => 'O campo {field} deve ter caracteres exatamente {param}.',
   'greater_than'          => 'O campo {field} deve conter um número maior que {param}.',
   'greater_than_equal_to' => 'O campo {field} deve conter um número maior ou igual a {param}.',
   'in_list'               => 'O campo {field} deve ser um dos seguintes: {param}.',
   'integer'               => 'O campo {field} deve conter um número inteiro.',
   'is_natural'            => 'O campo {field} deve conter apenas dígitos.',
   'is_natural_no_zero'    => 'O campo {field} deve conter apenas dígitos e deve ser maior que zero.',
   'is_unique'             => 'O campo {field} deve conter um valor único.',
   'less_than'             => 'O campo {field} deve conter um número menor que {param}.',
   'less_than_equal_to'    => 'O campo {field} deve conter um número menor ou igual a {param}.',
   'matches'               => 'O campo {field} não corresponde ao campo {param}.',
   'max_length'            => 'O campo {field} não pode exceder {param} caracteres.',
   'min_length'            => 'O campo {field} deve ter pelo menos {param} caracteres.',
   'not_equals'            => 'O campo {field} não pode ser: {param}.',
   'numeric'               => 'O campo {field} deve conter apenas números.',
   'regex_match'           => 'O campo {field} não está no formato correto.',
   'required'              => 'O campo {field} é obrigatório.',
   'required_with'         => 'O campo {field} é obrigatório quando {param} estiver informado.',
   'required_without'      => 'O campo {field} é obrigatório quando {param} não estiver informado.',
   'timezone'              => 'O campo {field} deve ter um horário válido.',
   'valid_base64'          => 'O campo {field} deve ter ser base64.',
   'valid_email'           => 'O campo {field} deve conter um e-mail válido.',
   'valid_emails'          => 'O campo {field} deve conter e-mails válidos.',
   'valid_ip'              => 'O campo {field} deve conter um IP válido.',
   'valid_url'             => 'O campo {field} deve conter uma URL válida.',
   'valid_date'            => 'O campo {field} deve conter uma data válida.',

	// Credit Cards
   'valid_cc_num'          => '{field} não é um cartão de crédito válido.',

	// Files
   'uploaded'              => '{field} não é um arquivo válido.',
   'max_size'              => '{field} é um arquivo muito grande.',
   'is_image'              => '{field} não é uma imagem válida.',
   'mime_in'               => '{field} não possui um formato válido.',
   'ext_in'                => '{field} não possui uma extensão válida.',
   'max_dims'              => '{field} is either not an image, or it is too wide or tall.',
];
