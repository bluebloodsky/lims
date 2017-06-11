<?php

$app->group('/i2_sensors', function() {
    $this->get('', function($req, $resp, $args) {
        $m = new I2SensorModel();
        $RET = $m->GetAll($req->getQueryParams());
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('/{sensor_id}', function ($req, $resp, $args) {
        $sensorId = $args['sensor_id'];
        $params = json_decode($req->getBody() , true);
        $m = new I2SensorModel();
        $RET = $m->UpdateInfoById($sensorId, $params);
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('', function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $m = new I2SensorModel();
        $RET = $m->AddInfo($params);
        $resp->withJson($RET);
        return $resp;
    });
    $this->delete('/{sensor_ids}', function ($req, $resp, $args) {
        $sensorIds = $args['sensor_ids'];
        $m = new I2SensorModel();
        $RET = $m->DeleteInfoById(preg_split("/,/", $sensorIds));
        $resp->withJson($RET);
        return $resp;
    });

    $this->post('/{sensor_id}/debug_order', function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $sensorId = $args['sensor_id'];
        $m = new I2SensorModel();
        $RET = $m->PostSensorDebugOrder($sensorId,$params);
        $resp->withJson($RET);
        return $resp;
    });

});

$app->group('/i2_params', function(){
    $this->get('', function ($req, $resp, $args) {
        $params = $req->getQueryParams();

        $m = new I2ParamsModel();
        $RET = $m->GetAllInfos();
        $resp->withJson($RET);
        return $resp;
    });

    $this->post('/{i2_param_id}', function ($req, $resp, $args) {
        $i2ParamId = $args['i2_param_id'];
        $params = json_decode($req->getBody() , true);
        $m = new I2ParamsModel();
        $RET = $m->UpdateInfoById($i2ParamId, $params);
        $resp->withJson($RET);
        return $resp;
    });

    $this->post('', function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $m = new I2ParamsModel();
        $RET = $m->AddInfo($params);
        $resp->withJson($RET);
        return $resp;
    });

    $this->delete('/{i2_param_ids}', function ($req, $resp, $args) {
        $i2ParamIds = $args['i2_param_ids'];
        $m = new I2ParamsModel();
        $RET = $m->DeleteInfoById($i2ParamIds);
        $resp->withJson($RET);
        return $resp;
    });

});

$app->group('/cac_infos', function() {
    $this->get('', function ($req, $resp, $args) {
        $m = new I2CacModel();
        $RET = $m->GetAllInfos();
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('', function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $m = new I2CacModel();
        $m->DeleteAll();
        $RET = $m->AddInfo($params);
        $resp->withJson($RET);
        return $resp;
    });
});

$app->group('/cag_infos', function() {
    $this->get('', function ($req, $resp, $args) {
        $m = new I2CagModel();
        $RET = $m->GetAllInfos();
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('', function ($req, $resp, $args) {
        $params = json_decode($req->getBody() , true);
        $m = new I2CagModel();
        $RET = $m->AddInfo($params);
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('/{cag_id}', function ($req, $resp, $args) {
        $cagId = $args['cag_id'];
        $params = json_decode($req->getBody() , true);
        $m = new I2CagModel();
        $RET = $m->UpdateInfoById($cagId, $params);
        $resp->withJson($RET);
        return $resp;
    });
    $this->delete('/{cag_ids}', function ($req, $resp, $args) {
        $cagIds = $args['cag_ids'];
        $m = new I2CagModel();
        $RET = $m->DeleteInfoById(preg_split("/,/", $cagIds));
        $resp->withJson($RET);
        return $resp;
    });
});

$app->get('/i2_groups', function ($req, $resp, $args) {
    $params = $req->getQueryParams();
    $fields = [] ;
    if(array_has($params , "fields"))
    {
        $fields = preg_split('/,/', array_get($params, 'fields', ''));
    }
    $m = new I2GroupModel($fields);
    $RET = $m->GetAllInfos();
    $resp->withJson($RET);
    return $resp;
});
