<?php

function gGetProcInfoArray($procName)
{
    global $gMmsHome;
    $pid  = trim(`cat $gMmsHome/pid/$procName.pid`);
    $procItem = array();
    $procItem['proName'] = $procName;
    $procItem['pid'] = $pid;
    $procItem['runStatus'] = 'die';
    $procItem['runTime'] = '';

    $procDir = "/proc/$pid";
    if( $pid && is_dir($procDir) && ($arr = stat($procDir)))
    {
        $procItem['runStatus'] = 'alive';
        $runTime_t = (time() - $arr['mtime']);
        $procItem['runTime'] = TransSec($runTime_t);
    }

    return $procItem;
}
function gGetAllProcInfoArray()
{
    global $gMmsHome;
    $procInfoList = array();
    foreach(array('proc', 'ntp') as $arg)
    {
        $arg = 'show'.$arg;
        $procName = trim(`sh $gMmsHome/monitor.sh $arg`);
        array_push($procInfoList, gGetProcInfoArray($procName));
    }

    return $procInfoList;
}


function gRestartProc($procName)
{
    global $gMmsHome;
    $pid  = trim(`cat $gMmsHome/pid/$procName.pid`);

    exec("kill -9 $pid", $output, $ret);
    if($ret!=0)
    {
        $msg = "kill proce $procName[$pid] failed";
        error_log($msg."ret: $ret");
        throw new Exception($msg);
    }

    do {
        sleep(3);
        $newPid = trim(`cat $gMmsHome/pid/$procName.pid`);
        if( ($newPid!=$pid) && is_dir("/proc/$newPid"))
        {
            sleep(1);   #add a sleep for slow return...
            return ['status'=>'success'];
        }
    }while(1);
}
