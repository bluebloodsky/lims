<?php
/**
 * Created by PhpStorm.
 * User: lmh
 * Date: 2017/3/24
 * Time: 9:20
 */
$app->group('/users', function () {
    $this->get('' , function ($req, $resp, $args) {
        $params = $req->getQueryParams();
        //$ln_class = $params["ln_class"];
        //$RET = DoAttrModel::GetDoAttrsByLnclass($ln_class);
        $RET = UserModel::GetAllUsers($params);
        return $resp->withJson($RET);
    });

    $this->post('/validate' , function ($req, $resp, $args) {
        $infoInput = json_decode($req->getBody(),true);
        $RET = UserModel::ValidateUser($infoInput);
        return $resp->withJson($RET);
    });

    $this->post('/modify' , function ($req, $resp, $args) {
        $info = json_decode($req->getBody(),true);
        $RET = UserModel::AddOrModifyUser($info);
        return $resp->withJson($RET);
    });

    $this->delete('/delete' , function ($req, $resp, $args) {
        $info = json_decode($req->getBody(),true);
        $RET = UserModel::RemoveUser($info);
        return $resp->withJson($RET);
    });
});