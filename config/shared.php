<?php
/**
 * Общие настройки для всех типов приложений
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__) .DS. '..'));

require_once ROOT.DS.'app' . DS.'components'.DS.'helpers'.DS.'functions.php';


return array(
	'basePath'    => ROOT.DS.'app',
	'name'        => 'My Web Application',
    'runtimePath' => ROOT . DS . 'tmp',

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.components.*',
        'application.components.base.*',
        'application.components.exceptions.*',
        'application.components.helpers.*',

        'application.models.*',

        'ext.logger.*',
	),

	// application components
	'components' => array(
	),

	'params' => require 'params.php',
);
