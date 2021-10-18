<?php
if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
//$myLog = new WXLog();
//$myLog->errorLog('aa');

/*
日志类
时间：2021-07-26
作者：tiger
http://localhost/weixin/system/class/log.share.php

*/

class WXLog{
/**

*/

	private $path = '';

	public function __construct($sPath='log'){
		
		$this->setPath($sPath);		
	}
	
	private function setPath($sPath){
		if(!is_dir($sPath)){
			mkdir($sPath);
		}
		
		$syear = date('Y');
		if(!is_dir($sPath.'/'.$syear)){
			if(mkdir($sPath.'/'.$syear)){
				$syear = $syear.'/';
			}
		}else{
			$syear = $syear.'/';
		}
		
		$this->path = $sPath.'/'.$syear;
		return $this->path;
	}

	public function saveLog($source,$skey='log',$sPath='log'){	
	
		$sPath = $this->setPath($sPath);
		
		//文件名格式：
		 $filename = $sPath.$skey."_".date ("Ymd").".txt";
		 $strTemp = "[".date ("H:i:s")."]:".$source."\r\n";
		 
		file_put_contents($filename, $strTemp, FILE_APPEND); 
		return;
	}
	
	public function errorLog($source,$skey='error',$sPath='log'){		
		$this->saveLog($source,$skey,$sPath);
		return;
	}
	
}

