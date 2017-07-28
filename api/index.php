<?php
/**
 * Define some constants
 */
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", realpath(dirname(__DIR__)) . DS);
define("API", ROOT . "api" . DS);
define("VENDORDIR", ROOT . "vendor" . DS);
define("ROUTEDIR", API . "src" . DS . "routes" . DS);
/**
 * Include autoload file
 */

if (file_exists(VENDORDIR . "autoload.php")) {
    require_once VENDORDIR . "autoload.php";
} else {
    die("<pre>Run 'composer.phar install' in root dir</pre>");
}

/**
 * Include getData file
 */
require API . 'src' . DS . 'getData.php';
/**
 * Include all files located in routes directory
 */

foreach (glob(ROUTEDIR . '*.php') as $router) {
    require_once $router;
}

/**
 * Run the application
 */

$asJsonMidd = function ($req, $resp, $next) {
    try
    {
        $resp = $next($req, $resp);
    } catch (Exception $e) {
        $resp = $resp->withJson(['error' => $e->getMessage()], 500);
        error_log("Exception occured: " . $e->getMessage());
    }
    return $resp;

};
$app->add($asJsonMidd);
$app->run();
