<?php
class InfoExtra{
	private $_dbClient;
    static private $instance;
    private function __construct(){
       global $db_cfg;
       $this->_dbClient = (new MongoDB\Client($db_cfg['uri']))->selectDatabase($db_cfg['dbName']);
    }

    static public function GetInstance(){
    if(!self::$instance) {
        self::$instance = new self();
    }
    return self::$instance;
    }

	public function GetMenu(){
		return $this->_dbClient->selectCollection('menus')->find()->toArray();
	}

    public function GetOrderAttrs($station){
        return $this->_dbClient->selectCollection($station . '.order.attrs')->find()->toArray();
    }

    public function GetSampleAttrs($station){
        return $this->_dbClient->selectCollection($station . '.sample.attrs')->find()->toArray();
    }
}