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
        /*
        if($req->getUri()->getPath() !="/users/validate"){
            $str_auth = $req->getHeader('Authorization');
            if($str_auth || $str_auth != ''){
                $resp = $resp->withJson(['error' => 'Unauthorized'] , 401);
                $resp = $resp->withHeader('Access-Control-Allow-Origin', '*');
                $resp = $resp->withHeader('Access-Control-Allow-Headers', 'Origin,X-Requested-With,Content-Type,Accept,Authorization');
                $resp = $resp->withHeader('Access-Control-Allow-Methods', 'GET,POST,PUT, DELETE');
                return $resp;
            }
        }
        */
        $resp = $next($req, $resp);
        $resp = $resp->withHeader('Access-Control-Allow-Origin', '*');
        $resp = $resp->withHeader('Access-Control-Allow-Headers', 'Origin,X-Requested-With,Content-Type,Accept,Authorization');
        $resp = $resp->withHeader('Access-Control-Allow-Methods', 'GET,POST,PUT, DELETE');
    } catch (Exception $e) {
        $resp = $resp->withJson(['error' => $e->getMessage()], 500);
        error_log("Exception occured: " . $e->getMessage());
    }
    return $resp;

};
$app->add($asJsonMidd);
$app->run();
