/* Webarch Admin Dashboard
 /* This JS is only for DEMO Purposes - Extract the code that you need
 -----------------------------------------------------------------*/
if($.fn.dataTable)
{

	/* Set the defaults for DataTables initialisation */
	$.extend(true, $.fn.dataTable.defaults,
	{
		"sDom" : "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'p i>>",
		"sPaginationType" : "materialize",
		"oLanguage" :
		{
			"sLengthMenu" : "_MENU_"
		}
	});

	/* Default class modification */
	$.extend($.fn.dataTableExt.oStdClasses,
	{
		"sWrapper" : "dataTables_wrapper form-inline"
	});

	/* API method to get paging information */
	$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
	{
		oSettings =
		{
			"iStart" : oSettings._iDisplayStart,
			"iEnd" : oSettings.fnDisplayEnd(),
			"iLength" : oSettings._iDisplayLength,
			"iTotal" : oSettings.fnRecordsTotal(),
			"iFilteredTotal" : oSettings.fnRecordsDisplay(),
			"iPage" : oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
			"iTotalPages" : oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		};

		return oSettings;
	};

	/* Materialize style pagination control */
	$.extend($.fn.dataTableExt.oPagination,
	{
		"materialize" :
		{
			"fnInit" : function(oSettings, nPaging, fnDraw)
			{
				var oLang = oSettings.oLanguage.oPaginate;
				var fnClickHandler = function(e)
				{
					e.preventDefault();
					if(oSettings.oApi._fnPageChange(oSettings, e.data.action))
					{
						fnDraw(oSettings);
					}
				};

				$(nPaging).addClass('pagination').append('<ul>' + '<li class="prev disabled"><a href="#"><i class="material-icons">keyboard_arrow_left</i></a></li>' + '<li class="next disabled"><a href="#"><i class="material-icons">keyboard_arrow_right</i></a></li>' + '</ul>');
				var els = $('a', nPaging);
				$(els[0]).bind('click.DT',
				{
					action : "previous"
				}, fnClickHandler);
				$(els[1]).bind('click.DT',
				{
					action : "next"
				}, fnClickHandler);
			},

			"fnUpdate" : function(oSettings, fnDraw)
			{
				var iListLength = 5;
				var oPaging = oSettings.oInstance.fnPagingInfo();
				var an = oSettings.aanFeatures.p;
				var i,
				    ien,
				    j,
				    sClass,
				    iStart,
				    iEnd,
				    iHalf = Math.floor(iListLength / 2);

				if(oPaging.iTotalPages < iListLength)
				{
					iStart = 1;
					iEnd = oPaging.iTotalPages;
				}
				else if(oPaging.iPage <= iHalf)
				{
					iStart = 1;
					iEnd = iListLength;
				}
				else if(oPaging.iPage >= (oPaging.iTotalPages - iHalf))
				{
					iStart = oPaging.iTotalPages - iListLength + 1;
					iEnd = oPaging.iTotalPages;
				}
				else
				{
					iStart = oPaging.iPage - iHalf + 1;
					iEnd = iStart + iListLength - 1;
				}

				for ( i = 0,
				ien = an.length; i < ien; i ++ )
				{
					// Remove the middle elements
					$('li:gt(0)', an[i]).filter(':not(:last)').remove();

					// Add the new list items and their event handlers
					for ( j = iStart; j <= iEnd; j ++ )
					{
						sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
						$('<li ' + sClass + '><a href="#">' + j + '</a></li>').insertBefore($('li:last', an[i])[0]).bind('click', function(e)
						{
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
							fnDraw(oSettings);
						});
					}

					// Add / remove disabled classes from the static elements
					if(oPaging.iPage === 0)
					{
						$('li:first', an[i]).addClass('disabled');
					}
					else
					{
						$('li:first', an[i]).removeClass('disabled');
					}

					if(oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0)
					{
						$('li:last', an[i]).addClass('disabled');
					}
					else
					{
						$('li:last', an[i]).removeClass('disabled');
					}
				}
			}

		}
	});

	/*
	* TableTools Materialize compatibility Required TableTools 2.1+
	*/

	// // Set the classes that TableTools uses to something suitable for Materialize
	// $.extend(true, $.fn.DataTable.TableTools.classes,
	// {
	// "container" : "DTTT ",
	// "buttons" :
	// {
	// "normal" : "btn btn-white",
	// "disabled" : "disabled"
	// },
	// "collection" :
	// {
	// "container" : "DTTT_dropdown dropdown-menu",
	// "buttons" :
	// {
	// "normal" : "",
	// "disabled" : "disabled"
	// }
	// },
	// "print" :
	// {
	// "info" : "DTTT_print_info modal"
	// },
	// "select" :
	// {
	// "row" : "active"
	// }
	// });

	// // Have the collection use a materialize compatible dropdown
	// $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags,
	// {
	// "collection" :
	// {
	// "container" : "ul",
	// "button" : "li",
	// "liner" : "a"
	// }
	// });

	if($.fn.seletct2)
	{
		$(".select2-wrapper").select2(
		{
			minimumResultsForSearch : -1
		});
	}

	/* Formating function for row details */
	function fnFormatDetails(oTable, nTr)
	{
		var aData = oTable.fnGetData(nTr);
		var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="inner-table">';
		sOut += '<tr><td>Rendering engine:</td><td>' + aData[1] + ' ' + aData[4] + '</td></tr>';
		sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
		sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
		sOut += '</table>';

		return sOut;
	}

	/**
	 * Funções para atualizar tabelas no html
	 *
	 * @param {Object}
	 *            url
	 * @param {Object}
	 *            params : Parâmetros opcionais enviados em formato POST para serem
	 *            tratados no lado do servidor
	 * @method {make_datatable}
	 */
	function make_datatable(url, params)
	{

		// Adiciona plugin datatable ao elemento Table do HTML
		var tabela = $('.datatable').DataTable();

		tabela.destroy();

		$.fn.dataTable.ext.errMode = '';

		// Variável {$data} para enviar com parâmetros $_POST
		$data = [];

		// Variável vazia {items} = array()
		var items = [];

		// Variável estática {action} = array() que será enviada com tipo $_POST para
		// o controller definindo uma ação a ser executada
		var action =
		{
			'action' : 'listar'
		};

		// Combina a variável {$post} + os parâmetros recebido na função {params}
		items.push(action, params);

		// percorre o array {items} convertendo vários arrays
		$.each(items, function(ind, val)
		{
			for ( var i in val )
			{
				action[i] = val[i];
			}
		});

		$('.datatable').each(function()
		{

			var self = $(this);
			var columns_clickable = [];

			// Bloquear eventos de click às colunas que não tiverem atributo clickable=true
			$(self).find('th').each(function(i)
			{

				var clickable = $(this).data('clickable');

				if( typeof clickable !== 'undefined' && clickable !== '' && clickable === false)
				{
					columns_clickable.push(i);
					$(this).attr('data-clickable', clickable);
				}
				else
					$(this).attr('data-clickable', true);

			});

			var table = self.DataTable(
			{
				// 'sDom' : '<"row"<"col-md-6"l T><"col-md-6"f>r>t<"row"<"col-md-12"p i>>',
				// 'tabelaTools' :
				// {
				// 		'aButtons' :
				//		[
				// 			{
				//				'sExtends' : 'collection',
				//				'sButtonText' : '<i class="fa fa-cloud-download"></i>',
				//				'aButtons' : ['csv', 'xls', 'pdf', 'copy']
				// 			}
				// 		]
				// },
				// 'responsive' : true,
				responsive : !1,
				scrollY : ( ! ! $(this).hasClass('responsive-table') ? '50vh' : !0),
				scrollCollapse : ! !$(this).hasClass('responsive-table'),
				paging : 1,
				'oLanguage' :
				{
					'sEmptyTable' : 'Nenhum registro encontrado',
					'sInfo' : '_START_-_END_ de _TOTAL_',
					'sInfoEmpty' : 'Nenhum registro encontrado',
					'sInfoFiltered' : '', //'(Filtrados de _MAX_ registros)',
					'sInfoPostFix' : '',
					'sInfoThousands' : '.',
					// 'sLengthMenu' : '_MENU_',
					'sLengthMenu' : '',
					'sLoadingRecords' : 'Carregando...',
					'sProcessing' : '<i></i> &nbsp; Carregando...',
					'sZeroRecords' : 'Nenhum registro encontrado',
					'sSearch' : ( typeof self.data('label') !== 'undefined' && self.data('label') ? self.data('label') : ''),
					'sSearchPlaceholder' : ( typeof self.data('placeholder') !== 'undefined' && self.data('placeholder') ? self.data('placeholder') : null),
					'oPaginate' :
					{
						'sNext' : 'Próximo',
						'sPrevious' : 'Anterior',
						'sFirst' : 'Primeiro',
						'sLast' : 'Último'
					},
					'oAria' :
					{
						'sSortAscending' : ': Ordenar colunas de forma ascendente',
						'sSortDescending' : ': Ordenar colunas de forma descendente'
					}
				},
				'processing' : true,
				'serverSide' : true,
				'order' : [],
				'displayLength' : 50,
				'ajax' :
				{
					type : 'post',
					url : url,
					data : action
				},
				"fnDrawCallback" : function()
				{
					if( typeof load_plugins === 'function')
						load_plugins();

					/**
					 * As divs acima da div.dataTables_wrapper.form-inline estão sendo ocultadas pelo
					 * JavaScript, onde não consegui identificar o código que a oculta.
					 * Adicionei esta linha para esse resolver este problema
					 */
					$(this).parent().parent().parent().parent().show();

				},
				'fnRowCallback' : function(nRow, aData, iDisplayIndex, iDisplayIndexFull)
				{

					var data_toggle = $(this).data('toggle');
					var data_target = $(this).attr('id');

					$(nRow).each(function()
					{

						$(this).find('td').each(function()
						{
							var clickable = $(this).find('[data-clickable]').data('clickable');
							if( typeof clickable !== 'undefined' && clickable !== '')
								$(this).attr('data-clickable', clickable);
							else
								$(this).attr('data-clickable', true);
						});

						$(this).find('td').on('click', function()
						{

							if($(this).data('clickable'))
							{

								if(data_toggle === 'modal')
								{
									$(this).addClass('modal-trigger');
									$(this).attr('data-target', 'modal-' + data_target);
								}

								var id = $(aData[0]).find(':input').attr('value');
								var href = window.location.href.split('?')[0] + '/' + id;
								var acao = 'editar';

								if(data_toggle === 'modal')
								{

									var name = $(this).parents('.datatable').attr('id');
									var title = $(this).parents('.datatable').attr('data-title');
									var datatype = $(this).parents('.datatable').attr('data-type');

									var params =
									{
										'name' : name,
										'acao' : acao,
										'href' : href,
										'title' : title,
										'datatype' : datatype
									};

									modal_editor(params, true);

								}// End if
								else
								{

									$.ajax(
									{
										type : 'post',
										url : href,
										dataType : 'json',
										data :
										{
											'id' : id,
											'acao' : acao
										},
										success : function(data)
										{
											var uri = new URI();
											if(uri.Request(href))
											{
												history.pushState('teste', 'tete2', href);
											}
										},
										error : function()
										{
										}

									});
									// End click event

								} // End else

							}

						});

					});
					// end Each

				},
				'sPaginationType' : 'materialize',
				'columnDefs' : [
				{
					'bSortable' : false,
					'aTargets' : columns_clickable,
					'ordareble' : false,
				},
				{
					'className' : ['not-clickable'],
					// 'targets' : [0]
				}
				// {
				// 		"targets" : [3],
				// 		"visible" : true,
				// 		"searchable" : true
				// },
				// {
				//		"targets" : ($('.datatable').attr('id') === 'emails') ? [4] : [],
				//		"visible" : false
				// }
				],
				// 'aaSorting' : [[0, 'asc']],
				'bAutoWidth' : true,
			});

		});

	};

}

function load_datatable()
{

	// listar no range de data do filtro
	var filtro = $('[data-toggle="daterangepicker"]').find('.data');
	var data = typeof filtro !== 'undefined' ? filtro.text().replace(/\ /, '').replace(/\ /, '') : null;
	var daterange = data !== null ?
	{
		'periodo' : data
	} : null;
	var params =
	{
		filter : daterange
	};

	$('.datatable').each(function()
	{

		if( typeof $(this).attr('data-request') !== 'undefined')
			if($(this).attr('data-request') == 'false')
				return false;

		url = typeof $(this).attr('data-url') !== 'undefined' ? $(this).attr('data-url') : window.location.href + '/index';

		make_datatable(url, params);

	});

}

