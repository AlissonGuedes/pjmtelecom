<?php

namespace Config
{

	use CodeIgniter\Events\Events;

	/*
	 * --------------------------------------------------------------------
	 * Application Events
	 * --------------------------------------------------------------------
	 * Events allow you to tap into the execution of the program without
	 * modifying or extending core files. This file provides a central
	 * location to define your events, though they can always be added
	 * at run-time, also, if needed.
	 *
	 * You create code that can execute by subscribing to events with
	 * the 'on()' method. This accepts any form of callable, including
	 * Closures, that will be executed when the event is triggered.
	 *
	 * Example:
	 *      Events::on('create', [$myInstance, 'myMethod']);
	 */
	Events :: on('pre_system', function()
	{
		while ( \ob_get_level() > 0 )
		{
			\ob_end_flush();
		}

		\ob_start(function($buffer)
		{
			return $buffer;
		});

		/*
		 * --------------------------------------------------------------------
		 * Debug Toolbar Listeners.
		 * --------------------------------------------------------------------
		 * If you delete, they will no longer be collected.
		 */
		if ( ENVIRONMENT !== 'production' )
		{
			Events :: on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
			Services :: toolbar() -> respond();
		}
	});

	Events :: on('pre_system', function()
	{

		require_once (BASEPATH . 'functions' . DS . 'Library.php');
		require_once (BASEPATH . 'functions' . DS . 'functions' . DS . 'functions.php');
		require_once (BASEPATH . 'functions' . DS . 'Functions.php');

		request_methods();

		define_session();
		is_logged();
		redirect_to_login();

	});

}