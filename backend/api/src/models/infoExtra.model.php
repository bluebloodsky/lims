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

    private function getDiff($differ, $diff)
    {
        if ($diff->getType() == 'change') {
            $newvalue = $diff->getNewValue();
            $oldvalue = $diff->getOldValue();
            if (is_array($newvalue) && is_array($oldvalue)) {
                $newdiffs = $differ->doDiff($oldvalue, $newvalue);
                $contents = [];
                foreach ($newdiffs as $key => $diff) {
                    $contents[$key] = $this->getDiff($differ, $diff);
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
        $differ = new Diff\Differ\MapDiffer();
        $diffs = $differ->doDiff($oldAttrs, $data['attrs']);
        if (!$diffs) {
            return ["message" => "无修改内容"];
        }
        $contents = [];
        foreach ($diffs as $key => $diff) {
            $contents[$key] = $this->getDiff($differ, $diff);
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
        $oldParams = [];
        $oldRecords = [];
        $data['logs'] = [];
        if ($arrayOldData) {
            if ($arrayOldData['params']) {
                $oldParams = $arrayOldData['params'];
            }
            if ($arrayOldData['records']) {
                $oldRecords = $arrayOldData['records'];
            }
            if ($arrayOldData['logs']) {
                $data['logs'] = $arrayOldData['logs'];
            }
        }
        $differ = new Diff\Differ\MapDiffer();
        $diffParams = $differ->doDiff($oldParams, $data['params']);
        $diffRecords = $differ->doDiff($oldRecords, $data['records']);
        $diffs = [];
        if ($diffParams) {
            $diffs["params"] = $diffParams;
        }
        if ($diffRecords) {
            $diffs["records"] = $diffRecords;
        }
        if ($diffs) {
            return ["message" => "无修改内容"];
        }
        array_unshift($data['logs'], [
            'logTime' => date_format(new DateTime(), 'Y-m-d H:i:s'),
            'user' => $username,
            'contents' => ["params" => array_map(function ($n) {
                return $n->toArray();
            }, $diffParams), "records" => array_map(function ($n) {
                return $n->toArray();
            }, $diffRecords)],
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
}