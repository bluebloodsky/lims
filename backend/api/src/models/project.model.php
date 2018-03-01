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

    public function FindTodos($limit=10,$skip=0) //查询待办业务
    {
    	$station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $options["projection"] = [
            "order_info.order_client.name" => 1,
            "order_info.order_content.order_code"  => 1 ,
            "sample_info.sample_base_info.product_name" => 1 ,
            "step" => 1
        ];
        $workflows = InfoExtra::GetInstance()->GetWorkflows();

        $options["limit"] = $limit;
        $projects = $collection->find([],$options)->toArray();
        $ret = [];
        foreach ($projects as $project) {
            if($project["step"]){                
                foreach ($workflows as $workflow) {
                    if($workflow["id"] == $project["step"]){
                        $project["step"] = ["id"=>$workflow["id"], "alias"=>$workflow["alias"]];
                    }
                }
            }
            array_push($ret, $project);
        }
        return $ret;
    }

    public function FindById($id)
    {
        $station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        return $collection->findOne(["_id"=>$objId]);
    }

    public function StepSubmit($id){
        $station = 'blq';
        $username = 'blq_admin';
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        $project = $collection->findOne(["_id"=>$objId]);

        if(!$project["doneSteps"]){
            $project["doneSteps"] = [];
        }        
        $workflows = InfoExtra::GetInstance()->GetWorkflows();
        foreach ($workflows as $workflow) {
            if($workflow["id"] == $project["step"]){
                array_push($project["doneSteps"], [
                    "id" => $workflow["id"] ,
                    "alias" => $workflow["alias"] ,
                    "status" => "移交",
                    "suggestion"=>"",
                    "user" => $username,
                    "handleAt" => date_format(new DateTime(), 'Y-m-d H:i:s')
                ]);
                $project["step"] = $workflow["nextSteps"][0];
                break;
            }
        }        
        $collection->replaceOne(['_id' => $project["_id"]], $project);
        return [];
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
        $data['step'] = 'ORDER_RECEIVE';
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