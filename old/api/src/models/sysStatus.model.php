<?php

function TransSec($sec)
{
    $s = floor($sec%60);
    $sec /= 60;
    $m = floor($sec%60);
    $sec /= 60;
    $h = floor($sec%24);
    $sec /= 24;
    $d = floor($sec);

    return sprintf("%d days, %02d:%02d:%02d", $d, $h, $m, $s);
}

class SysInfoModel
{
    private $m_hostInfo;
    private $m_memInfo;
    private $m_diskInfo;

    function All()
    {
        $this->GetHostInfo();
        $this->GetMemoryInfo();
        $this->GetDiskInfo();

        return [ 'host_status' => $this->m_hostInfo,
            'mem_status'  => $this->m_memInfo,
            'disk_status'  => $this->m_diskInfo ];
    }

    private function GetCpuCoreNum() { return `grep -cPi '^processor\s*:\s*\d+' /proc/cpuinfo`; }

    private function GetHostInfo()
    {
        $data = `cat /proc/uptime`;

        $cores = $this->GetCpuCoreNum();
        $arr = preg_split('/\s+/', $data);
        $run = TransSec($arr[0]);
        $idle = TransSec($arr[1] / $cores);

        $this->m_hostInfo['runTime'] = $run;
        $this->m_hostInfo['freeTime'] = $idle;
        $this->m_hostInfo['cpuNum'] = $cores;
        $this->m_hostInfo['cpuFree'] = floatval(sprintf("%.2f", (100*$arr[1]/$cores)/($arr[0])));

    }

    private function GetMemoryInfo()
    {
        $arr = preg_split('/\s+/', ` head -2 /proc/meminfo |awk -vORS=' ' '{print $2}'`);
        $totalMem = sprintf('%.2f', $arr[0]/(2<<9));
        $freeMem = sprintf('%.2f', $arr[1]/(2<<9));

        $this->m_memInfo['totalMem'] = $totalMem;
        $this->m_memInfo['freeMem'] = $freeMem;
        $this->m_memInfo['freePercent'] = floatval(sprintf('%.2f', 100*$freeMem/$totalMem));
    }

    private function GetDiskInfo()
    {
        $data = `df -h`;   # -P use POSIX output format
        $arr = preg_split('/\n|\r\n/', $data);
        array_pop($arr);
        foreach($arr as $line)
        {
            $diskItem = array();
            $list = preg_split('/\s+/', $line);
            if(count($list) != 6)
                continue;

            $diskItem['fileSystem'] = trim($list[0]);
            $diskItem['size']  = trim($list[1]);
            $diskItem['used']       = trim($list[2]);
            $diskItem['avail']  = trim($list[3]);
            $diskItem['use%'] = trim($list[4]);
            $diskItem['mountedOn']  = trim($list[5]);

            $this->m_diskInfo[] = $diskItem;
        }
    }
}
