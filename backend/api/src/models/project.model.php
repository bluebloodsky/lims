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
    	global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
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
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        $project = $collection->findOne(["_id"=>$objId]);

        $workflows = InfoExtra::GetInstance()->GetWorkflows();

        if($project["step"]){                
            foreach ($workflows as $workflow) {
                if($workflow["id"] == $project["step"]){
                    $project["step"] = $workflow;
                }
            }
        }
        return $project;
    }

    public function StepSubmit($id){
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        $project = $collection->findOne(["_id"=>$objId]);
        $modify =  ["doneSteps" => $project["doneSteps"] ? json_decode(json_encode($project["doneSteps"]), true) :[] ,
                   "step" => $project["step"]];  

        $workflows = InfoExtra::GetInstance()->GetWorkflows();
        foreach ($workflows as $workflow) {
            if($workflow["id"] == $modify["step"]){
                array_push($modify["doneSteps"], [
                    "id" => $workflow["id"] ,
                    "alias" => $workflow["alias"] ,
                    "status" => "移交",
                    "suggestion"=>"",
                    "user" => $username,
                    "handleAt" => date_format(new DateTime(), 'Y-m-d H:i:s')
                ]);
                $modify["step"] = $workflow["nextSteps"][0];
                break;
            }
        }        
        return $collection->updateOne(['_id' => $objId], ['$set' =>$modify]);
    }

    public function StepRollback($id){
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
        $collection = $this->_dbClient->selectCollection($station . '.projects');
        $objId = new ObjectId($id);
        $project = $collection->findOne(["_id"=>$objId]);
        $modify =  ["doneSteps" => $project["doneSteps"] ? json_decode(json_encode($project["doneSteps"]), true) :[] ,
                   "step" => $project["step"]];  

        $workflows = InfoExtra::GetInstance()->GetWorkflows();
        foreach ($workflows as $workflow) {
            if($workflow["id"] == $modify["step"]){
                array_push($modify["doneSteps"], [
                    "id" => $workflow["id"] ,
                    "alias" => $workflow["alias"] ,
                    "status" => "回退",
                    "suggestion"=>"",
                    "user" => $username,
                    "handleAt" => date_format(new DateTime(), 'Y-m-d H:i:s')
                ]);
                $modify["step"] = $workflow["rollbackSteps"][0];
                break;
            }
        }        
        return $collection->updateOne(['_id' => $objId], ['$set' =>$modify]);
    }
    public function InsertOrUpdateData($data,$type)
    {
        global $userInfo;
        $station = $userInfo['station'];
        $username = $userInfo['username'];
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
            $objId = $collection->insertOne($data)->getInsertedId(); 
        } else {
            unset($data['_id']);
            $collection->updateOne(['_id' => $objId], ['$set'=>$data]);
        }

        $data['_id'] = $objId;
        return ["data" => $data,
            "message" => "更新成功"];

    }
}