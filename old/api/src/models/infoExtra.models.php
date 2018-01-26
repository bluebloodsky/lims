<?php

/*
$g_cfgI2Db = new medoo([
    'database_type' => 'sqlite',
    'database_file' => 'E:\isoms_web\api\config\cfg_i2.sqlite3'
]);
*/

class SensorExtra
{
    static public function GetAllSensorTypes()
    {
        $cfg_db = GetCfgDb();
        $q = $cfg_db->select('sen_type_model', ['sen_type']);
        $resp = array_values($q);
        return [$resp];
    }

    static public function GetMapParams()
    {
        $cfg_db = GetCfgDb();
        $resp = [];
        
        //从数据库读取所有设备型号
        $q = $cfg_db->select('sen_type_model', ['sen_type']);
        $map_sen_type = [];
        /*foreach ($q as $row) {
            array_push($map_sen_type, $row['sen_type']);
        }
        $resp["map_sen_type"] = $map_sen_type;
        */
        $resp["map_sen_type"] = array_map(function($row){
            return $row["sen_type"];
        } , $q );


        //从数据库读取所有监测类型
        $q = $cfg_db->select('ln_class_model', ['ln_class' , 'desc_cn']);

        foreach ($q as $row) {
            $resp["map_ln_class"][$row['ln_class']] = $row['desc_cn'];
        }

        //从数据库读取所有通信类型
        $q = $cfg_db->select('commu_type_model', ['commu_type' , 'commu_type_cn']);
        foreach ($q as $row) {
            $resp["map_commu_type"][$row['commu_type']] = $row['commu_type_cn'];
        }

        //从数据库中读取设备属性模版
        $q = $cfg_db->select('sen_attr_model', ['ln_class','sen_type','attr','desc_cn','attr_def_val']);
        $map_sen_attr = [] ;
        foreach ($q as $row) {
            $location = -1 ;
            for($i = 0 , $arrlength=count($map_sen_attr); $i < $arrlength ; $i++){
                if($map_sen_attr[$i]["ln_class"] == $row["ln_class"] && $map_sen_attr[$i]["sen_type"] == $row["sen_type"]){
                    $location = $i ;
                    break;
                }
            }            
            if($location == -1){
                $item["ln_class"] = $row["ln_class"] ;
                $item["sen_type"] = $row["sen_type"] ;
                unset($row["ln_class"]);
                unset($row["sen_type"]);
                $item["attrs"] = [] ;
                array_push($item["attrs"],$row);
                //array_push($item["attrs"], ["attr" =>$row["attr"] , "desc_cn" =>$row["desc_cn"] ,  "attr_def_val" =>$row["attr_def_val"] ]);
                array_push($map_sen_attr, $item);
            }
            else{
                unset($row["ln_class"]);
                unset($row["sen_type"]);
                $map_sen_attr[$location]["attrs"][] = $row;
            }
        }
        $resp["map_sen_attr"] = $map_sen_attr;


        //从数据库中读取设备指令模版
        $q = $cfg_db->select('sen_debug_model', ['ln_class','sen_type','cmd','desc_cn','comment','def_data'] );
        $map_sen_debug = [] ;
        foreach ($q as $row) {
            $location = -1 ;
            for($i = 0 , $arrlength=count($map_sen_debug); $i < $arrlength ; $i++){
                if($map_sen_debug[$i]["ln_class"] == $row["ln_class"] && $map_sen_debug[$i]["sen_type"] == $row["sen_type"]){
                    $location = $i ;
                    break;
                }
            }
            if($location == -1){
                $item["ln_class"] = $row["ln_class"] ;
                $item["sen_type"] = $row["sen_type"] ;
                unset($row["ln_class"]);
                unset($row["sen_type"]);
                $item["attrs"] = [] ;
                array_push($item["attrs"],$row);
                array_push($map_sen_debug, $item);
            }
            else{
                unset($row["ln_class"]);
                unset($row["sen_type"]);
                array_push($map_sen_debug[$location]["attrs"],$row);
            }
        }
        $resp["map_sen_debug"] = $map_sen_debug;

        $resp["map_cdc_type"] = $cfg_db->select('cdc_type_model', ['cdc_type_id','cdc_type_name','desc_cn']);

        //i2基本配置数据
        $i2_db = GetI2DataDb();
        $resp["map_i2_group"] = $i2_db->select("i2_group_tbl",
            ["group_id" , "ln_name" , "group_name" , "group_code" ],[  "ORDER" => "group_id"]);

        $q = $i2_db->select("phase_tbl",["phase_id" , "phase_name"],[  "ORDER" => "phase_id"]);
        foreach ($q as $row) {
            $resp["map_phase"][$row['phase_id']] = $row['phase_name'];
        }

        $resp["map_i2_debug"] = $i2_db->select("i2_debug_model",["cmd","desc_cn"]);

        $resp["map_i2_data_type"] = $i2_db->select("data_type_tbl",["data_type_id","data_type_name"]);
        //
        return $resp;
    }
    static public function GetAllAttrTemplates()
    {
        $cfg_db = GetCfgDb();
        $q = $cfg_db->select('sen_attr_model', ['ln_class','sen_type','attr','desc_cn','attr_def_val']);
        $resp = array_values($q);
        return $resp;
    }
    static public function GetAllLnClasses()
    {
        $cfg_db = GetCfgDb();
        $q = $cfg_db->select('ln_class_model', ['ln_class', 'desc_cn']);
        return $q;
    }
    static public function AddLnClass($ln_class)
    {
        $cfg_db = GetCfgDb();
        $ln_class = array_filter($ln_class , function($key){
            return in_array($key , ['ln_class', 'desc_cn']);
        },ARRAY_FILTER_USE_KEY);
        $new_id = $cfg_db->insert('ln_class_model',$ln_class);
        return ['new_id'=>$new_id];
    }
    /*
    static public function GetAllLnClasses()
    {
        $cfg_db = GetCfgDb();
        $q = $cfg_db->select('sen_attr_model', ['ln_class', 'sen_type', 'attr', 'attr_desc_cn', 'attr_def_val']);
        $resp = [];
        foreach ($q as $row) {
            $lnclass = $row['ln_class'];

            $resp[$lnclass] = ['ln_class'=>$lnclass, 'sen_type'=>$row['sen_type']];
            $resp[$lnclass]['items'][] =
                ['attr'=>$row['attr'], 'desc_cn'=>$row['attr_desc_cn'], 'def_val'=>$row['attr_def_val']];
        }
        return array_values($resp);
    }
    */
}

class TimeExtra
{
    static public function GetSysTime()
    {
        $time = time();
        return ['epoch'=>$time];
    }
    static public function SetSysTime($epoch)
    {
        date_default_timezone_set("Asia/Shanghai");
        $str  = "date +%s -s @$epoch";
        exec($str);
        $rst=exec("hwclock -w");
      //  exec('/sbin/reboot');
        return ["info"=>$rst];
    }
}


class IedExtra
{
    static public function SetIedReset()
    {
        $rst=exec('/sbin/reboot');
        return ["result"=>$rst];
    }
}

class NetExtra
{
    static public function GetNetPara()
    {
        $para=array();
        $idx=-1;
        global $gNetCfgFilePath;
        $path=$gNetCfgFilePath;
        if(file_exists($path))
        {
            $file = fopen($path, "r") or exit("Unable to open network config file!");
            while(!feof($file))
            {
                $row= trim(fgets($file));
                if((!empty($row))&&(strpos($row,"#")!==0))
                {
                    $arr=explode(" ",$row);
                    $arr=array_filter($arr);
                    $cnt=count($arr);
                    if($cnt<=0)
                    {
                        continue;
                    }
                    if(strtolower($arr[$cnt-1]) =="static")
                    {
                        $idx++;
                        $para[$idx]=array();
                        $para[$idx]["eth"]=$arr[1];
                    }
                    if(strtolower($arr[0])=="address"||strtolower($arr[0])=="netmask"||strtolower($arr[0])=="gateway")
                    {
                        $para[$idx][$arr[0]]=$arr[1];
                    }
                }
            }
            fclose($file);
        }
        else
        {
            error_log("network config file not find!");
            throw new Exception("network config file not find!");
        }
        return $para;
    }

    static public function SetNetPara($para)
    {
        global $gNetCfgFilePath;
        $path=$gNetCfgFilePath;
        $rows=array();
        $find=false;
        $have=false;
        $new_str="";
        if(file_exists($path))
        {
            $file = fopen($path, "r") or exit("Unable to open network config file!");
            while(!feof($file))
            {
                $row= trim(fgets($file));
                if(strstr($row,"static"))
                {
                     $find=(strstr($row,$para["eth"])!=false);
                     if(strstr($row,$para["eth"]))
                     {
                         $have=true;
                     }
                }
                if($find)
                {
                     $arr=explode(' ',$row);
                     if(count($arr)>0)
                     {
                         $key=strtolower($arr[0]);
                         if($key=="address"||$key=="netmask"||$key=="gateway")
                         {
                             if($para[$key]!=null&&trim($para[$key])!="")
                             {
                                 $row=$key." ".$para[$key];  //新行
                             }
                         }
                     }
                }
                $rows[]=$row;
            }

            fclose($file);  //关闭文件

            if(!$have)  //不存在则添加
            {
                $rows[]="auto ".$para["eth"];
                $rows[]="iface ".$para["eth"]." inet static";
                $rows[]="address ".$para["address"];
                $rows[]="netmask ".$para["netmask"];
                $rows[]="gateway ".$para["gateway"];
            }
            for($i=0;$i<count($rows);$i++)
            {
                $new_str.=$rows[$i];
                $new_str.="\n";
            }
            $size=file_put_contents($path,$new_str);  //写入新数据
        //    exec('/sbin/reboot');  //重启
            return ["writtened"=>$size];
        }
        else
        {
            error_log("network config file not find!");
            throw new Exception("network config file not find!");
        }
    }

}

class ProcAttrsExtra
{
    static public function GetAllProcAttrs()
    {
        $cfg_db = GetCfgDb();
        $q = $cfg_db->select('sys_cfg_tbl', ['key', 'value', 'desc_cn']);
        $resp = [];
        foreach ($q as $row) {
            array_push($resp, ['key' => $row['key'], 'desc_cn' => $row['desc_cn'], 'value' => $row['value']]);
        }
        return $resp;
    }

    static public function SetProcAttrs($attrs)
    {
        $cfg_db = GetCfgDb();
        $cfg_db->pdo->beginTransaction();
        foreach($attrs as $key=>$value)
        {
            $cfg_db->update('sys_cfg_tbl',
                            ['value' => $value],
                            ['key' => $key]
                        );
        }

        $cfg_db->pdo->commit();
        return ['ret' => 'success'];
        //return json_encode($resp);
    }

}

class LogContentExtra
{
    static public function GetLogContent($type, $start, $limit)
    {
        if($limit<=0)
            $limit = 200;

        try{
            $logFileName ="";
            if($type == "ied")
            {
                $logdirAndFile = gGetLogDirAndFileName();
                $logFileName = $logdirAndFile['logDir']."/".$logdirAndFile['logFile'];
                global $gMmsHome;
                chdir($gMmsHome);
            }
            else    //if($type == "i2")
            {
                global $gI2LogFileName;
                $logFileName = $gI2LogFileName;
            }
            if($start == 0)
            {   //if $start is 0, means the current read, return the last $limit lines
                $lineCount=0;
                /*
                while (!$fp->eof())
                {
                    $fp->next();
                    ++$lineCount;
                }
                */
                $lineCount = rtrim(`cat $logFileName|wc -l`);

                $start = $lineCount>$limit ? ($lineCount-$limit) : 0;
            }

            $lines = [];
            $lastLine = 0;
            $fp = new SplFileObject($logFileName);
            $fp->seek($start);     // Seek to line no. 10,000
            while($limit-- && !$fp->eof())
            {
                $lines[] =  rtrim($fp->current());
                $lastLine = $fp->key();
                $fp->next();
            }
        }catch (Exception $e){
            error_log('Caught exception:'.$e->getMessage());
            throw $e;
        }

        // fix the last line empty bug
        if(strlen(end($lines)) == 0)
            array_pop($lines);
        return ['last_line'=>$lastLine, 'log_content' => $lines];
    }
}

class NtpCfgExtra
{
    static public function GetNtpInfo()
    {
        global $gMmsEtcHome;
        $ntpFile = $gMmsEtcHome."ntp.ini";
        $arr = parse_ini_file($ntpFile);
        unset($arr['rtc_dev']);
        return $arr;
    }

    static private function WriteNtpFile($arr, $fileName)
    {
        $res = [];
        foreach($arr as $key => $val)
        {
            if(is_array($val))
            {
                $res[] = "[$key]";
                foreach($val as $skey => $sval)
                {
                    $res[] = "$skey = $sval";
                    //$res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
                }
            }
            else
                $res[] = "$key = $val";
                //$res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
        }
        $dataToSave = implode("\n", $res);

        try
        {
            if($fp = fopen($fileName, 'w'))
            {
                $startTime = microtime(TRUE);
                do
                {            $canWrite = flock($fp, LOCK_EX);
                    // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
                    if(!$canWrite) usleep(round(rand(0, 100)*1000));
                } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

                //file was locked so now we can store information
                if ($canWrite)
                {
                    fwrite($fp, "[ntp]\n");
                    fwrite($fp, $dataToSave);
                    flock($fp, LOCK_UN);
                }
                fclose($fp);
            }
        }
        catch (Exception $e)
        {
            error_log("write file $fileName failed: ".$e->getMessage());
            throw $e;
        }

        return ['ret'=>'success'];
    }
    static public function SetNtpInfo($ntpInfo)
    {
        global $gMmsEtcHome;
        $ntpFile = $gMmsEtcHome."ntp.ini";
        $arr = parse_ini_file($ntpFile);
        foreach($ntpInfo as $key=>$val)
        {
            $arr[$key] = $val;
        }

        return self::WriteNtpFile($arr, $ntpFile);
    }
}

/*
class IpExtra
{
    static public function GetIpInfo()
    {

        $m = php_uname('m');
        if(!ereg('^arm', $m))
        {
            //echo '<p>the ip modify operation is only available on arm system.</p>';
            return;
        }

        exec("db -v|grep '^lan_'", $out);
        $v = [];
        foreach($out as $line)
        {
            preg_match('/^lan_(\w+)(\d+)=(.+)$/', $line, $regs);
            $v[$regs[2]][$regs[1]] = $regs[3];
        }

    }
}
*/

class DebugTemplsExtra
{
    public static function GetAllData()
    {
        $cfg_db = GetCfgDb();
        $cols = ['ln_class', 'type', 'cmd', 'desc_cn', 'comment', 'def_data'];
        $q = $cfg_db->select('debug_tmpl_tbl', $cols);
        $ret = [];
        foreach($q as $row)
        {
            $lnclass =$row['ln_class'];
            unset($row['ln_class']);
            $type =$row['type'];
            unset($row['type']);
            $unionKey = "$lnclass#$type";
            if(!array_key_exists($unionKey, $ret))
            {
               $ret[$unionKey] = ['ln_class'=>$lnclass, 'type'=>$type, 'items'=>[]];
            }
            $ret[$unionKey][items][] = $row;
        }

        return array_values($ret);
    }
}
