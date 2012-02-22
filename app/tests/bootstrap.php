<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../framework/yiit.php';
require_once($yiit);

$local  = require (dirname(__FILE__) . '/../../config/local/local.php');
$config = require(dirname(__FILE__) . '/../../config/test.php');
$shared = require(dirname(__FILE__) . '/../../config/shared.php');


require_once(dirname(__FILE__).'/WebTestCase.php');

$config = CMap::mergeArray($shared, $config);
$config = CMap::mergeArray($config, $local);
//print_r($config);die;
Yii::createWebApplication($config);
