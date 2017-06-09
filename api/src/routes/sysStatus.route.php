<?php

$app->group('/sys_status', function () {
	$this->get('' , function ($req, $resp, $args){
        $sysInfo = new SysInfoModel();
        $resp->withJson($sysInfo->All());
        return $resp;
    });
});
