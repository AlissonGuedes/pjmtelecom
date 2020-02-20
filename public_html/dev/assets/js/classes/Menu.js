'use strict';

/**
 * Classe Menu;
 *
 * Esta classe permite a interação com os links de menus.
 */
function Menu()
{

	var $BASE_URL = BASE_URL;
	var $BODY = $('body');
	var $TOGGLE_MENU = $('#menu_toggle');
	// var $SIDEBAR_MENU = $('#main-menu');
	var $SIDEBAR_MENU = $('#slide-out');
	var $SIDEBAR_FOOTER = $('.sidebar-footer');
	var $LEFT_COL = $('.left_col');
	var $RIGHT_COL = $('.right_col');
	var $NAV_MENU = $('.nav_menu');
	var $FOOTER = $('footer');

	var url = window.location.href.split($BASE_URL)[1];
	var pagina = url !== undefined ? url.replace(/[^a-zA-Z0-9(\.\w?)+?]+/g, '/') : '';

	/**
	 * Método retorna o valor da altura da coluna do menu
	 */
	$.setContentHeight = function()
	{

		$RIGHT_COL.css('min-height', $(window).height());

		var bodyHeight = $BODY.outerHeight();
		var footerHeight = $BODY.hasClass('footer_fiexed') ? -10 : $FOOTER.height();
		var leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.hegiht();
		var contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

		contentHeight -= $NAV_MENU.height() + footerHeight;

	};

	/**
	 * Seleciona o menu ativo
	 */
	$.setMenuActive = function()
	{

		$SIDEBAR_MENU.find('li > a').on('click', function(e)
		{

			var uri = new URI();
			var href = $(this).attr('href');

			e.preventDefault();

			if(uri.is_url(href))
			{
				// Quando o botão de menu for exibido em modo responsivo,
				// Ocultar a barra de menus antes de carregar a página
				if(window.innerWidth < 993)
					$('.sidenav').sidenav('close');

				$SIDEBAR_MENU.find('a').removeClass('open');
				$(this).addClass('open');
			}

			e.preventDefault();

		});

		// Auto Scroll menu to the active item
		var position;
		if($(".sidenav-main li a.open").parent("li.open").parent("ul.collapsible-sub").length > 0)
		{
			position = $(".sidenav-main li a.open").parent("li.open").parent("ul.collapsible-sub").position();
		}
		else
		{
			position = $(".sidenav-main li a.open").parent("li.open").position();
		}
		setTimeout(function()
		{
			if(position !== undefined)
			{
				$(".sidenav-main ul").stop().animate(
				{
					scrollTop : position.top - 300
				}, 300);
			}
		}, 300);

	};

	/**
	 * @param HandleSidebarAndContentHeight();
	 * Método para contar altura da barra lateral
	 */
	$.handleSidebarAndContentHeight = function()
	{

		var content = $('.page-content');
		var sidebar = $SIDEBAR_MENU;

		if( !content.attr('data-height'))
			content.attr('data-height', content.height());

		if(sidebar.height() > content.height())
			content.css('min-height', sidebar.height() + 120);
		else
			content.css('min-height', content.attr('data-height'));

	};

	/**
	 * Método para abrir o menu
	 */
	$.openMenu = function()
	{

		$SIDEBAR_MENU.find('.collapsible').find('a').addClass('waves-effect').removeClass('open');
		$SIDEBAR_MENU.find('li').removeClass('open');

		if($('body').hasClass('condense-menu'))
			$SIDEBAR_MENU.addClass('mini');

		if( ! $SIDEBAR_MENU.hasClass('collapse'))
		{

			$SIDEBAR_MENU.find('a').removeClass('open').removeClass('active').parents('li').removeClass('open').removeClass('active').find('div.collapsible-body').hide().find('li').removeClass('open');
			$SIDEBAR_MENU.find('a[href="' + $BASE_URL + pagina + '"]').addClass('open').addClass('active').removeClass('waves-effect').parents('li').addClass('open').addClass('active').parents('.collapsible-body').show();

		}

		$.setMenuActive();

	};

	this.openMenu = $.openMenu;

}
