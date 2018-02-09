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

    private function InsertOrUpdateAttrs($station , $username , $type, $data)
    {
        $collection = $this->_dbClient->selectCollection($station . '.' . $type . '.attrs');
        $oldData = $collection->findOne(['name' => $data['name']]);
        if ($data['_id']) {
            $data['_id'] = new ObjectId($data['_id']['$oid']);
        }

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
        $diff = $differ->doDiff($oldAttrs, $data['attrs']);
        if (!$diff) {
            return ["message" => "无修改内容"];
        }
        array_unshift($data['logs'], [
            'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
            'user' => $username,
            'contents' => array_map(function ($n) {
                return $n->toArray();
            }, $diff),
        ]);
        if (!$oldData) {
            $data['_id'] = $collection->insertOne($data)->getInsertedId();
        } else {
            $collection->replaceOne(['_id' => $oldData['_id']], $data);
        }
        return ["data" => $data,
            "message" => "更新成功"];
    }

    public function InsertOrUpdateOrderAttrs($data)
    {
        $station = 'blq';
        $username = 'blq_admin';
        return $this->InsertOrUpdateAttrs($station , $username , 'order', $data);
    }

    public function InsertOrUpdateSampleAttrs($data)
    {
        $station = 'blq';
        $username = 'blq_admin';
        return $this->InsertOrUpdateAttrs($station, $username,'sample', $data);
    }
}