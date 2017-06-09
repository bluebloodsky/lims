<?php
$app = new Slim\App();
/*use Illuminate\Database\Capsule\Manager as Capsule;


if (file_exists(API . 'config' . DS . 'database.config.php')) {
	$db_cfg = include API . "config" . DS . 'database.config.php' ;
    require_once $db_cfg;
} else {
    die("<pre>Rename 'config/database.config.php.install' to 'config/database.config.php' and configure your connection</pre>");
}
*/

$mmscfgPhp = API . 'config' . DS . 'mms.config.php';
if (file_exists($mmscfgPhp))
{
   require_once($mmscfgPhp) ;
}
else
{
    die("<pre>can't find mms config file 'mms.config.php' </pre>");
}

foreach (glob(API . 'src' . DS . 'models' . DS . '*.php') as $filename) {
    require_once $filename;
}

/**
 * Load all libs
 */
foreach (glob(API . 'src' . DS . 'libs' . DS . '*.php') as $filename) {
    require_once $filename;
}

