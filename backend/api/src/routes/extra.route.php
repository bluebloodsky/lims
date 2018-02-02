<?php
$app->group('/menus', function () {
    $this->get('', function ($req, $resp, $args) {
        $RET = InfoExtra::GetInstance()->GetMenu();
        return $resp->withJson($RET);
    });
});

$app->group('/order-attrs', function () {
    $this->get('', function ($req, $resp, $args) {
    	$params = $req->getQueryParams();
        $RET = InfoExtra::GetInstance()->GetOrderAttrs($params['lab']);
        return $resp->withJson($RET);
    });
});

$app->group('/sample-attrs', function () {
    $this->get('', function ($req, $resp, $args) {
    	$params = $req->getQueryParams();
        $RET = InfoExtra::GetInstance()->GetSampleAttrs($params['lab']);
        return $resp->withJson($RET);
    });
});