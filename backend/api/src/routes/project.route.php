<?php
$app->group('/projects', function () {
    $this->post('/order', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = Project::GetInstance()->InsertOrUpdateData($data,"order_info");
        return $resp->withJson($RET);
    });
    $this->post('/sample', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = Project::GetInstance()->InsertOrUpdateData($data,"sample_info");
        return $resp->withJson($RET);
    });
    $this->post('/test-params', function ($req, $resp, $args) {
        $data = json_decode($req->getBody() , true);
        $RET = Project::GetInstance()->InsertOrUpdateData($data,"params");
        return $resp->withJson($RET);
    });
    $this->post('/step-submit/{id}', function ($req, $resp, $args) {
        $RET = Project::GetInstance()->StepSubmit($args['id']);
        return $resp->withJson($RET);
    });
    $this->get('',function ($req, $resp, $args) {
        $RET = Project::GetInstance()->FindTodos();
        return $resp->withJson($RET);
    });
    $this->get('/{id}',function ($req, $resp, $args) {
        $RET = Project::GetInstance()->FindById($args['id']);
        return $resp->withJson($RET);
    });
});