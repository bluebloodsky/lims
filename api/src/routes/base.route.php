<?php
$app->get('/', function ($req, $response, $args) {
    /*
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(API . 'resources/model/test.docx');
//    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(API . 'resources/model/project.docx');
    $templateProcessor->deleteBlock('BLOCK1');
    $templateProcessor->cloneBlock('BLOCK2',1);
    $templateProcessor->cloneBlock('BLOCK3',1);
    header("Pragma: public");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-word");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename="test.docx"');
    header("Content-Transfer-Encoding:binary");
    $templateProcessor->saveAs('php://output');
    */
    $response->write("Welcome to use!");
    return $response;
});
