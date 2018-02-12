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
        $RET = InfoExtra::GetInstance()->InsertOrUpdateTestitems($data);
        return $resp->withJson($RET);
    });
});