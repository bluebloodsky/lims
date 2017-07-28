<?php
/**
 * Mms settings
 */

$gMmsHome =  API.'config' ;
if(!$gMmsHome)
{
    echo 'env "COLLECTOR_IED_PATH" cannot be found!';
    exit ;
}

$gMmsHome =  API.'config' ;
if(!$gMmsHome)
{
    echo 'env "IED_NAND_PATH" cannot be found!';
    exit ;
}
$gMmsEtcHome = $gMmsHome."/etc/";
$gMmsBinHome = $gMmsHome."/bin/";
$gMmsIcdHome = $gMmsHome."/icd/";
$gMmsDocHome = $gMmsHome."/doc/";
$gMmsUpdateHome = $gMmsHome."/update/";
$gMmsToolHome= $gNandHome."/software/";   //add 常用工具目录
$gMmsHelpHome= $gNandHome."/help/";   //add 帮助文档目录
$gNetCfgFilePath= "/etc/network/interfaces";

$gMmsConfigFileMap = array('mmscfg' => "mms_config.xml",
                           'logcfg' => "log_config.xml");

$gI2LogFileName = $gNandHome . DS . "log/i2.log";

ini_set("error_reprorting", "E_ALL");
ini_set("log_errors", "On");
ini_set("error_log", "$gNandHome/log/php_error.log");   //此路径自行配置
date_default_timezone_set('Asia/Shanghai');

function GetCfgDb()
{
    global $gMmsEtcHome;
    $g_cfgMonitorDb = new medoo([
        'database_type' => 'sqlite',
        'database_file' => $gMmsEtcHome . DS .'cfg.sqlite3'
    ]);
    return $g_cfgMonitorDb;
}
function GetIedDataDb()
{
    global $gNandHome;
    $g_iedDataDb = new medoo([
        'database_type' => 'sqlite',
        'database_file' => "$gNandHome/db/ied_data.sqlite3"
    ]);
    return $g_iedDataDb;
}
function GetI2DataDb()
{
    global $gMmsEtcHome;
    $g_i2Db = new medoo([
        'database_type' => 'sqlite',
        'database_file' =>  $gMmsEtcHome . DS .'cfg_i2.sqlite3'
    ]);
    return $g_i2Db;
}
// public function
function gGetLogDirAndFileName()
{
    global $gMmsEtcHome;
    $mmsLogCfgFile = $gMmsEtcHome."logcfg.xml";
    $xml = simplexml_load_file($mmsLogCfgFile);
    if(!$xml)
    {
        echo "open file $mmsLogCfgFile failed.\n";
        exit;
    }

    return array('logDir'=>(string)$xml->LogControl->LogDir,
        'logFile'=>(string)$xml->LogControl->LogFileName);
}
