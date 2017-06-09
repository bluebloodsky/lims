<?php

function gGetProcInfoArray($procName)
{
    global $gMmsHome;
    $pid  = trim(`cat $gMmsHome/pid/$procName.pid`);
    $procItem = array();
    $procItem['proc_name'] = $procName;
    $procItem['pid'] = $pid;
    $procItem['run_status'] = false;
    $procItem['run_time'] = 0;

    $procDir = "/proc/$pid";
    if( $pid && is_dir($procDir) && ($arr = stat($procDir)))
    {
        $procItem['run_status'] = true;
        $runTime_t = (time() - $arr['mtime']);
        $procItem['run_time'] = TransSec($runTime_t);
    }

    return $procItem;
}
function gGejAllProcInfoArray()
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


function gRestartProc($procName, $procStatus)
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
        $newPid = trim(`cat $home/pid/$proc.pid`);
        if( ($newPid!=$pid) && is_dir("/proc/$newPid"))
        {
            sleep(1);   #add a sleep for slow return...
            return ['status'=>'success'];
        }
    }while(1);
}
