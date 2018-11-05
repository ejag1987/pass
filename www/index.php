<?php

use ZCode\Lighting\Application;

session_start();

require 'vendor/autoload.php';

$logger        = new \Monolog\Logger('Main');
$mainFactory   = new \ZCode\Lighting\Factory\MainFactory($logger);
$mainFactory->setConfigurationFile('framework.conf');

$app = new Application($mainFactory);
$app->render();
