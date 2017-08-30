<?php

require __DIR__."/../vendor/autoload.php";

$app = new Silex\Application();
$app->register(new Application\Provider\ApplicationProvider());

$app->run();