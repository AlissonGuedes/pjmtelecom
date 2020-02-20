<?php

namespace Config
{

	/**
	 * --------------------------------------------------------------------
	 * URI Routing
	 * --------------------------------------------------------------------
	 * This file lets you re-map URI requests to specific
	 * controller functions.
	 *
	 * Typically there is a one-to-one relationship between a URL
	 * string
	 * and its corresponding controller class/method. The
	 * segments in a
	 * URL normally follow this pattern:
	 *
	 *    example.com/class/method/id
	 *
	 * In some instances, however, you may want to remap this
	 * relationship
	 * so that a different class/function is called than the one
	 * corresponding to the URL.
	 */

	// Create a new instance of our RouteCollection class.
	$routes = Services :: routes(true);

	// Load the system's routing file first, so that the app and
	// ENVIRONMENT
	// can override as needed.
	if ( file_exists(SYSTEMPATH . 'Config/Routes.php') )
	{
		require SYSTEMPATH . 'Config/Routes.php';
	}

	/**
	 * --------------------------------------------------------------------
	 * Router Setup
	 * --------------------------------------------------------------------
	 * The RouteCollection object allows you to modify the way
	 * that the
	 * Router works, by acting as a holder for it's configuration
	 * settings.
	 * The following methods can be called on the object to
	 * modify
	 * the default operations.
	 *
	 *    $routes->defaultNamespace()
	 *
	 * Modifies the namespace that is added to a controller if it
	 * doesn't
	 * already have one. By default this is the global namespace
	 * (\).
	 *
	 *    $routes->defaultController()
	 *
	 * Changes the name of the class used as a controller when
	 * the route
	 * points to a folder instead of a class.
	 *
	 *    $routes->defaultMethod()
	 *
	 * Assigns the method inside the controller that is ran when
	 * the
	 * Router is unable to determine the appropriate method to
	 * run.
	 *
	 *    $routes->setAutoRoute()
	 *
	 * Determines whether the Router will attempt to match URIs
	 * to
	 * Controllers when no specific route has been defined. If
	 * false,
	 * only routes that have been defined here will be available.
	 */
	$routes -> setDefaultNamespace('App\Controllers');
	$routes -> setDefaultController('Home');
	$routes -> setDefaultMethod('index');
	$routes -> setTranslateURIDashes(false);
	$routes -> set404Override();
	$routes -> setAutoRoute(true);

	/**
	 * --------------------------------------------------------------------
	 * Route Definitions
	 * --------------------------------------------------------------------
	 */

	// We get a performance increase by specifying the default
	// route since we don't have to scan directories.
	// $routes->get('/', 'Home::index');
	// $routes -> get('/admin', 'Home::index');

	$routes -> group('/admin', function($routes)
	{

		// Raiz
		$routes -> get('/', 'Home::index');
		$routes -> get('dashboard', 'Home::index');

		// Login
		$routes -> get('login', 'Account::index');
		$routes -> add('login/(.+)', 'Account::index');
		$routes -> post('login', 'Account::auth');
		$routes -> put('login', 'Account::auth');
		$routes -> get('logout', 'Account::logout');

		// Banners
		$routes -> group('banners', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Banners::index');
			$routes -> get('add', 'Banners::show_form');

			// Select
			$routes -> get(':num', 'Banners::show_form');
			$routes -> post('index', 'Banners::datatable');

			// Insert
			$routes -> post('/', 'Banners::create');

			// Update
			$routes -> put('/', 'Banners::update');
			$routes -> patch('/', 'Banners::update');

			// Delete
			$routes -> delete('/', 'Banners::delete');

		});

		// Planos
		$routes -> group('planos', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Planos::index');
			$routes -> get('add', 'Planos::show_form');

			// Select
			$routes -> get(':num', 'Planos::show_form');
			$routes -> post('index', 'Planos::datatable');

			// Insert
			$routes -> post('/', 'Planos::create');

			// Update
			$routes -> put('/', 'Planos::update');
			$routes -> patch('/', 'Planos::update');

			// Delete
			$routes -> delete('/', 'Planos::delete');

		});

		// Comunicados
		$routes -> group('comunicados', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Comunicados::index');
			$routes -> get('add', 'Comunicados::show_form');

			// Select
			$routes -> get(':num', 'Comunicados::show_form');
			$routes -> post('index', 'Comunicados::datatable');

			// Insert
			$routes -> post('/', 'Comunicados::create');

			// Update
			$routes -> put('/', 'Comunicados::update');
			$routes -> patch('/', 'Comunicados::update');

			// Delete
			$routes -> delete('/', 'Comunicados::delete');

		});

		// Faqs
		$routes -> group('faqs', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Faqs::index');
			$routes -> get('add', 'Faqs::show_form');

			// Select
			$routes -> get(':num', 'Faqs::show_form');
			$routes -> post('index', 'Faqs::datatable');

			// Insert
			$routes -> post('/', 'Faqs::create');

			// Update
			$routes -> put('/', 'Faqs::update');
			$routes -> patch('/', 'Faqs::update');

			// Delete
			$routes -> delete('/', 'Faqs::delete');

		});

		// Empresa >>> Configurações
		$routes -> group('empresa', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Configuracoes::index');
			$routes -> get('add', 'Configuracoes::show_form');

			// Select
			$routes -> get(':num', 'Configuracoes::show_form');
			$routes -> post('index', 'Configuracoes::datatable');

			// Insert
			$routes -> post('/', 'Configuracoes::create');

			// Update
			$routes -> put('/', 'Configuracoes::update');
			$routes -> patch('/', 'Configuracoes::update');

			// Delete
			$routes -> delete('/', 'Configuracoes::delete');

		});

		// Usuários
		$routes -> group('usuarios', function($routes)
		{

			// Exibe a página
			$routes -> get('/', 'Usuarios::index');
			$routes -> get('add', 'Usuarios::show_form');

			// Select
			$routes -> get(':num', 'Usuarios::show_form');
			$routes -> post('index', 'Usuarios::datatable');

			// Insert
			$routes -> post('/', 'Usuarios::create');

			// Update
			$routes -> put('/', 'Usuarios::update');
			$routes -> patch('/', 'Usuarios::update');

			// Delete
			$routes -> delete('/', 'Usuarios::delete');
			$routes -> delete('bairros', 'Usuarios::delete_bairros');

		});

		/** Ajax **/
		$routes -> add('cidades', 'Ajax::busca_cidades');
		$routes -> add('bairros', 'Ajax::busca_bairros');

	});

	/**
	 * --------------------------------------------------------------------
	 * Additional Routing
	 * --------------------------------------------------------------------
	 *
	 * There will often be times that you need additional routing
	 * and you
	 * need to it be able to override any defaults in this file.
	 * Environment
	 * based routes is one such time. require() additional route
	 * files here
	 * to make that happen.
	 *
	 * You will have access to the $routes object within that
	 * file without
	 * needing to reload it.
	 */
	if ( file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php') )
	{
		require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
	}

}
