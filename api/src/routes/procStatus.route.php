<?php

$app->group('/proc_status', function () {
    $this->get('' , function ($req, $resp, $args){
        $proInfo = gGetAllProcInfoArray();
        $resp->withJson($proInfo);
        return $resp;
    });
	$this->get('/{proc_name}' , function ($req, $resp, $args){
        $procName = $args['proc_name'];
        $proInfo = gGetProcInfoArray($procName);
        $resp->withJson($proInfo);
        return $resp;
    });
    $this->post('/restart/{proc_name}', function ($req, $resp, $args){
		$params = $req->getQueryParams();
        $runStatus = ($params['run_status'] == "true");

        $procName = $args['proc_name'];
        //Omit the runstatus
        $proInfo = gRestartProc($procName);
        $resp->withJson($proInfo);
        return $resp;
    });
});
