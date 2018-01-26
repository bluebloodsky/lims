<?php

$app->group('/sys_status', function () {
	$this->get('' , function ($req, $resp, $args){
        $sysInfo = new SysInfoModel();
        return $resp->withJson($sysInfo->All());
    });
});
