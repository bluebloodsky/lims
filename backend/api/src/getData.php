<?php
$app = new Slim\App();

foreach (glob(API . 'src' . DS . 'libs' . DS . '*.php') as $filename) {
    require_once $filename;
}


foreach (glob(API . 'src' . DS . 'models' . DS . '*.php') as $filename) {
    require_once $filename;
}

foreach(glob(API . 'src' . DS . 'routes' . DS . '*.php') as $router) {
    require_once $router;
}

/**
 * Run the application
 */
 $app->run();