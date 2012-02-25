<?php

$yii    = dirname(__FILE__).'/../../framework/yii.php';
$local  = require (dirname(__FILE__) . '/../config/local/local.php');
$web  = require (dirname(__FILE__) . '/../config/web.php');
$shared = require dirname(__FILE__).'/../config/shared.php';


require_once($yii);

$config = CMap::mergeArray($shared, $web);
$config = CMap::mergeArray($config, $local);

$app = Yii::createWebApplication($config);

$app->run();
