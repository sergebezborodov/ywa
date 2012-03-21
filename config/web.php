<?php
/**
 * Web application config
 */

return array(
    'components' => array(
        'input' => array(
            'class'         => 'CmsInput',
            'cleanPost'     => true,
            'cleanGet'      => false,
        ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
        'urlManager' => array(
            'urlFormat'      => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
    ),
);
