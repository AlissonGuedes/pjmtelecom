'use strict';

/**
 * Inicialização da classe URI
 *
 * 	plugins_elements();
 *
 * @path {js/classes/URI.js}
 */
var uri = new URI();
var is_ajax = true;
window.onload = uri.Loader(is_ajax);

/**
 *
 */
$(document).ready(function()
{

	autoload();

	$.ajax(
	{
		'type' : 'post',
		'url' : MAIN + 'env',
		'data' :
		{
			'checkenv' : true
		},
		'dataType' : 'json',
		success : function(data)
		{

			if(data.type === 'success')
			{
				if(data.debug)
				{
					M.toast(
					{
						html : data.msg,
						classes : 'amber darken-3',
						// displayLength : Infinity
							displayLength: 5000
					});

					$('.toast-action').on('click', function(e)
					{
						e.preventDefault();
						M.Toast.dismissAll();
					});

				}

			}

		}

	});

});

