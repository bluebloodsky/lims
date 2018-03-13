<?php
$app->group('/menus', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetMenu();
        return $resp->withJson($RET);
    });
});

$app->group('/order-attrs', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetOrderAttrs();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = InfoExtra::GetInstance()->InsertOrUpdateOrderAttrs($data);
        return $resp->withJson($RET);
    });
});

$app->group('/sample-attrs', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetSampleAttrs();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = InfoExtra::GetInstance()->InsertOrUpdateSampleAttrs($data);
        return $resp->withJson($RET);
    });
});

$app->group('/test-items', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetTestitems();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = [];
        //索引数组，即多个对象,用于试验项整体增删，基本名称修改，无日志
        if(array_keys($data) == range(0, count($data)-1)){ 
             $RET = InfoExtra::GetInstance()->UpdateAllTestitems($data);
        }else{       // 关联数组，即单个对象，用于试验项单个配置     
            $RET = InfoExtra::GetInstance()->InsertOrUpdateTestitems($data);
        }
        return $resp->withJson($RET);
    });
});

$app->group('/workflows', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetWorkflows();
        return $resp->withJson($RET);
    });
    $this->post('', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = InfoExtra::GetInstance()->updateWorkflows($data);
        return $resp->withJson($RET);
    });
});