<?php

//获取基本属性
$app->group('/map_params', function(){
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetMapParams();
       // var_dump($RET);
        //$resp->write($RET);
        return $resp->withJson($RET);
    });
});

$app->group('/sen_types', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetAllSensorTypes();
        return $resp->withJson($RET);
    });
});

$app->group('/ln_classes', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetAllLnClasses();
        return $resp->withJson($RET);
    });
    $this->post('',function ($req , $resp , $args){
        $ln_class = json_decode($req->getBody() , true);
        $ret = SensorExtra::AddLnClass($ln_class);
        return $resp->withJson($ret);
    });
});

$app->group('/attr_tmpls', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetAllAttrTemplates();
        return $resp->withJson($RET);
    });

});

$app->group('/sys_time', function () {
    $this->get('', function ($req, $resp, $args) {

        $RET = TimeExtra::GetSysTime();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $req_val = json_decode($req->getBody(),true);
        $epoch = $req_val['epoch'];
        if (!$epoch)
            return;

        $RET=TimeExtra::SetSysTime($epoch);
        return $resp->withJson($RET);
    });
});

$app->group('/net_set', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = NetExtra::GetNetPara();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $req_val = json_decode($req->getBody(),true);
        $RET= NetExtra::SetNetPara($req_val);
        return $resp->withJson($RET);
    });
});

$app->group('/ied_reset', function () {
    $this->post('', function ($req, $resp, $args) {
        $RET=IedExtra::SetIedReset();
        return $resp->withJson($RET);
    });
});

$app->group('/commu_proc_attrs', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = ProcAttrsExtra::GetAllProcAttrs();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        //$procAttrs = json_decode($req->getBody());
        $procAttrs = $req->getParsedBody();
        $RET = ProcAttrsExtra::SetProcAttrs($procAttrs);
        return $resp->withJson($RET);
    });

});

$app->group('/ntp_info', function (){
    $this->get('', function ($req, $resp, $args) {
        $RET = NtpCfgExtra::GetNtpInfo();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $postParams = $req->getParsedBody();
        $RET = NtpCfgExtra::SetNtpInfo($postParams);
        return $resp->withJson($RET);
    });
});

$app->group('/log_cfg', function (){
    $this->get('', function ($req, $resp, $args) {
        $cfg = new LogCfgExtra();
        $RET = $cfg->GetCfgData();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $postParams = $req->getParsedBody();
        $cfg = new LogCfgExtra();
        $RET = $cfg->SetCfgData($postParams);
        return $resp->withJson($RET);
    });
});

$app->group('/mms_cfg', function (){
    $this->get('', function ($req, $resp, $args) {
        $cfg = new MmsCfgExtra();
        $RET = $cfg->GetCfgData();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        //$params = $req->getQueryParams();
        $postParams = $req->getParsedBody();
        $cfg = new MmsCfgExtra();
        $RET = $cfg->SetCfgData($postParams);
        return $resp->withJson($RET);
    });
});

$app->get('/debug_templs', function ($req, $resp, $args) {
    // Omit the params
    //$params = $req->getQueryParams();
    $RET = DebugTemplsExtra::GetAllData();
    return $resp->withJson($RET);
});

$app->group('/log_content', function () {
    $this->get('', function ($req, $resp, $args) {
        $params = $req->getQueryParams();

        if(!array_has($params, 'type'))
            throw new Exception("need file type !");
        $type =  $params['type'];
        if($type != "i2" && $type != "ied")
            throw new Exception("bad type value $type");

        $limit = array_has($params, 'limit') ? $params['limit'] : 200;
        $start = array_has($params, 'start') ? $params['start'] : 0;

        $RET = LogContentExtra::GetLogContent($type, $start, $limit);
        return $resp->withJson($RET);
    });
});
/*
$app->group('/ip_infos', function () {
    $this->get('' , function ($req, $resp, $args) {
        $resp = $resp->withHeader(
            'Content-Type',
            'application/json'
        );

        $RET = IpExtra::GetIpInfo();
        $resp->withJson($RET);
        return $resp;
    });
    $this->put('/{dev_name}' , function ($req, $resp, $args) {
        $devName = $args['dev_name'];
        $ipInfo = json_decode($req->getBody());
        IpExtra::SetIpInfo($devName, $ipInfo);

    });

});
*/

