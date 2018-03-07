<?php
class Report
{
	static public function exportTest($project){
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(API . 'resources/model/project.docx');
		$vars = $templateProcessor->getVariables();
		$templateProcessor->setValue('delegate_factory',$project['order_info']['order_client']['name']); 

	    header("Pragma: public");
	    header("Content-Type:application/force-download");
	    header("Content-Type:application/vnd.ms-word");
	    header("Content-Type:application/octet-stream");
	    header("Content-Type:application/download");;
	    header('Content-Disposition:attachment;filename="test.docx"');
	    header("Content-Transfer-Encoding:binary");
        $templateProcessor->saveAs('php://output');
	}
}