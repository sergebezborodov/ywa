<?php

$yii    = dirname(__FILE__).'/../../framework/yii.php';
$local  = require (dirname(__FILE__) . '/../config/local/local.php');
$config = dirname(__FILE__).'/../config/main.php';


require_once($yii);

$config = CMap::mergeArray($config, $local);

$app = Yii::createWebApplication($config);
$app->run();
