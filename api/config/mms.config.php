<?php
/**
 * Mms settings
 */

$gMmsHome = "D:/git/lims/dist";
if(!$gMmsHome)
{
    echo 'env "COLLECTOR_IED_PATH" cannot be found!';
    exit ;
}
$gMmsEtcHome = $gMmsHome."/etc/";
$gMmsBinHome = $gMmsHome."/bin/";
$gMmsIcdHome = $gMmsHome."/icd/";
$gMmsDocHome = $gMmsHome."/doc/";
$gMmsUpdateHome = $gMmsHome."/update/";

$gMmsConfigFileMap = array('mmscfg' => "mms_config.xml",
                           'logcfg' => "log_config.xml");

function GetCfgDb()
{
    $g_cfgMonitorDb = new medoo([
        'database_type' => 'sqlite',
        'database_file' => API.'config' . DS .'cfg.sqlite3'
    ]);
    return $g_cfgMonitorDb;
}
function GetIedDataDb()
{
    $g_iedDataDb = new medoo([
        'database_type' => 'sqlite',
        'database_file' => API.'config' . DS .'ied_data.sqlite3'
    ]);
    return $g_iedDataDb;
}
function GetI2DataDb()
{
    $g_i2Db = new medoo([
        'database_type' => 'sqlite',
        'database_file' => API.'config' . DS .'cfg_i2.sqlite3'
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
