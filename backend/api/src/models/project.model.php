<?php
use MongoDB\BSON\ObjectId;

/*一份委托协议对应一个project*/

class Project
{
    private $_dbClient;    
    static private $instance;

    public function __construct()
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

    public function findAll()
    {
    	$station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $options["projection"] = [
            "order_info.order_client.name" => 1,
            "order_info.order_content.order_code"  => 1 ,
            "sample_info.sample_base_info.product_name" => 1 ,
            "status" => 1
        ];
        $options["limit"] = 10;
        return $collection->find([],$options)->toArray();
    }

    public function findById($id)
    {
        $station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        return $collection->findOne(["_id"=>$objId]);
    }

    public function InsertOrUpdateData($data,$type)
    {
        $station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');

        $arrayOldData = [];
        $objId = null ;
        if ($data['_id']) { //项目已存在
            $objId = new ObjectId($data['_id']['$oid']);
        }

        $data['modifyAt'] = date_format(new DateTime(), 'Y-m-d H:i:s');
        if (!$objId) {
            $data['createAt'] = $data['modifyAt'];
            $data['_id'] = $collection->insertOne($data)->getInsertedId();
        } else {
            $data['_id'] = $objId;
            $collection->replaceOne(['_id' => $objId], $data);
        }
        return ["data" => $data,
            "message" => "更新成功"];

    }
}