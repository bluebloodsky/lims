<?php

$app->get('/cfg_filelist', function ($req, $resp, $args) {
    // Omit the params
    //$params = $req->getQueryParams();
    $RET = FileListModel::GetFileList();
    $resp->withJson($RET);
});

$app->get('/file_download', function ($req, $resp, $args) {
    $params = $req->getQueryParams();
    $type = $params['type'];
    $fileName = $params['filename'];
    if(is_null($type)||is_null($fileName))
    {
        throw new Exception("need argument type and filename");
    }

    return FileListModel::GetFile($type, $fileName, $resp);
});

$app->post('/icd_upload', function ($req, $resp, $args) {
    $files = $req->getUploadedFiles();
    if (empty($files['newfile']))
    {
        throw new Exception('Expected a newfile');
    }

    $newfile = $files['newfile'];
    // do something with $newfile
    if ($newfile->getError() === UPLOAD_ERR_OK)
    {
        $uploadFileName = $newfile->getClientFilename();

        global $gMmsIcdHome;
        $newfile->moveTo($gMmsIcdHome.$uploadFileName);
    }

    return $resp->withRedirect("/pages/html/file_manager.html");
});
