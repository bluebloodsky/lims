<?php
abstract class Basehook
{
    static public function FilterSampleMainPara($mainParams,$project); 	//过滤试品主要技术参数
    static public function CalcSampleMainPara($project); //计算试品主要技术参数
    static public function FilterTestItems($testItems,$project);//过滤试品项目    
    static public function CalcTestItemsPara($project);//过滤试品项目
}