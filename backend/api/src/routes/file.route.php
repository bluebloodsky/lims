<?php
$app->group('/files', function () {
    $this->post('', function ($req, $resp, $args) {
        $files = $req->getUploadedFiles();
        if (empty($files['newfile'])) {
            throw new Exception('Expected a newfile');
        }
        $newfile = $files['newfile'];
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $name = $newfile->getClientFilename();
            $url = md5(uniqid(microtime(ture), true)).strrchr($name, '.');
            $newfile->moveTo("D:/files/" . $url);
            return $resp->withJson(['url' => $url, 'name' => $name]);
        }
        return [];
    });
    $this->get('/{url}',function($req, $resp, $args){
        $url = $args['url'];
        $fp = fopen("D:/files/" . $url, "r") or die("Unable to open file!");

        $stream = new \Slim\Http\Stream($fp); // create a stream instance for the response body
        return $resp->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', "attachment; filename=$filename")
            ->withBody($stream); // all stream contents will be sent to the response
    });
});
