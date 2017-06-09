<?php

//获取基本属性
$app->group('/map_params', function(){
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetMapParams();
       // var_dump($RET);
        //$resp->write($RET);
        $resp->withJson($RET);
        return $resp->withHeader('Content-type', 'application/json');;
    });
});

$app->group('/sen_types', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetAllSensorTypes();
        $resp->withJson($RET);
        return $resp;
    });
});

$app->group('/ln_classes', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = SensorExtra::GetAllLnClasses();
        $resp->withJson($RET);
        return $resp;
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
        $resp->withJson($RET);
        return $resp;
    });

});

$app->group('/sys_time', function () {
    $this->get('', function ($req, $resp, $args) {

        $RET = TimeExtra::GetSysTime();
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('', function ($req, $resp, $args) {
        $req_val = json_decode($req->getBody());
        $epoch = $req_val['epoch'];
        if (!$epoch)
            return;

        TimeExtra::SetSysTime($epoch);
    });

});

$app->group('/commu_proc_attrs', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = ProcAttrsExtra::GetAllProcAttrs();
        $resp->withJson($RET);
        return $resp;
    });
    $this->post('', function ($req, $resp, $args) {
        //$procAttrs = json_decode($req->getBody());
        $procAttrs = $req->getParsedBody();
        $RET = ProcAttrsExtra::SetProcAttrs($procAttrs);
        $resp->withJson($RET);
        return $resp;
    });

});

$app->group('/ntp_info', function (){
    $this->get('', function ($req, $resp, $args) {
        $RET = NtpCfgExtra::GetNtpInfo();
        $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $postParams = $req->getParsedBody();
        $RET = NtpCfgExtra::SetNtpInfo($postParams);
        $resp->withJson($RET);
        return $resp;
    });
});

$app->group('/log_cfg', function (){
    $this->get('', function ($req, $resp, $args) {
        $cfg = new LogCfgExtra();
        $RET = $cfg->GetCfgData();
        $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $postParams = $req->getParsedBody();
        $cfg = new LogCfgExtra();
        $RET = $cfg->SetCfgData($postParams);
        $resp->withJson($RET);
    });
});

$app->group('/mms_cfg', function (){
    $this->get('', function ($req, $resp, $args) {
        $cfg = new MmsCfgExtra();
        $RET = $cfg->GetCfgData();
        $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        //$params = $req->getQueryParams();
        $postParams = $req->getParsedBody();
        $cfg = new MmsCfgExtra();
        $RET = $cfg->SetCfgData($postParams);
        $resp->withJson($RET);
    });
});

$app->get('/debug_templs', function ($req, $resp, $args) {
    // Omit the params
    //$params = $req->getQueryParams();
$RET = DebugTemplsExtra::GetAllData();
$resp->withJson($RET);
});

$app->group('/log_content', function () {
    $this->get('', function ($req, $resp, $args) {
        $params = $req->getQueryParams();
        $limit = array_has($params, 'limit') ? $params['limit'] : 200;
        $start = array_has($params, 'start') ? $params['start'] : 0;

        $RET = LogContentExtra::GetLogContent($start, $limit);
        $resp->withJson($RET);
        return $resp;
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

