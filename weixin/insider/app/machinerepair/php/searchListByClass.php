<?php
/*

http://localhost/weixin/insider/app/machinerepair/php/searchListByClass.php?CurWXID=17665201558&Class=01&
http://localhost/weixin/insider/app/machinerepair/searchRepair.html



*/

//00： 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	header("Content-Type:text/html; charset=utf-8");
	
	$wxBase = new WXInsider();
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	
//01:获得参数
	$sWXID = '';
	if(isset($_GET["CurWXID"])){
		$sWXID = $_GET["CurWXID"];	
	}	
	if($sWXID == ''){
		$sWXID = '17665201558';
	}

	$sClass = '01';
	if(isset($_GET["Class"])){
		$sClass = $_GET["Class"];	
	}	
	if($sClass == ''){
		$sClass = '01';
	}
	
	
	$sFrom = '1';
	if(isset($_GET["From"])){
		$sFrom = $_GET["From"];	
	}	
	if($sFrom == ''){
		$sFrom = '1';
	}	
	$sTo = (string)(((int)$sFrom) + 5);
	
	//日志记录
	$strTemp = $sWXID.' 01 搜索报修记录: '.$sClass;
	$myLog->saveLog($strTemp);

	if($sWXID == ''){
		echo '{"errcode":1,"errmsg":"WXID ID is empty."}';
		return ;
	}
	
//03 从SQL里面读取记录，判断是否是重复的，根据手机号，
	$machineRepair = new MachineRepair();
	$result = $machineRepair->readListByClass($sWXID,$sClass,$sFrom,$sTo);
	
//判断执行结果，
	$obj = json_decode($result);
	
	//保存日志
	$strTemp = $sWXID." 02 搜索结果：".$obj->Num;
	$myLog->saveLog($strTemp);
	
	echo $result; 
	return;

