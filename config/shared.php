<?php
/**
 * Общие настройки для всех типов приложений
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) .DS. '..');

$basePath = dirname(__FILE__).DS.'..'.DS.'app';

require_once $basePath . DS.'components'.DS.'helpers'.DS.'functions.php';


return array(
	'basePath'    => $basePath,
	'name'        => 'My Web Application',
    'runtimePath' => ROOT.DS.'tmp',

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
        'input' => array(
            'class'         => 'CmsInput',
            'cleanPost'     => true,
            'cleanGet'      => false,
        ),
		'urlManager' => array(
			'urlFormat'       => 'path',
            'showScriptName'  => false,
			'rules' => array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        'db' => require_once 'local/database.php',
		'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
	),

	'params' => require_once 'params.php',
);
