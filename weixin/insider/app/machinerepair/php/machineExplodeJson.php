<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/machineexplodeJson.php?ID=1234&


http://localhost/weixin/insider/app/machinerepair/manager/machineList.html
*/


//定义SQL对象
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	header("Content-Type:text/html; charset=utf-8");

	$wxBase = new WXInsider();	
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
	
	$machineRepair = new MachineRepair();
	
	//获得参数	
	$sWXID = $myApp->UrlGetValue('CurWXID','17665201558');
	$sFrom = $myApp->UrlGetValue('From','1');
	$sTo = $myApp->UrlGetValue('To','100');
	$sKey = $myApp->UrlGetValue('Key','');
	$sSortName = $myApp->UrlGetValue('SortName','');
	$sSortType = $myApp->UrlGetValue('SortType','');
	
	
	//$sLog = '01 导出Json';
	//$myLog->saveLog($sLog);
	
	$result = $machineRepair->machineExplodeJson($sFrom,$sTo,$sKey,$sSortName,$sSortType);
	$obj = json_decode($result);
	if($obj->errcode != 0){
		echo $result;
		return;
	}
	
	//$sLog = '02 导出数量'.$obj->Num;
	//$myLog->saveLog($sLog);

	echo $result;
	return;

//获取参数
	function getValue($sName,$sValue){
		
		$sFrom = '';
		if(isset($_GET[$sName])){
			$sFrom = $_GET[$sName];	
		}	
		if($sFrom == ''){
			$sFrom = $sValue;
		}
		return $sFrom;
	}