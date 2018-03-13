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
        global $userInfo;
        $station = $userInfo['station'];
        return $this->_dbClient->selectCollection($station . '.order.attrs')->find()->toArray();
    }

    public function GetSampleAttrs()
    {
        global $userInfo;
        $station = $userInfo['station'];
        return $this->_dbClient->selectCollection($station . '.sample.attrs')->find()->toArray();
    }
    public function GetWorkflows(){
        global $userInfo;
        $station = $userInfo['station'];
        return $this->_dbClient->selectCollection($station . '.workflows')->find()->toArray();
    }
    public function updateWorkflows($data){
       global $userInfo;
        $station = $userInfo['station'];
        $collection = $this->_dbClient->selectCollection($station . '.workflows');
        $collection->drop();
        $steps = [];
        foreach ($data as $step) {
            /*if($step["_id"]){
                unset($step["_id"]);
            }*/
            $step["_id"] = new ObjectId($step["_id"]["$oid"]);            
            array_push($steps, $step);
        }
        return  $collection->insertMany($steps);
    }

    private function InsertOrUpdateAttrs($station, $username, $type, $data)
    {
        $collection = $this->_dbClient->selectCollection($station . '.' . $type . '.attrs');
        $oldData = $collection->findOne(['name' => $data['name']]);

        $arrayOldData = json_decode(json_encode($oldData), true);
        $logs = $arrayOldData['logs'] ? $arrayOldData['logs'] : [];
        $oldAttrs = $arrayOldData['attrs'] ? $arrayOldData['attrs'] : [];

        $diffs = GetMapDiff($oldAttrs, $data['attrs']);
        if (!$diffs) {
            return ["message" => "无修改内容"];
        }
        array_unshift($logs, [
            'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
            'user' => $username,
            'contents' => $diffs
        ]);
        $data['logs'] = $logs;        
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
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
        return $this->InsertOrUpdateAttrs($station, $username, 'order', $data);
    }

    public function InsertOrUpdateSampleAttrs($data)
    {
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
        return $this->InsertOrUpdateAttrs($station, $username, 'sample', $data);
    }

    public function GetTestitems()
    {
        global $userInfo;
        $station = $userInfo['station'];
        return $this->_dbClient->selectCollection($station . '.test.items')->find()->toArray();
    }

    public function InsertOrUpdateTestitems($data)
    {
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];

        $collection = $this->_dbClient->selectCollection($station . '.test.items');
        $oldData = $collection->findOne(['name' => $data['name']]);

        $arrayOldData = json_decode(json_encode($oldData), true);

        $logs = $arrayOldData['logs'] ? $arrayOldData['logs']:[];

        unset($arrayOldData['logs']);
        unset($data['logs']);
        unset($arrayOldData['_id']);
        unset($data['_id']);

        $diffs = GetMapDiff($arrayOldData , $data);
        if(!$diffs){
            return ["message" => "无修改内容"];
        } 
        array_unshift($logs, [
                        'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'user' => $username,
                        'contents' =>  $diffs,
                    ]);  
        $data["logs"] = $logs;
        if (!$oldData) {
            $data['_id'] = $collection->insertOne($data)->getInsertedId();
        } else {
            $data['_id'] = $oldData['_id'];
            $collection->replaceOne(['_id' => $oldData['_id']], $data);
        }
        return ["data" => $data,
            "message" => "更新成功"];
    }

    public function UpdateAllTestitems($data){
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];        
        $collection = $this->_dbClient->selectCollection($station . '.test.items');
        $pros = [];
        foreach ($data as $project) {
            # code...
            if($project["_id"]){
                $project["_id"] = new ObjectId($project['_id']['$oid']);
            }
            array_push($pros , $project);
        }
        $collection->drop();
        return  $collection->insertMany($pros);
    }

}