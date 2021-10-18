<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/readEngineerList.php

*/

//00： 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';	
	header("Content-Type:text/html; charset=utf-8");
	
	$wxBase = new WXInsider();		
	$myMsg = $wxBase->getClass('WXMsg');
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
		
//01:获得参数
	$sWXID = '';
	if(isset($_GET["CurWXID"])){
		$sWXID = $_GET["CurWXID"];	
	}	
	if($sWXID == ''){
		$sWXID = '17665201558';
	}
	
	$filename = 'engineerlist.json';
	echo file_get_contents($filename);
	return ;
	
	