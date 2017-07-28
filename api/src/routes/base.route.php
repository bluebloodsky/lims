<?php
$app->get('/', function ($req, $response, $args) {

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(API . 'resources/model/project.docx');

    $templateProcessor->setValue('delegate_code', '0001');
    $templateProcessor->setValue('delegate_factory', '国家电网');
    $templateProcessor->setValue('aa', '国家电网');

    $templateProcessor->deleteBlock('BLOCK1');
    $templateProcessor->cloneBlock('BLOCK2', 1);
    $templateProcessor->deleteBlock('BLOCK3');

    header("Pragma: public");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-word");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename="test.docx"');
    header("Content-Transfer-Encoding:binary");
    $templateProcessor->saveAs('php://output');
});
