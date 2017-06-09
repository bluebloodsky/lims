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
        date_default_timezone_set("Etc/GMT");
        $str  = "date +%s -s @$epoch";
        exec($str);
        exec("hwclock -w");
        exec('/sbin/reboot');

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
    static public function GetLogContent($start, $limit)
    {
        $logdirAndFile = gGetLogDirAndFileName();
        $logFileName = $logdirAndFile['logDir']."/".$logdirAndFile['logFile'];
        try{
            global $gMmsHome;
            chdir($gMmsHome);
            $fp = new SplFileObject($logFileName);
            $lines = [];
            $fp->seek($start);     // Seek to line no. 10,000
            while($limit-- && !$fp->eof())
            {
                $lines[] = $fp->current();
                $fp->next();
            }
        }catch (Exception $e){
            error_log('Caught exception:'.$e->getMessage());
            throw $e;
        }

        return ['log_content' => $lines];
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
