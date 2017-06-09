<?php
$app->group('/sensors', function () {
	$this->get('' , function ($req, $resp, $args) {
		$params = $req->getQueryParams();
		$groupby = $params["groupby"];
		$fields = $params["fields"];
		if($fields){
		    $fields = preg_split("/,/" , $fields);
		}else{
			$fields = ['sen_id', 'sen_type', 'ln_class', 'ln_inst' , 'desc_cn'];
		}
		$RET = SensorModel::GetSensorsDataGroupBy($groupby, $fields,$params);
		$resp->withJson($RET);
		return $resp;

	});

	$this->get('/sen_basic_status' , function ($req, $resp, $args) {
		$params = $req->getQueryParams();
		if(!array_has($params, 'top'))
            $params['top'] = 1;
		$RET = SensorBasicStatus::GetSensorStatusById(null,$params);
		$resp->withJson($RET);
		return $resp;
	});

	$this->get('/all' , function ($req, $resp, $args) {
		$RET = SensorModel::GetSensorsDataAll();
		$resp->withJson($RET);
		return $resp;
	});

	//***desc: 获取某一设备某一报警原因历史数据
	$this->get('/{id}/sen_basic_status' , function ($req, $resp, $args) {
		$params = $req->getQueryParams();
		$sen_id = $args['id'];

		$RET = SensorBasicStatus::GetSensorStatusById($sen_id, $params);
		$resp->withJson($RET);
		return $resp;
	});

	$this->get('/{id}/sen_datas' , function ($req, $resp, $args) {
		$params = $req->getQueryParams();
		$sen_id = $args['id'];
        $RET = SensorData::GetSensorDataById($sen_id, $params);
		$resp->withJson($RET);
		return $resp;
	});

    $this->get('/sen_datas' , function ($req, $resp, $args) {
        $params = $req->getQueryParams();
        $RET = SensorData::GetSensorData($params);
        $resp->withJson($RET);
        return $resp;
    });

    $this->get('/{id}/debug_orders' , function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $sensorId = $args['sensor_id'];
        $RET = SensorModel::PostSensorDebugOrder($sensorId,$params);
        $resp->withJson($RET);
        return $resp;
        //TODO
        /*
        ***type:post
        ***url: api/sensors/$sen_id$/debug_orders
        ***request:
        {
            sen_id:	<int>,
            cmd:	<int>,
            param:	<str>,
        }
        */
        // 不知道这个要怎么实现
    });

	$this->post('' , function ($req, $resp ) {
        $sensor = json_decode($req->getBody() , true);
        $ret = SensorModel::OnAddSensor($sensor);
		return $resp->withJson($ret);
	});
	$this->post('/{id}' , function ($req, $resp, $args) {
		$sensor = json_decode($req->getBody() , true);
		$sen_id = $args['id'];
		$ret = SensorModel::OnUpdateSensor($sen_id, $sensor);
		return $resp->withJson($ret);
    });

	$this->delete('/{ids}' , function ($req, $resp, $args) {
		$sen_ids = $args['ids'];
		$ret = SensorModel::OnDeleteSensor($sen_ids);
		return $resp->withJson($ret);
    });

});
