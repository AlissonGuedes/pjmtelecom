/**
 * Carregar o plugin Materializecss
 */
function Materializecss()
{

	/*================================================================================
	 Item Name: Materialize - Material Design Admin Template
	 Version: 5.0
	 Author: PIXINVENT
	 Author URL: https://themeforest.net/user/pixinvent/portfolio
	 ================================================================================*/
	$(function()
	{

		"use strict";

		$('.scrollspy').scrollSpy();

		/** Ampliar imagens **/
		$('.materialboxed').materialbox();

		/** Floating Action Buttons - Botões de ação **/
		$('.fixed-action-btn').floatingActionButton(
		{
			direction : typeof $(this).data('direction') !== 'undefined' && $(this).data('direction') !== null ? $(this).data('direction') : 'top',
			hoverEnabled : false
		});

		/** Collapsible - Recolher menus **/
		$('.collapsible').collapsible();

		if($('.sidenav').length)
		{
			/** Sidenav - Para barras de menus escondidas **/
			$('.sidenav').sidenav();
			// $('body').perfectScrollbar();
			// or just with selector string
			// var ps = new PerfectScrollbar('html');
			// var ps = new PerfectScrollbar('.sidenav');
		}

		// Init collapsible
		$(".collapsible").collapsible(
		{
			accordion : true,
			onOpenStart : function()
			{
				// Removed open class first and add open at collapsible active
				$(".collapsible > li.open").removeClass("open");
				setTimeout(function()
				{
					$("#slide-out > li.open > a").parent().addClass("open");
				}, 10);
			}

		});

		// Inicializa Modals
		if($('.modal').length)
		{
			$('.modal').each(function()
			{
				var modal = $(this);
				var dismissible = typeof modal.data('dismissible') === 'undefined' || modal.data('dismissible');
				modal.modal(
				{
					dismissible : dismissible,
					inDuration : 150,
					outDuration : 200
				});
			});
		}

		// Textareas
		// if($('body').find('textarea').length > 0)
		// 	M.textareaAutoResize($('textarea'));

		$('[maxlength]').each(function()
		{

			$(this).attr('data-length', $(this).attr('maxlength')).characterCounter();

		});

		var $data = [];

		$('.chips').each(function()
		{

			var self = $(this);

			self.parent().find('input[name="meta_keywords[]"]').each(function()
			{
				$data.push(
				{
					'tag' : $(this).val()
				})
			});

			$(this).chips(
			{
				data : $data,
				placeholder : 'Plavaras-chave',
				secondaryPlaceholder : '+Tag',
				onChipAdd : function(index, value)
				{
					var tagvalue = $(value).text().replace('close', '');
					$(this.el).parent().append($('<input/>',
					{
						type : 'hidden',
						name : $(this.el).data('name'),
						value : tagvalue
					}));
				},
				onChipDelete : function(index, value)
				{
					var tagvalue = $(value).text().replace('close', '');
					$(this.el).parent().find('input[value="' + tagvalue + '"]').remove();
				}

			});

		});

		///////////////////////////////////////////////////////////////////////////////////////////////

		// Add open class on init
		$("#slide-out > li.open > a").parent().addClass("open");

		// Open active menu for multi level
		if($("li.open .collapsible-sub .collapsible").find("a.open").length > 0)
		{
			$("li.open .collapsible-sub .collapsible").find("a.open").closest("div.collapsible-body").show();
			$("li.open .collapsible-sub .collapsible").find("a.open").closest("div.collapsible-body").closest("li").addClass("open");
		}

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

		// Collapsible navigation menu
		$(".nav-collapsible .navbar-toggler").click(function()
		{
			// Toggle navigation expan and collapse on radio click
			if($(".sidenav-main").hasClass("nav-expanded") && !$(".sidenav-main").hasClass("nav-lock"))
			{
				$(".sidenav-main").toggleClass("nav-expanded");
				$("#main").toggleClass("main-full");
			}
			else
			{
				$("#main").toggleClass("main-full");
			}
			// Set navigation lock / unlock with radio icon
			if($(this).children().text() == "radio_button_unchecked")
			{
				$(this).children().text("radio_button_checked");
				$(".sidenav-main").addClass("nav-lock");
				$(".navbar .nav-collapsible").addClass("sideNav-lock");
			}
			else
			{
				$(this).children().text("radio_button_unchecked");
				$(".sidenav-main").removeClass("nav-lock");
				$(".navbar .nav-collapsible").removeClass("sideNav-lock");
			}
		});

		// Expand navigation on mouseenter event
		$(".sidenav-main.nav-collapsible, .navbar .brand-sidebar").mouseenter(function()
		{
			if( !$(".sidenav-main.nav-collapsible").hasClass("nav-lock"))
			{
				$(".sidenav-main.nav-collapsible, .navbar .nav-collapsible").addClass("nav-expanded").removeClass("nav-collapsed");
				$("#slide-out > li.close > a").parent().addClass("open").removeClass("close");

				setTimeout(function()
				{
					// Open only if collapsible have the children
					if($(".collapsible .open").children().length > 1)
					{
						$(".collapsible").collapsible("open", $(".collapsible .open").index());
					}
				}, 100);
			}
		});

		// Collapse navigation on mouseleave event
		$(".sidenav-main.nav-collapsible, .navbar .brand-sidebar").mouseleave(function()
		{
			if( !$(".sidenav-main.nav-collapsible").hasClass("nav-lock"))
			{
				var openLength = $(".collapsible .open").children().length;
				$(".sidenav-main.nav-collapsible, .navbar .nav-collapsible").addClass("nav-collapsed").removeClass("nav-expanded");
				$("#slide-out > li.open > a").parent().addClass("close").removeClass("open");
				setTimeout(function()
				{
					// Open only if collapsible have the children
					if(openLength > 1)
					{
						$(".collapsible").collapsible("close", $(".collapsible .close").index());
					}
				}, 100);
			}
		});

		// Search class for focus
		$(".header-search-input").focus(function()
		{
			$(this).parent("div").addClass("header-search-wrapper-focus");
		}).blur(function()
		{
			$(this).parent("div").removeClass("header-search-wrapper-focus");
		});

		//Search box form small screen
		$(".search-button").click(function(e)
		{
			if($(".search-sm").is(":hidden"))
			{
				$(".search-sm").show();
				$(".search-box-sm").focus();
			}
			else
			{
				$(".search-sm").hide();
				$(".search-box-sm").val("");
			}
		});
		$(".search-sm-close").click(function(e)
		{
			$(".search-sm").hide();
			$(".search-box-sm").val("");
		});

		//Breadcrumbs with image
		if($("#breadcrumbs-wrapper").attr("data-image"))
		{
			var imageUrl = $("#breadcrumbs-wrapper").attr("data-image");
			$("#breadcrumbs-wrapper").addClass("breadcrumbs-bg-image");
			$("#breadcrumbs-wrapper").css("background-image", "url(" + imageUrl + ")");
		}

		// Check first if any of the task is checked
		$("#task-card input:checkbox").each(function()
		{
			checkbox_check(this);
		});

		// Task check box
		$("#task-card input:checkbox").change(function()
		{
			checkbox_check(this);
		});

		// Check Uncheck function
		function checkbox_check(el)
		{
			if( !$(el).is(":checked"))
			{
				$(el).next().css("text-decoration", "none");
				// or addClass
			}
			else
			{
				$(el).next().css("text-decoration", "line-through");
				//or addClass
			}
		}

		//Init tabs
		$(".tabs").tabs();

		// Swipeable Tabs Demo Init
		if($("#tabs-swipe-demo").length)
		{
			$("#tabs-swipe-demo").tabs(
			{
				swipeable : true
			});
		}

		// Set checkbox on forms.html to indeterminate
		var indeterminateCheckbox = document.getElementById("indeterminate-checkbox");
		if(indeterminateCheckbox !== null)
			indeterminateCheckbox.indeterminate = true;

		// Materialize Slider
		$(".slider").slider(
		{
			full_width : true
		});

		// Commom, Translation & Horizontal Dropdown
		$(".dropdown-trigger").dropdown(
		{
			inDuration : 300,
			outDuration : 225,
			constrainWidth : false,
			// hover : false,
			gutter : 0,
			coverTrigger : true,
			// alignment : "left"
		});

		// Fab
		$(".fixed-action-btn").floatingActionButton();
		$(".fixed-action-btn.horizontal").floatingActionButton(
		{
			direction : "left"
		});
		$(".fixed-action-btn.click-to-toggle").floatingActionButton(
		{
			direction : "left",
			hoverEnabled : false
		});
		$(".fixed-action-btn.toolbar").floatingActionButton(
		{
			toolbarEnabled : true
		});

		// Materialize Tabs
		$(".tab-demo").show().tabs();
		$(".tab-demo-active").show().tabs();

		// Materialize scrollSpy
		$(".scrollspy").scrollSpy();

		// Materialize tooltip
		$(".tooltipped").tooltip(
		{
			delay : 50
		});

		//Main Left Sidebar Menu // sidebar-collapse
		$(".sidenav").sidenav(
		{
			edge : "left" // Choose the horizontal origin
		});

		//Main Right Sidebar
		$(".slide-out-right-sidenav").sidenav(
		{
			edge : "right"
		});

		//Main Right Sidebar Chat
		$(".slide-out-right-sidenav-chat").sidenav(
		{
			edge : "right"
		});

		// Perfect Scrollbar
		$("select").not(".disabled").select();
		var leftnav = $(".page-topbar").height();
		var leftnavHeight = window.innerHeight - leftnav;
		var righttnav = $("#slide-out-right").height();

		if($("#slide-out.leftside-navigation").length > 0)
		{
			if( !$("#slide-out.leftside-navigation").hasClass("native-scroll"))
			{
				var ps_leftside_nav = new PerfectScrollbar(".leftside-navigation",
				{
					wheelSpeed : 2,
					wheelPropagation : false,
					minScrollbarLength : 20
				});
			}
		}
		if($(".slide-out-right-body").length > 0)
		{
			var ps_slideout_right = new PerfectScrollbar(".slide-out-right-body, .chat-body .collection",
			{
				suppressScrollX : true
			});
		}
		if($(".chat-body .collection").length > 0)
		{
			var ps_slideout_chat = new PerfectScrollbar(".chat-body .collection",
			{
				suppressScrollX : true
			});
		}

		// Char scroll till bottom of the char content area
		var chatScrollAuto = $("#right-sidebar-nav #slide-out-chat .chat-body .collection");
		if(chatScrollAuto.length > 0)
		{
			chatScrollAuto[0].scrollTop = chatScrollAuto[0].scrollHeight;
		}

		// if((document.fullScreenElement && document.fullScreenElement !== null) || (
		// !document.mozFullScreen && !document.webkitIsFullScreen))
		// {
		// toggleFullScreen();
		// }

		// Fullscreen
		function toggleFullScreen()
		{

			if((document.fullScreenElement && document.fullScreenElement !== null) || ( !document.mozFullScreen && !document.webkitIsFullScreen))
			{
				if(document.documentElement.requestFullScreen)
				{
					document.documentElement.requestFullScreen();
				}
				else if(document.documentElement.mozRequestFullScreen)
				{
					document.documentElement.mozRequestFullScreen();
				}
				else if(document.documentElement.webkitRequestFullScreen)
				{
					document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
				}
				else if(document.documentElement.msRequestFullscreen)
				{
					if(document.msFullscreenElement)
					{
						document.msExitFullscreen();
					}
					else
					{
						document.documentElement.msRequestFullscreen();
					}
				}
			}
			else
			{
				if(document.cancelFullScreen)
				{
					document.cancelFullScreen();
				}
				else if(document.mozCancelFullScreen)
				{
					document.mozCancelFullScreen();
				}
				else if(document.webkitCancelFullScreen)
				{
					document.webkitCancelFullScreen();
				}
			}
		}


		$(".toggle-fullscreen").click(function()
		{
			toggleFullScreen();
		});

		// Detect touch screen and enable scrollbar if necessary
		function is_touch_device()
		{
			try
			{
				document.createEvent("TouchEvent");
				return true;
			}
			catch (e)
			{
				return false;
			}
		}

		if(is_touch_device())
		{
			$("#nav-mobile").css(
			{
				overflow : "auto"
			});
		}

		resizetable();

	});

	$(window).on("resize", function()
	{
		resizetable();
	});

	function resizetable()
	{
		if($(window).width() < 976)
		{
			if($('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo').length > 0)
				$('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo img').attr('src', BASEPATH + 'img/logo-blue.png');
			if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length > 0)
				$('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo img').attr('src', BASEPATH + 'img/logo-blue.png');
			if($('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo').length > 0)
				$('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo img').attr('src', BASEPATH + 'img/logo-white.png');
		}
		else
		{
			if($('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo').length > 0)
				$('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo img').attr('src', BASEPATH + 'img/logo-white.png');
			if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length > 0)
				$('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo img').attr('src', BASEPATH + 'img/logo-white.png');
			if($('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo').length > 0)
				$('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo img').attr('src', BASEPATH + 'img/logo-blue.png');
		}
	}

	resizetable();

	// Add message to chat
	function slide_out_chat()
	{
		var message = $(".search").val();
		if(message != "")
		{
			var html = '<li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat"><div class="user-content speech-bubble-right">' + '<p class="medium-small">' + message + "</p>" + "</div></li>";
			$("#right-sidebar-nav #slide-out-chat .chat-body .collection").append(html);
			$(".search").val("");
			var charScroll = $("#right-sidebar-nav #slide-out-chat .chat-body .collection");
			if(charScroll.length > 0)
			{
				charScroll[0].scrollTop = charScroll[0].scrollHeight;
			}
		}
	}

	/*
	 * Theme customizer
	 */

	var menuBgDefault = false;

	$(document).ready(function()
	{

		// Trigger customizer options
		$(".theme-cutomizer").sidenav(
		{
			edge : "right"
		});

		// var ps_theme_customiser = new PerfectScrollbar(".theme-cutomizer",
		// {
		// suppressScrollX : true
		// });

		if($("body").hasClass("vertical-modern-menu") || $("body").hasClass("vertical-menu-nav-dark"))
		{
			$(".menu-bg-color").hide();
		}
		else if($("body").hasClass("vertical-gradient-menu") || $("body").hasClass("vertical-dark-menu"))
		{
			$(".menu-color").hide();
			menuBgDefault = true;
		}
		else if($("body").hasClass("horizontal-menu"))
		{
			$(".menu-options").hide();
		}

		// Menu Options
		// ------------

		//Set menu color on select color
		$(".menu-color-option, .menu-bg-color-option").click(function(e)
		{
			$(".menu-color .menu-color-option, .menu-bg-color .menu-bg-color-option").removeClass("selected");
			$(this).addClass("selected");
			var menu_color = $(this).attr("data-color");
			if(menuBgDefault)
			{
				menuDark(true);
				menuBGColor(menu_color);
			}
			else
			{
				menuColor(menu_color);
			}
		});

		//Set menu dark/light
		$(".menu-dark-checkbox").click(function(e)
		{
			if($(".menu-dark-checkbox").prop("checked"))
			{
				menuDark(true);
			}
			else
			{
				menuDark(false);
			}
		});

		//Set menu selection type on select
		$(".menu-selection-radio").click(function(e)
		{
			var menu_selection = $(this).val();
			menuSelection(menu_selection);
		});

		//Set menu selection type on select
		$(".menu-collapsed-checkbox").click(function(e)
		{
			if($(".menu-collapsed-checkbox").prop("checked"))
			{
				menuCollapsed(true);
			}
			else
			{
				menuCollapsed(false);
			}
		});

		//Function to set menu color
		function menuColor(menu_color)
		{
			removeColorClass(".sidenav-main .sidenav li a.open");
			$(".sidenav-main .sidenav li a.open").css(
			{
				background : "none",
				"box-shadow" : "none"
			});
			$(".sidenav-main .sidenav li a.open").addClass(menu_color + " gradient-shadow");
		}

		//Function to set  menu bg color
		function menuBGColor(menu_color)
		{
			removeColorClass(".sidenav-main");
			$(".sidenav-main").addClass(menu_color + " sidenav-gradient");
		}

		//Function menu dark/light
		function menuDark(isDark)
		{
			if(isDark)
			{
				$(".menu-dark-checkbox").prop("checked", true);
				$(".sidenav-main").removeClass("sidenav-light").addClass("sidenav-dark");
			}
			else
			{
				$(".menu-dark-checkbox").prop("checked", false);
				$(".sidenav-main").addClass("sidenav-light").removeClass("sidenav-dark");
			}
		}

		//Function menu collapsed
		function menuCollapsed(isCollapsed)
		{
			if(isCollapsed)
			{
				$(".sidenav-main").removeClass("nav-lock");
				$(".navbar-main.nav-collapsible").removeClass("sideNav-lock").addClass("nav-expanded");
				$(".navbar-toggler i").html("radio_button_unchecked");
				$("#main").addClass("main-full");
				$(".sidenav-main.nav-collapsible, .navbar .brand-sidebar").trigger("mouseleave");
			}
			else
			{
				$(".sidenav-main").addClass("nav-lock").removeClass("nav-collapsed");
				$(".navbar-main.nav-collapsible").addClass("sideNav-lock").removeClass("nav-collapsed");
				$(".navbar-toggler i").html("radio_button_checked");
				$("#main").removeClass("main-full");
				$(".sidenav-main.nav-collapsible, .navbar .brand-sidebar").trigger("mouseenter");
			}
		}

		//Function menu collapsed
		function menuSelection(menu_selection)
		{
			$(".sidenav-main").removeClass("sidenav-active-square sidenav-active-rounded").addClass(menu_selection);
		}

		// Navbar Options
		// --------------

		// On click of navbar color
		$(".navbar-color-option").click(function(e)
		{
			$(".navbar-color .navbar-color-option").removeClass("selected");
			$(this).addClass("selected");
			var navbar_color = $(this).attr("data-color");
			navbarDark(true);
			navbarColor(navbar_color);
		});

		//Set menu dark/light
		$(".navbar-dark-checkbox").click(function(e)
		{
			if($(".navbar-dark-checkbox").prop("checked"))
			{
				navbarDark(true);
			}
			else
			{
				navbarDark(false);
			}
		});

		// Click on navbar fixed checkbox
		$(".navbar-fixed-checkbox").click(function(e)
		{
			if($(".navbar-fixed-checkbox").prop("checked"))
			{
				$("#header .navbar").addClass("navbar-fixed");
			}
			else
			{
				$("#header .navbar").removeClass("navbar-fixed");
			}
		});

		//Function to set navbar dark checkbox
		function navbarDark(isDark)
		{
			removeColorClass(".navbar-main");
			if(isDark)
			{
				$(".navbar-dark-checkbox").prop("checked", true);
				$(".navbar-main").removeClass("navbar-light").addClass("navbar-dark");
			}
			else
			{
				$(".navbar-dark-checkbox").prop("checked", false);
				$(".navbar-main").addClass("navbar-light").removeClass("navbar-dark");
			}
		}

		//Function to set  navbar color
		function navbarColor(navbar_color)
		{
			removeColorClass(".navbar-main");
			$(".navbar-main").addClass(navbar_color);
			if($("body").hasClass("vertical-modern-menu"))
			{
				removeColorClass(".content-wrapper-before");
				$(".content-wrapper-before").addClass(navbar_color);
			}
		}

		// Footer Options
		// --------------

		//On click of footer dark
		$(".footer-dark-checkbox").click(function(e)
		{
			removeColorClass(".page-footer");
			if($(".footer-dark-checkbox").prop("checked"))
			{
				footerDark(true);
			}
			else
			{
				footerDark(false);
			}
		});

		// Click on footer fixed checkbox
		$(".footer-fixed-checkbox").click(function(e)
		{
			if($(".footer-fixed-checkbox").prop("checked"))
			{
				$(".page-footer").addClass("footer-fixed").removeClass("footer-static");
			}
			else
			{
				$(".page-footer").removeClass("footer-fixed").addClass("footer-static");
			}
		});

		//Function to set footer dark checkbox
		function footerDark(isDark)
		{
			if(isDark)
			{
				$(".footer-dark-checkbox").prop("checked", true);
				$(".page-footer").removeClass("footer-light").addClass("footer-dark");
			}
			else
			{
				$(".footer-dark-checkbox").prop("checked", false);
				$(".page-footer").addClass("footer-light").removeClass("footer-dark");
			}
		}

		//Function to remove default color
		function removeColorClass(el)
		{
			$(el).removeClass("gradient-45deg-indigo-blue gradient-45deg-purple-deep-orange gradient-45deg-light-blue-cyan gradient-45deg-purple-amber gradient-45deg-purple-deep-purple gradient-45deg-deep-orange-orange gradient-45deg-green-teal gradient-45deg-indigo-light-blue gradient-45deg-red-pink red purple pink deep-purple cyan teal light-blue amber darken-3 brown darken-2 gradient-45deg-indigo-purple gradient-45deg-deep-purple-blue");
		}

	});

}

// /*================================================================================
// Item Name: Materialize - Material Design Admin Template
// Version: 4.0
// Author: PIXINVENT
// Author URL: https://themeforest.net/user/pixinvent/portfolio
// ================================================================================*/
// function Materializecss()
// {
//
// /*Preloader*/
// $(window).on('load', function()
// {
// setTimeout(function()
// {
// $('body').addClass('loaded');
// }, 200);
// });
//
// $(function()
// {
//
// "use strict";
//
// var window_width = $(window).width();
//
// /** Scrollpsy **/
// $('.scrollspy').scrollSpy();
//
// /** Ampliar imagens **/
// $('.materialboxed').materialbox();
//
// /** Floating Action Buttons - Botões de ação **/
// $('.fixed-action-btn').floatingActionButton(
// {
// direction : typeof $(this).data('direction') !== 'undefined' &&
// $(this).data('direction') !== null ? $(this).data('direction') : 'top',
// hoverEnabled : false
// });
//
// /** Collapsible - Recolher menus **/
// $('.collapsible').collapsible();
//
// if($('.sidenav').length)
// {
// /** Sidenav - Para barras de menus escondidas **/
// $('.sidenav').sidenav();
// // $('body').perfectScrollbar();
// // or just with selector string
// // var ps = new PerfectScrollbar('html');
// var ps = new PerfectScrollbar('.sidenav');
// }
//
// // // Search class for focus
// // $('.header-search-input').focus(function()
// // {
// // $(this).parent('div').addClass('header-search-wrapper-focus');
// // }).blur(function()
// // {
// // $(this).parent('div').removeClass('header-search-wrapper-focus');
// // });
// //
// // // Check first if any of the task is checked
// // $('#task-card input:checkbox').each(function()
// // {
// // checkbox_check(this);
// // });
// //
// // // Task check box
// // $('#task-card input:checkbox').change(function()
// // {
// // checkbox_check(this);
// // });
// //
// // // Check Uncheck function
// // function checkbox_check(el)
// // {
// // if( !$(el).is(':checked'))
// // {
// // $(el).next().css('text-decoration', 'none');
// // // or addClass
// // }
// // else
// // {
// // $(el).next().css('text-decoration', 'line-through');
// // //or addClass
// // }
// // }
// //
// // // Plugin initialization
// //
// // $('select').material_select();
// // // Set checkbox on forms.html to indeterminate
// // var indeterminateCheckbox =
// document.getElementById('indeterminate-checkbox');
// // if(indeterminateCheckbox !== null)
// // indeterminateCheckbox.indeterminate = true;
// //
// // Commom, Translation & Horizontal Dropdown
// // $('.dropdown-button, .translation-button, .dropdown-menu').dropdown(
// // {
// // inDuration : 300,
// // outDuration : 225,
// // constrainWidth : false,
// // hover : true,
// // gutter : 0,
// // belowOrigin : true,
// // alignment : 'left',
// // stopPropagation : false
// // });
// // Notification, Profile & Settings Dropdown $('.notification-button,
// // .profile-button, .dropdown-settings').dropdown(
// $('.dropdown-trigger').dropdown(
// {
// inDuration : 150,
// outDuration : 150,
// constrainWidth : false,
// hover : false,
// gutter : 0,
// belowOrigin : true,
// stopPropagation : false
// });
// //
// // // Materialize scrollSpy
// // $('.scrollspy').scrollSpy();
// //
// // // Materialize tooltip
// // $('.tooltipped').tooltip(
// // {
// // delay : 0
// // });
// //
// // //Main Left Sidebar Menu
// // $('.sidebar-collapse').sideNav(
// // {
// // edge : 'left', // Choose the horizontal origin
// // });
// //
// // // Overlay Menu (Full screen menu)
// // $('.menu-sidebar-collapse').sideNav(
// // {
// // menuWidth : 240,
// // edge : 'left', // Choose the horizontal origin
// // //closeOnClick:true, // Set if default menu open is true
// // menuOut : false // Set if default menu open is true
// // });
// //
// // //Main Left Sidebar Chat
// // $('.chat-collapse').sideNav(
// // {
// // menuWidth : 300,
// // edge : 'right',
// // });
// //
// // // Pikadate datepicker
// // $('.datepicker').pickadate(
// // {
// // selectMonths : true, // Creates a dropdown to control month
// // selectYears : 15 // Creates a dropdown of 15 years to control year
// // });
// //
// // // Perfect Scrollbar
// // $('select').not('.disabled').material_select();
// // var leftnav = $(".page-topbar").height();
// // var leftnavHeight = window.innerHeight - leftnav;
// // if( !$('#slide-out.leftside-navigation').hasClass('native-scroll'))
// // {
// // $('.leftside-navigation').perfectScrollbar(
// // {
// // suppressScrollX : true
// // });
// // }
// // var righttnav = $("#chat-out").height();
// // $('.rightside-navigation').perfectScrollbar(
// // {
// // suppressScrollX : true
// // });
// //
// // Fullscreen
// function toggleFullScreen()
// {
// if((document.fullScreenElement && document.fullScreenElement !== null) || (
// !document.mozFullScreen && !document.webkitIsFullScreen))
// {
// if(document.documentElement.requestFullScreen)
// {
// document.documentElement.requestFullScreen();
// }
// else if(document.documentElement.mozRequestFullScreen)
// {
// document.documentElement.mozRequestFullScreen();
// }
// else if(document.documentElement.webkitRequestFullScreen)
// {
//
document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
// }
// }
// else
// {
// if(document.cancelFullScreen)
// {
// document.cancelFullScreen();
// }
// else if(document.mozCancelFullScreen)
// {
// document.mozCancelFullScreen();
// }
// else if(document.webkitCancelFullScreen)
// {
// document.webkitCancelFullScreen();
// }
// }
// }
//
//

// $('.toggle-fullscreen').click(function()
// {
// toggleFullScreen();
// });
//
// // Toggle Flow Text
// var toggleFlowTextButton = $('#flow-toggle')
// toggleFlowTextButton.click(function()
// {
// $('#flow-text-demo').children('p').each(function()
// {
// $(this).toggleClass('flow-text');
// })
//
// });

// if((document.fullScreenElement && document.fullScreenElement !== null) || (
// !document.mozFullScreen && !document.webkitIsFullScreen))
// toggleFullScreen();
//
// // Fullscreen
// function toggleFullScreen()
// {
//
// if((document.fullScreenElement && document.fullScreenElement !== null) || (
// !document.mozFullScreen && !document.webkitIsFullScreen))
// {
// if(document.documentElement.requestFullScreen)
// {
// document.documentElement.requestFullScreen();
// }
// else if(document.documentElement.mozRequestFullScreen)
// {
// document.documentElement.mozRequestFullScreen();
// }
// else if(document.documentElement.webkitRequestFullScreen)
// {
// document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
// }
// else if(document.documentElement.msRequestFullscreen)
// {
// if(document.msFullscreenElement)
// {
// document.msExitFullscreen();
// }
// else
// {
// document.documentElement.msRequestFullscreen();
// }
// }
// }
// else
// {
// if(document.cancelFullScreen)
// {
// document.cancelFullScreen();
// }
// else if(document.mozCancelFullScreen)
// {
// document.mozCancelFullScreen();
// }
// else if(document.webkitCancelFullScreen)
// {
// document.webkitCancelFullScreen();
// }
// }
// }
//
//
// $(".toggle-fullscreen").click(function()
// {
// toggleFullScreen();
// });
//
// // Detect touch screen and enable scrollbar if necessary
// function is_touch_device()
// {
// try
// {
// document.createEvent("TouchEvent");
// return true;
// }
// catch (e)
// {
// return false;
// }
// }
//
// if(is_touch_device())
// {
// $("#nav-mobile").css(
// {
// overflow : "auto"
// });
// }
//
// resizetable();
//
// function resizetable()
// {
// if($(window).width() < 976)
// {
// if($('.vertical-layout.vertical-gradient-menu .sidenav-dark
// .brand-logo').length > 0)
// {
// $('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo
// img').attr('src', BASEPATH + 'img/logo-blue.png');
// }
// if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length >
// 0)
// {
// $('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo
// img').attr('src', BASEPATH + 'img/logo-blue.png');
// }
// if($('.vertical-layout.vertical-modern-menu .sidenav-light
// .brand-logo').length > 0)
// {
// $('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo
// img').attr('src', BASEPATH + 'img/logo-white.png');
// }
// }
// else
// {
// if($('.vertical-layout.vertical-gradient-menu .sidenav-dark
// .brand-logo').length > 0)
// {
// $('.vertical-layout.vertical-gradient-menu .sidenav-dark .brand-logo
// img').attr('src', BASEPATH + 'img/logo-white.png');
// }
// if($('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo').length >
// 0)
// {
// $('.vertical-layout.vertical-dark-menu .sidenav-dark .brand-logo
// img').attr('src', BASEPATH + 'img/logo-white.png');
// }
// if($('.vertical-layout.vertical-modern-menu .sidenav-light
// .brand-logo').length > 0)
// {
// $('.vertical-layout.vertical-modern-menu .sidenav-light .brand-logo
// img').attr('src', BASEPATH + 'img/logo-blue.png');
// }
// }
// }
//
// resizetable();
//
// // // Detect touch screen and enable scrollbar if necessary
// // function is_touch_device()
// // {
// // try
// // {
// // document.createEvent("TouchEvent");
// // return true;
// // }
// // catch (e)
// // {
// // return false;
// // }
// // }
// //
// // if(is_touch_device())
// // {
// // $('#nav-mobile').css(
// // {
// // overflow : 'auto'
// // })
// // }
// //
// // // Search class for focus
// // $(".header-search-input").focus(function()
// // {
// // $(this).parent("div").addClass("header-search-wrapper-focus");
// // }).blur(function()
// // {
// // $(this).parent("div").removeClass("header-search-wrapper-focus");
// // });
// //
// // //Search box form small screen
// // $(".search-button").click(function(e)
// // {
// // if($(".search-sm").is(":hidden"))
// // {
// // $(".search-sm").show();
// // $(".search-box-sm").focus();
// // }
// // else
// // {
// // $(".search-sm").hide();
// // $(".search-box-sm").val("");
// // }
// // });
// //
// // $(".search-sm-close").click(function(e)
// // {
// // $(".search-sm").hide();
// // $(".search-box-sm").val("");
// // });
//
// });
//
// }