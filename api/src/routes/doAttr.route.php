<?php

$app->group('/do_attrs', function () {
    $this->get('' , function ($req, $resp, $args) {
        $params = $req->getQueryParams();
        //$ln_class = $params["ln_class"];
        //$RET = DoAttrModel::GetDoAttrsByLnclass($ln_class);
        $RET = DoAttrModel::GetDoAttrs($params);
        return $resp->withJson($RET);
    });

    $this->post('' , function ($req, $resp, $args) {
        $doAttrs = json_decode($req->getBody() , true);
        $RET = DoAttrModel::AddDoAttr($doAttrs);
        return $resp->withJson($RET);
    });

    $this->post('/{do_id}' , function ($req, $resp, $args) {
        $do_id = $args['do_id'];
        $doAttrs = json_decode($req->getBody(),true);
        $RET = DoAttrModel::UpdateDoAttr($do_id, $doAttrs);
        return $resp->withJson($RET);
    });

    $this->delete('/{do_ids}' , function ($req, $resp, $args) {
        $do_ids = $args['do_ids'];
        $RET = DoAttrModel::DeleteDoAttr($do_ids);
        return $resp->withJson($RET);
    });

});
