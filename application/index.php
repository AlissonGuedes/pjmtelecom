<?php

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Recife');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Valid PHP Version?
$minPHPVersion = '7.2';

if (phpversion() < $minPHPVersion) {
    die("Your PHP version must be {$minPHPVersion} or higher to run CodeIgniter. Current version: " . phpversion());
}

unset($minPHPVersion);

//******************************************//

set_include_path(dirname(__FILE__));
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system"
 * directory.
 * Set the path if it is not in the same directory as this
 * file.
 */
$system_path = get_include_path() . DS . 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different
 * "application"
 * directory than the default one you can set its name here.
 * The directory
 * can also be renamed or relocated anywhere on your server.
 * If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */

if (! isset($path))
    $path = '_production';

$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = ! empty($url) ? explode('/', $url) : '';
$url = ! empty($url) ? get_include_path() . DS . 'app' . DS . $path . DS . strtolower($url[0]) : get_include_path() . DS . 'app' . DS . $path . DS . 'main';

$application_folder = is_dir($url) ? $url : get_include_path() . DS . 'app' . DS . $path . DS . 'main';

define('APPPATH', $application_folder . DS);

/**
 * The path to the views directory
 */
$view_folder = 'Views';

if (! isset($view_folder[0]) && is_dir('views' . DS)) {
    $view_folder = APPPATH . 'Views';
} elseif (is_dir($view_folder)) {
    if (($_temp = realpath($view_folder)) !== false) {
        $view_folder = $_temp;
    } else {
        $view_folder = strtr(rtrim($view_folder, '/\\'), '/\\', DS . DS);
    }
} elseif (is_dir(APPPATH . $view_folder . DS)) {
    $view_folder = APPPATH . strtr(trim($view_folder, '/\\'), '/\\', DS . DS);
} else {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your view folder path does not appear to be set correctly. Please, open the following file and correct this: ' . SELF;
    exit(3);
}

define('BASEPATH', get_include_path() . DS);
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . DS);
define('SYSTEMPATH', $system_path . DS);
define('FCPATH', $application_folder . DS);
define('VIEWPATH', $view_folder . DS);
define('WRITABLE', ROOTPATH . '../tmp/.writable');
define('TESTS', BASEPATH . 'tests');

// Minhas funções
define('FUNCTIONS', BASEPATH . 'functions');

// Location of the Paths config file.
// This is the line that might need to be changed, depending
// on your folder
// structure.
// ^^^ Change this if you move your application folder
$pathsPath = FCPATH . 'Config/Paths.php';

//******************************************//

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and
 * registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Ensure the current directory is pointing to the front
// controller's directory
chdir(__DIR__);

// Load our paths config file
require $pathsPath;
$paths = new Config\Paths();

// Location of the framework bootstrap file.
$app = require rtrim($paths -> systemDirectory, '/ ') . '/bootstrap.php';

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */
$app -> run();
