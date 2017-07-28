<?php
$app = new Slim\App();

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

