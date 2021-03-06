<?php

/*
$g_cfgI2Db = new medoo([
'database_type' => 'sqlite',
'database_file' => 'E:\isoms_web\api\config\cfg_i2.sqlite3'
]);
 */

class SensorModel
{
    public static function GetSensorsDataGroupBy($groupby, $fields, $params)
    {
        $db = GetCfgDb();
        if (array_key_exists($params, "groupby")) {
            unset($params["groupby"]);
        }

        if (array_key_exists($params, "fields")) {
            unset($params["fields"]);
        }

        $where = [];
        if (count($params)) {
            $where["AND"] = $params;
        }

        if (strlen($groupby) == 0) {
            # if no $groupby, return val no items
            $q = $db->select("sen_cfg_tbl", $fields, $where);
            return $q;
        } else {
            if (!array_key_exists($groupby, $fields)) {
                array_push($fields, $groupby);
            }
            $q        = $db->select("sen_cfg_tbl", $fields, $where);
            $retArray = [];
            foreach ($q as $row) {
                $key = $row[$groupby];
                unset($row[$groupby]);
                $retArray[$key][] = $row;
            }

            return $retArray;
        }
    }
    public static function PostSensorDebugOrder($sensorId, $params)
    {
        $cmd  = $params["cmd"];
        $data = $params["param"];
        $str  = "ln_inst:$sensorId;cmd:$cmd;data:$data";
        set_time_limit(0);
        $host       = "127.0.0.1";
        $port       = 6665;
        $socket     = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
        $connection = socket_connect($socket, $host, $port) or die("Could not connet server\n");
        socket_write($socket, $str) or die("Write failed\n");

        while ($buff = socket_read($socket, 1024, PHP_BINARY_READ)) {
            $RET = $buff;
            break;
        }
        socket_close($socket);
        return ["response" => $RET];
    }
    public static function GetSensorsDataAll()
    {
        $cfg_db = GetCfgDb();
        $q      = $cfg_db->select('sen_cfg_tbl', [
            '[>]ln_class_model'   => 'ln_class',
            '[>]commu_type_model' => 'commu_type',
            '[>]sen_attr_model'   => ['ln_class', 'sen_type'],
            '[>]sen_attr_tbl'     => ['sen_attr_model.attr' => 'key', 'sen_id' => 'sen_id'],
        ],
            ['sen_cfg_tbl.sen_id',
                'sen_cfg_tbl.sen_type',
                'sen_cfg_tbl.ln_class',
                'sen_cfg_tbl.ln_inst',
                'sen_cfg_tbl.s_addr',
                'sen_cfg_tbl.commu_type',
                'sen_cfg_tbl.desc_cn',
                'sen_attr_model.attr',
                'sen_attr_model.desc_cn(sen_attr_desc)',
                'sen_attr_tbl.value',
                'ln_class_model.desc_cn(ln_class_cn)',
                'commu_type_model.commu_type_cn']
        );
        $id2Sensor = [];
        $id2Attr   = [];

        foreach ($q as $row) {
            $sen_id = $row['sen_id'];
            if ($row['attr']) {
                $attr["attr"]       = $row['attr'];
                $attr["value"]      = $row['value'];
                $attr["desc_cn"]    = $row["sen_attr_desc"];
                $id2Attr[$sen_id][] = $attr;
            }
            if (!array_key_exists($id2Sensor, $sen_id)) {
                unset($row['attr']);
                unset($row['value']);
                unset($row['sen_attr_desc']);
                $id2Sensor[$sen_id] = $row;
            }
        };

        $resps = [];
        foreach ($id2Attr as $sen_id => $attrs) {
            $resp          = $id2Sensor[$sen_id];
            $resp['attrs'] = $attrs;
            array_push($resps, $resp);
        }

        return $resps;
    }

    public static function getSensorById($sen_id)
    {
        $cfg_db = GetCfgDb();
        $q      = $cfg_db->select("sen_cfg_tbl", "*", ["sen_id" => $sen_id]);
        return $q[0];
    }

    public static function OnAddSensor($sensor)
    {
        $attrs     = $sensor["attrs"];
        $newsensor = array_filter($sensor, function ($key) {
            return in_array($key, ['sen_type', 'ln_class', 'ln_inst', 's_addr', 'desc_cn', 'commu_type']);
        }, ARRAY_FILTER_USE_KEY);

        $db    = GetCfgDb();
        $senId = -1;
        $db->action(function ($db) use ($newsensor, $attrs, &$senId) {
            $senId = $db->insert('sen_cfg_tbl', $newsensor);
            $rows  = array_map(function ($value) use ($senId) {
                $tmp["sen_id"] = $senId;
                $tmp["key"]    = $value["attr"];
                $tmp["value"]  = $value["value"];
                return $tmp;
            }, $attrs);
            $db->insert('sen_attr_tbl', $rows);
        });
        return ["sen_id" => $senId];
    }

    public static function OnUpdateSensor($senId, $sensor)
    {
        $attrs     = $sensor["attrs"];
        $newsensor = array_filter($sensor, function ($key) {
            return in_array($key, ['sen_type', 'ln_class', 'ln_inst', 's_addr', 'desc_cn', 'commu_type']);
        }, ARRAY_FILTER_USE_KEY);

        $cfgDb = GetCfgDb();
        $cfgDb->action(function ($db) use ($newsensor, $attrs, $senId) {
            $num = $db->update('sen_cfg_tbl', $newsensor, ['sen_id' => $senId]);
            if ($num != 1) {
                throw new Exception("update sen_cfg_tbl return value $num != 1");
            }

            $rows = array_map(function ($value) use ($senId) {
                $tmp["sen_id"] = $senId;
                $tmp["key"]    = $value["attr"];
                $tmp["value"]  = $value["value"];
                return $tmp;
            }, $attrs);
            $db->delete('sen_attr_tbl', ['sen_id' => $senId]);
            $db->insert('sen_attr_tbl', $rows);
        });
    }

    public static function OnDeleteSensor($senIds)
    {
        $cfgDb   = GetCfgDb();
        $arr_ids = preg_split("/,/", $senIds);
        $cfgDb->action(function ($db) use ($arr_ids) {
            $num = 0;
            $num = $db->delete('sen_cfg_tbl', ['sen_id' => $arr_ids]);
            if ($num < 1) {
                throw new Exception("delete sen_cfg_tbl return value $num < 1");
            }

            $db->delete('sen_attr_tbl', ['sen_id' => $arr_ids]);
        });
        return ["sen_id" => $arr_ids];
    }
}

class SensorBasicStatus
{
    private static function _getSenBasicStatus($where)
    {
        $data_db = GetIedDataDb();
        $cols    = ["sen_id", "data_time", "mov_dev_conf", "sup_dev_run", "data_status"];
        if (array_key_exists($where, "top") && !array_key_exists($where["AND"], "sen_id")) {
            $top   = $where['top'];
            $where = "where (select count(*) from status_sensor as b
                where b.sen_id = status_sensor.sen_id and b.status_id > status_sensor.status_id) < $top
                order by status_sensor.sen_id asc , status_sensor.status_id desc";
        }
        $RET = $data_db->select("status_sensor", $cols, $where);
        return $RET;
        /*
        $hasDataStatus = false;
        $dataStatusFilter = 0;
        if (array_key_exists($where, 'data_status')) {
        $hasDataStatus = true;
        $dataStatusFilter = $where['data_status'];
        unset($where['data_status']);
        }

        $cfg_db = GetCfgDb();

        $q = $cfg_db->select("status_sensor", "sen_id", "data_time", "mov_dev_conf", "sup_dev_run", $where);
        $retArray = [];
         */
        /*
         * 计算流程:
         * 首先取出id,和其它信息
         * 然后根据id在cfg数据库中查出ln_class和warn/alarm属性字段cols(cdc_type=1)
         * 然后根据ln_class确定ied_data数据库中的表名
         * 通过表名和cols查出warn和alarm的值
         * 通过warn和alarm的值计算出data_status的值
         * 如果有alarm就data_status就是1,如果没有alarm有warn就是2,如果两个都没有就是0
         */
        /*
        foreach ($q as $row) {
        $dt = NULL; # data_status value
        do {
        $id = $row['sen_id'];
        $retItem = $row;
        $fields_q = $cfg_db->select('sen_cfg_tbl',      #table name
        ['[><]do_map_model' => ['ln_class', 'ln_class']],    #join, [><]means inner join
        ['do_map_model.ln_class', 'do_map_model.do_name'],  #select columns
        ['sen_cfg_tbl.sen_id' => $id, 'do_map_model.cdc_type_id' => 1]  #where clause
        );
        if (count($fields_q) == 0)
        break;

        $ln_name = $fields_q[0]['ln_class'];
        $iedDataTbl = "data_$ln_name";
        $cols = [];
        foreach ($fields_q as $field_row) {
        $cols[] = $field_row['do_name'];
        }

        $data_q = $cfg_db->select($iedDataTbl,  #tablename
        $cols,                              #select columns
        ['data_time' => $row['data_time']]  #where clause
        );
        if (count($data_q) == 0)
        break;

        $warnCols = preg_grep('/Warn/i', $cols);
        $alarmCols = preg_grep('/Alarm/i', $cols);

        $data_row = $data_q[0];
        $alarm = 0;
        foreach ($alarmCols as $ac) {
        if ($data_row[$ac]) {
        $alarm = 1;
        break;
        }
        }
        if ($alarm == 1) {
        $dt = $alarm;
        break;
        }
        $warn = 0;
        foreach ($warnCols as $wc) {
        if ($data_row[$wc]) {
        $warn = 2;
        break;
        }
        }
        $dt = $alarm;
        } while (0);

        if ($hasDataStatus && ($dataStatusFilter != $dt))
        continue;

        $retItem['data_status'] = $dt;
        array_push($retArray, $retItem);
        }
         */
        return $retArray;
    }

    public static function GetTopSenBasicStatus($top)
    {

        $data_db = GetIedDataDb();
        $cfg_db  = GetCfgDb();
        $q       = $cfg_db->select("ln_class_model", ["ln_class"]);

        $resps = [];
        if (!$top || $top < 1) {
            $top = 1;
        }

        foreach ($q as $row) {
            $table_name = "data_" . $row["ln_class"];
            //$sql = "select * from $table_name as a inner join(select max(data_time) as time , ln_inst from
            //    $table_name group by ln_inst) as tmp on a.data_time = tmp.time and a.ln_inst = tmp.ln_inst " ;
            $orderBy = "data_time";
            $sql     = "select * from $table_name as a where (select count(*) from $table_name as b
                where b.ln_inst = a.ln_inst and b.$orderBy > a.$orderBy) < $top
                order by a.ln_inst asc , a.$orderBy desc";
            $q_query = $data_db->query($sql);
            if ($q_query) {
                $datas  = [];
                $q_data = $q_query->fetchAll();
                foreach ($q_data as $item) {
                    $aData["ln_inst"]   = $item["Ln_inst"];
                    $aData["data_time"] = $item["Data_Time"];
                    unset($item["Ln_inst"]);
                    unset($item["Data_Time"]);
                    $aData["data_status"] = null;
                    foreach ($item as $key => $value) {
                        if (stristr($key, "alm")) {
                            if ($value != null) {
                                if ($value == 1) {
                                    $aData["data_status"] = "2"; //有一个参数告警即告警
                                    break;
                                } else if ($aData["data_status"] == null) {
                                    $aData["data_status"] = "0";
                                }
                            }
                        }
                        if (stristr($key, "warn")) {
                            if ($value != null) {
                                if ($value == 1 || $aData["data_status"] == null) {
                                    $aData["data_status"] = $value; //有一个参数告警即告警
                                }
                            }
                        }
                    }
                    array_push($datas, $aData);
                }
                $resps[$row["ln_class"]] = $datas;
            }
        }

        return $resps;
    }

    public static function GetAlmUntreated() //Add

    {
        $data_db = GetIedDataDb();
        $cols    = ["status_id", "sen_id", "data_time", "mov_dev_conf", "sup_dev_run", "data_status"];
        $RET     = $data_db->select("status_sensor", $cols, ["alm_ignored" => 0, "ORDER" => ["data_time" => "DESC"]]);
        return $RET;
    }

    public static function SetAlmTreated($list_id) //Add

    {
        if (count($list_id) == 0) {
            return ['list_id' => "empty"];
        }
        $data_db = GetIedDataDb();
        $where   = array("status_id" => $list_id);
        $RET     = $data_db->update("status_sensor", ["alm_ignored" => 1], $where);

        if ($RET <= 0) {
            throw new Exception("update info failed");
        } else {
            $cnt_alm  = 0;
            $list_sen = $data_db->query("select distinct sen_id from status_sensor")->fetchAll(); //所有传感器
            foreach ($list_sen as $item) {
                $id_sen = $item["sen_id"];

                $ignore = $data_db->query("select alm_ignored from status_sensor WHERE sen_id=$id_sen ORDER by status_id DESC limit 1")->fetchAll();

                if (is_numeric($ignore[0]["alm_ignored"]) && ($ignore[0]["alm_ignored"] == 0)) {
                    $cnt_alm++;
                }
            }
            if ($cnt_alm == 0) //最新状态 无报警
            {
                self::SetSoundAlmOff(); //关闭声光报警
            }
        }
        return ['mod_count' => $RET];
    }

    public static function SetSoundAlmOff() //Add

    {
        $str = "ln_inst:0;cmd:0;data:";
        set_time_limit(0);
        $host       = "127.0.0.1";
        $port       = 6665;
        $socket     = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create  socket\n");
        $connection = socket_connect($socket, $host, $port) or die("Could not connect server\n");
        socket_write($socket, $str) or die("Write failed\n");
        while ($buff = socket_read($socket, 1024, PHP_BINARY_READ)) {
            $RET = $buff;
            break;
        }
        socket_close($socket);
        return ["response" => $RET];
    }

    public static function GetSensorStatusById($id, $params)
    {
        $where["AND"] = array_filter($params, function ($key) {
            return in_array($key, ["data_status", "mov_dev_conf", "sup_dev_run"]);
        }, ARRAY_FILTER_USE_KEY);
        if ($id) {
            $where["AND"]['sen_id'] = $id;
        }

        if (array_key_exists($params, 'top')) {
            $where['top'] = $params['top'];
        }
        return self::_getSenBasicStatus($where);
    }

    public static function GetCurrentSensorStatus()
    {
        $data_db = GetIedDataDb();
        $cols    = ["sen_id", "data_time", "mov_dev_conf", "sup_dev_run", "data_status"];
        $RET     = $data_db->select("update_status", $cols);
        return $RET;
    }
}

class SensorData
{
    private static function _str2Time($str)
    {
        if (strlen($str) != 14) {
            return '0000-00-00 00:00:00';
        }

        $arr = str_split($str, 2);
        return "$arr[0]$arr[1]-$arr[2]-$arr[3] $arr[4]:$arr[5]:$arr[6]";
    }

    private static function _getSensorDataById($senId2dataLnWhere, $join_domap_where)
    {
        $cfg_db = GetCfgDb();

        $q = $cfg_db->select('sen_cfg_tbl', #table name
        ['[>]do_map_model' => ['ln_class', 'ln_class']], #join, [><]means inner join
        ['sen_cfg_tbl.sen_id', 'sen_cfg_tbl.ln_inst', 'sen_cfg_tbl.desc_cn', 'do_map_model.ln_class', 'do_map_model.do_name', 'do_map_model.desc_cn(do_name_cn)', 'do_map_model.unit', 'do_map_model.precision'], #select columns
        $join_domap_where#where clause
    );
        $resps = [];
        if ($q && count($q) > 0) {
            $sen_sources = [];
            /*{
            "SIML" :{
            "sensors" : ["1":{
            "ln_inst":2
            "desc" : "aa" }],
            "do_attrs" : ["a":{
            "unit" : "w"
            }]
            }
            }*/
            $ied_data_db = GetIedDataDb();

            foreach ($q as $row) {
                $ln_class                                            = $row["ln_class"];
                $sen_sources[$ln_class]["sensors"][$row["sen_id"]]   = ["ln_inst" => $row["ln_inst"], "desc_cn" => $row["desc_cn"]];
                $sen_sources[$ln_class]["do_attrs"][$row["do_name"]] = ['do_name_cn' => $row["do_name_cn"], 'unit' => $row["unit"], 'precision' => $row["precision"]]; //add 精度
            }
            foreach ($sen_sources as $ln_class => $item) {
                $data_ln_tbl = "data_$ln_class";
                $ln_insts    = array_column($item["sensors"], "ln_inst");
                if (array_key_exists($senId2dataLnWhere, 'TOP')) {
                    $top       = $senId2dataLnWhere['TOP'];
                    $str_insts = implode(",", $ln_insts);
                    $sql       = "select * from $data_ln_tbl as a where (select count(*) from $data_ln_tbl as b
                where b.ln_inst = a.ln_inst and b.data_time > a.data_time) < $top and a.ln_inst in ($str_insts)
                order by a.ln_inst asc , a.data_time desc";
                    if ($ied_data_db->query($sql)) {
                        $data_q = $ied_data_db->query($sql)->fetchAll();
                    }

                } else {
                    $senId2dataLnWhere["AND"]["ln_inst"] = $ln_insts;
                    $data_q                              = $ied_data_db->select($data_ln_tbl, "*", $senId2dataLnWhere);
                }
                foreach ($item["sensors"] as $sen_id => $sen_item) {
                    $resp['sen_id']  = $sen_id;
                    $resp['desc_cn'] = $sen_item['desc_cn'];
                    $resp["items"]   = [];
                    if (isset($data_q) && $data_q) {
                        foreach ($data_q as $data_row) {
                            if ($data_row['Ln_inst'] == $sen_item['ln_inst']) {
                                $aData["data_time"]  = $data_row["Data_Time"];
                                $aData["data_attrs"] = [];
                                foreach ($item["do_attrs"] as $do_name => $do_item) {
                                    if (array_key_exists($data_row, $do_name)) {
                                        $attr["name"] = $do_item["do_name_cn"];
                                        $attr["unit"] = $do_item["unit"];
                                        if (is_numeric($do_item["precision"])) {
                                            $attr["value"] = round($data_row[$do_name], $do_item["precision"]); //add 精度
                                        } else {
                                            $attr["value"] = $data_row[$do_name];
                                        }
                                        $aData["data_attrs"][] = $attr;
                                    }
                                }
                                $resp["items"][] = $aData;
                            }
                        }
                    }
                    $resps[] = $resp;
                }
            }
            /*
        $sen_id = $q[0]['sen_id'] ;
        $resp['sen_id'] = $sen_id;
        $resp['desc_cn'] = $q[0]['desc_cn'];
        $resp["items"] = [];
        $ln_class = $q[0]['ln_class'];
        $ln_inst = $q[0]['ln_inst'];
        $data_ln_tbl = "data_$ln_class";
        $where = $senId2dataLnWhere;
        $where["AND"]["ln_inst"] = $ln_inst;
        $data_q = $ied_data_db->select($data_ln_tbl ,"*" , $where );
        if($data_q && count($data_q) > 0){
        foreach($data_q as $data_row)
        {
        $aData["data_time"] = $data_row["Data_Time"] ;
        $aData["data_attrs"] = [];
        foreach($q as $row)
        {
        $attr["name"] = $row["do_name"];
        $attr["unit"] = $row["unit"];
        $attr["value"] = $data_row[$row["do_name"]];
        $aData["data_attrs"][] = $attr;
        }
        $resp["items"][] = $aData;
        }
        }
        $resps[] = $resp;
        //$data_q = $ied_data_db->select("data_siml" ,"*" , ["ln_inst" => "1" , "LIMIT" => [10, 10]] );
         */

        }

        /*
        foreach($q as $row)
        {
        $sen_id = $row['sen_id'];
        $resp = ['$sen_id' => $sen_id];
        $resp['desc_cn'] = $row['desc_cn'];
        $resp['items'] = [];

        $ln_class = $row['ln_class'];
        $resp['data_ln_tbl'] = "data_$ln_class";
        //$cols = ['data_time'];
        $id2DoNameAndUnit[$sen_id][$row['do_name']] = $row['unit'];
        $resps[] = $resp;
        }

        foreach($resps as $resp)
        {
        $sen_id = $resp['sen_id'];
        $data_ln_tbl_where = $senId2dataLnWhere[$sen_id];
        $doName2Unit = $id2DoNameAndUnit[$sen_id];
        $data_q = $ied_data_db->select($resp['data_ln_tbl'],
        ['data_time', array_keys($doName2Unit)],
        $data_ln_tbl_where);
        unset($resp['data_ln_tbl']);

        foreach ($data_q as $row)
        {
        $item = [];
        $item['data_time'] = $row['data_time'];
        $item['data_attrs'] = [];
        foreach($doName2Unit as $col=>$unit)
        {
        $item['data_attrs'][] = ['name' => $col, 'value' => $row[$col], 'unit' => $unit];
        }

        $resp['items'][] = $item;
        }

        }
         */
        return $resps;
    }

    public static function GetSensorDataById($sen_id, $args)
    {
        $join_where["AND"] = ['sen_cfg_tbl.sen_id' => $sen_id];
        $data_ln_tbl_where = array();
        if (array_key_exists($args, 'top')) {
            $data_ln_tbl_where['TOP'] = $args['top'];
        } else {
            if (array_key_exists($args, 'time')) {
                $v                              = $args['time'];
                $data_ln_tbl_where['data_time'] = self::_str2Time($v);
            } else {
                if (array_key_exists($args, 'time_min')) {
                    $v                                         = $args['time_min'];
                    $data_ln_tbl_where["AND"]['data_time[>=]'] = self::_str2Time($v);
                }
                if (array_key_exists($args, 'time_max')) {
                    $v                                         = $args['time_max'];
                    $data_ln_tbl_where["AND"]['data_time[<=]'] = self::_str2Time($v);
                }
            }
        }

        if (array_key_exists($args, 'import_level')) {
            // import_level in (....)
            $join_where["AND"]['do_map_model.import_level'] = preg_split('/,/', $args['import_level']);
        }

        $resps = self::_getSensorDataById($data_ln_tbl_where, $join_where);

        return $resps[0];
    } #function

    public static function GetCsvById($sen_id, $args, $resp)
    {
        $cfg_db      = GetCfgDb();
        $q           = $cfg_db->select("sen_cfg_tbl", ["sen_id", "ln_class", "ln_inst", "desc_cn"], ["sen_id" => $sen_id]);
        $ln_class    = $q[0]["ln_class"];
        $ln_inst     = $q[0]["ln_inst"];
        $desc        = $q[0]["desc_cn"];
        $file_name   = $ln_class . "_" . $ln_inst . "_" . date("Ymd_His") . ".csv";
        $data_ln_tbl = "data_$ln_class";

        $do_q = $cfg_db->select("do_map_model", ["do_name", "desc_cn", "unit", "precision"], ["AND" => ["ln_class" => $ln_class, "import_level[>=]" => 1]]);

        $data_ln_tbl_where                   = array();
        $data_ln_tbl_where["AND"]["ln_inst"] = $ln_inst;
        if (array_key_exists($args, 'time_min')) {
            $v                                         = $args['time_min'];
            $data_ln_tbl_where["AND"]['data_time[>=]'] = self::_str2Time($v);
        }
        if (array_key_exists($args, 'time_max')) {
            $v                                         = $args['time_max'];
            $data_ln_tbl_where["AND"]['data_time[<=]'] = self::_str2Time($v);
        }
        $ied_data_db = GetIedDataDb();
        $data_q      = $ied_data_db->select($data_ln_tbl, "*", $data_ln_tbl_where);

        $csv_header   = [];
        $csv_key      = [];
        $csv_header[] = "Des";
        $csv_header[] = "Data_Time";
        $csv_key[]    = "Des";
        $csv_key[]    = "Data_Time";
        foreach ($do_q as $do_row) {
            if (array_key_exists($do_row["do_name"], $data_q[0])) {
                $csv_header[] = $do_row["do_name"];
                $csv_key[]    = $do_row["do_name"];
            }
        }
        $header  = implode(',', $csv_header) . PHP_EOL;
        $content = "";

        foreach ($data_q as $data_row) {
            $ary = array();
            foreach ($csv_key as $key) {
                $ary[] = $data_row[$key];
            }
            $content .= implode(',', $ary) . PHP_EOL;
        }

        $csv = $header . $content;
        $fp  = tmpfile();
        fwrite($fp, $csv);
        $stream = new \Slim\Http\Stream($fp); // create a stream instance for the response body
        return $resp->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', "attachment; filename=$file_name")
            ->withBody($stream); // all stream contents will be sent to the response
    }   
    public function GetUpdateSensorInfo($args)
    {
        $iedDataDb                = GetIedDataDb();
        $cfgDb                    = GetCfgDb();
        $lnNames                  = self::GetLnName($iedDataDb);
        $where["AND"]['ln_class'] = $lnNames;

        ##### get all the specify columns for $lnNames filter by import_level.
        if (array_key_exists($args, 'import_level')) {
            // import_level in (....)
            $where["AND"]['import_level'] = preg_split('/,/', $args['import_level']);
        } else {
            $where["AND"]['import_level'] = 2; # 2 is the default value
        }

        # sql: "select ln_class, do_name from do_map_model where inport_level=2 and ln_class IN ($lnNames)"
        $modelQ = $cfgDb->select('do_map_model', ['ln_class', 'do_name'], $where);
        if (!$modelQ) ## if not get any thing, return empty array
        {
            return [];
        }

        $ln2Cols = [];
        foreach ($modelQ as $row) {
            $ln2Cols[$row['ln_class']][] = $row['do_name'];
        }

        # sql: "select ln_inst, sen_id from sen_cfg_tbl where and ln_class IN ($lnNames)"
        ##### get ln_inst => sen_id map
        $modelQ = $cfgDb->select('sen_cfg_tbl', ['ln_class', 'ln_inst', 'sen_id'], ['ln_class' => $lnNames]);
        if (!$modelQ) ## if not get any thing, return empty array
        {
            return [];
        }

        $inst2SendId = [];
        foreach ($modelQ as $row) {
            $inst2SendId[$row['ln_class'] . $row['ln_inst']] = $row['sen_id'];
        }

        ##### get doData
        $doDatas = [];
        foreach ($lnNames as $lnName) {
            $cols = $ln2Cols[$lnName];
            # sql: "select data_time, ln_inst, ..$cols.... from update_$lnName"
            $dataQ = $iedDataDb->select("update_$lnName", array_merge(['Data_Time', 'Ln_inst'], $cols));
            if (!$dataQ) {
                continue;
            }

            #MEDOO will return FALSE or 0 or empty Array if SELECT/UPDATE failed or no data were retrieved/updated, and all of these things are equal FALSE in an if statement
            foreach ($dataQ as $row) {
                $res = ['sen_id' => $inst2SendId[$lnName . $row['Ln_inst']], 'data_time' => $row['Data_Time']];
                foreach ($cols as $col) {
                    if (array_key_exists($col, $row)) {
                        $res[$col] = $row[$col];
                    } else {
                        $res[$col] = null;
                    }

                }
                $doDatas[] = $res;
            }
        }

        return $doDatas;
    }

    public function GetLnName($db)
    {
        # sql: "select name from sqlite_master wher type=table and name like 'DATA_%'"
        $res = $db->select('sqlite_master',
            ['name'],
            ["AND" => ['type' => 'table', 'name[~]' => 'DATA_']]);
        if (!$res || count($res) == 0) {
            throw new Exception('No table like DATA_% found!');
        }
        $RET = [];
        foreach ($res as $row) {
            array_push($RET, preg_replace('/^DATA_/', '', $row['name']));
        }

        //error_log("get lnNames: [".join(',', $RET)."] success....");
        return $RET;
    }
} //class SensorData
