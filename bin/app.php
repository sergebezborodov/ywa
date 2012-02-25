<?php

$yii    = dirname(__FILE__).'/../../framework/yii.php';
$local  = require (dirname(__FILE__) . '/../config/local/local.php');
$console = require(dirname(__FILE__) . '/../config/console.php');
$shared = require(dirname(__FILE__) . '/../config/shared.php');

require_once($yii);

$config = CMap::mergeArray($shared, $console);
$config = CMap::mergeArray($config, $local);

$app = Yii::createConsoleApplication($config);

$app->run();
