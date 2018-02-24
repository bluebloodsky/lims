<?php
$app->group('/projects', function () {
    $this->post('/order', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = InfoExtra::GetInstance()->InsertOrUpdateOrderAttrs($data);
        return $resp->withJson($RET);
    });
});