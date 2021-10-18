<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/getRepairByRepairID.php?ID=2021070006&
*/

//00 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	
	header("Content-Type:text/html; charset=utf-8");
	
//01 定义对象
//02 处理参数

	

	//获得参数	
	$sWXID = '';
	if(isset($_GET["CurWXID"])){
		$sWXID = $_GET["CurWXID"];	
	}	
	if($sWXID == ''){
		$sWXID = '17665201558';
	}
	$sID = '';
	if(isset($_GET["ID"])){
		$sID = $_GET["ID"];	
	}	
	if($sID == ''){
		echo '{"errcode":1,"errmsg":Repair ID is empty."}';
		return;
	}
	
	
	$wxBase = new WXInsider();	
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	if(!$wxBase->isAllowRun()){
		echo '{"errcode":99,"errmsg":Can not run on your server."}';
		return;
	}
	
	//日志
	//$strTemp = $sWXID.' 01 查询记录';
	//$myLog->saveLog($strTemp);

//04 保存到数据库
	$machineRepair = new MachineRepair();
	$result = $machineRepair->readRepairByRepairID($sID);
	//echo $result,'  22<br>' ;
	//$obj = json_decode($result);

	//echo $obj->Line[0]->MachineName,'<br>' ;
	echo $result;
	return;
	