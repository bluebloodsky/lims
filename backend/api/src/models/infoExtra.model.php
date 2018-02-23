<?php
use MongoDB\BSON\ObjectId;

class InfoExtra
{
    private $_dbClient;
    static private $instance;

    private function __construct()
    {
        global $db_cfg;
        $this->_dbClient = (new MongoDB\Client($db_cfg['uri']))->selectDatabase($db_cfg['dbName']);
    }

    static public function GetInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function GetMenu()
    {
        $array = $this->_dbClient->selectCollection('menus')->find();
        return $array->toArray();
    }

    public function GetOrderAttrs()
    {
        $station = 'blq';
        return $this->_dbClient->selectCollection($station . '.order.attrs')->find()->toArray();
    }

    public function GetSampleAttrs()
    {
        $station = 'blq';
        return $this->_dbClient->selectCollection($station . '.sample.attrs')->find()->toArray();
    }

    private function getDiff($diff)
    {
        $differ = new Diff\Differ\MapDiffer();
        if ($diff->getType() == 'change') {
            $newvalue = $diff->getNewValue();
            $oldvalue = $diff->getOldValue();
            if (is_array($newvalue) && is_array($oldvalue)) {
                $newdiffs = $differ->doDiff($oldvalue, $newvalue);
                $contents = [];
                foreach ($newdiffs as $key => $diff) {
                    $contents[$key] = $this->getDiff($diff);
                }
                return $contents;
            }
        }
        return $diff->toArray();
    }

    private function InsertOrUpdateAttrs($station, $username, $type, $data)
    {
        $collection = $this->_dbClient->selectCollection($station . '.' . $type . '.attrs');
        $oldData = $collection->findOne(['name' => $data['name']]);

        $arrayOldData = json_decode(json_encode($oldData), true);
        $oldAttrs = [];
        $data['logs'] = [];
        if ($arrayOldData) {
            if ($arrayOldData['attrs']) {
                $oldAttrs = $arrayOldData['attrs'];
            }
            if ($arrayOldData['logs']) {
                $data['logs'] = $arrayOldData['logs'];
            }
        }
        $differ = new Diff\Differ\ListDiffer();
        $diffs = $differ->doDiff($oldAttrs, $data['attrs']);
        if (!$diffs) {
            return ["message" => "无修改内容"];
        }
        $contents = [];
        foreach ($diffs as $key => $diff) {
            $contents[$key] = $this->getDiff($diff);
        }

        array_unshift($data['logs'], [
            'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
            'user' => $username,
            'contents' => $contents
        ]);
        if (!$oldData) {
            $data['_id'] = $collection->insertOne($data)->getInsertedId();
        } else {
            $data['_id'] = $oldData['_id'];
            $collection->replaceOne(['_id' => $oldData['_id']], $data);
        }
        return ["data" => $data,
            "message" => "更新成功"];
    }

    public function InsertOrUpdateOrderAttrs($data)
    {
        $station = 'blq';
        $username = 'blq_admin';
        return $this->InsertOrUpdateAttrs($station, $username, 'order', $data);
    }

    public function InsertOrUpdateSampleAttrs($data)
    {
        $station = 'blq';
        $username = 'blq_admin';
        return $this->InsertOrUpdateAttrs($station, $username, 'sample', $data);
    }

    public function GetTestitems()
    {
        $station = 'blq';
        return $this->_dbClient->selectCollection($station . '.test.items')->find()->toArray();
    }

    public function InsertOrUpdateTestitems($data)
    {
        $station = 'blq';
        $username = 'blq_admin';

        $collection = $this->_dbClient->selectCollection($station . '.test.items');
        $oldData = $collection->findOne(['name' => $data['name']]);

        $arrayOldData = json_decode(json_encode($oldData), true);
        $differ = new Diff\Differ\MapDiffer();
        $types = ['params' , 'records'];
        $flg_diffs = false;
        foreach ($types as $type) {
            $length = count($arrayOldData[$type]);
            for($i = 0 ; $i < $length;$i++){
                $diffs =  $differ->doDiff($arrayOldData[$type][$i] , $data[$type][$i]);
                if($diffs){  
                    if($diffs['logs']){
                        unset($diffs['logs']);
                    }                   
                    foreach ($diffs as $key => $diff) {                                 
                        $diffs[$key] = $this->getDiff($diff);
                    }
                    if($arrayOldData[$type][$i]['logs']){
                        $data[$type][$i]['logs'] = $arrayOldData[$type][$i]['logs'];
                    }else{
                        $data[$type][$i]['logs'] = [];
                    }
                    array_unshift($data[$type][$i]['logs'], [
                        'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'user' => $username,
                        'contents' =>  $diffs,
                    ]);  
                    $flg_diffs = true;                  
                }
            }                          
        }
        if(!$flg_diffs){
            return ["message" => "无修改内容"];
        } 
        if (!$oldData) {
            $data['_id'] = $collection->insertOne($data)->getInsertedId();
        } else {
            $data['_id'] = $oldData['_id'];
            $collection->replaceOne(['_id' => $oldData['_id']], $data);
        }
        return ["data" => $data,
            "message" => "更新成功"];
    }
}