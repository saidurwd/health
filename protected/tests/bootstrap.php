<?php

// change the following paths if necessary
$yiit='C:\Users\Saidur\Downloads\yii-1.1.16.bca042\yii-1.1.16.bca042\framework\yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
