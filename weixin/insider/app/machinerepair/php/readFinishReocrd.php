<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/readFinishReocrd.php?ID=2021070010&

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
	$sID = '';
	if(isset($_GET["ID"])){
		$sID = $_GET["ID"];	
	}	
	if($sID == ''){
		echo '{"errcode":1,"errmsg":Repair ID is empty."}';
		return;
	}
	
	//日志记录
	$strTemp = $sWXID." 读取完成记录: ".$sID;
	$myLog->saveLog($strTemp);

	if($sWXID == '' || $sID == ''){
		$arrTemp = array("errcode"=>1,"errmsg"=>" ID is empty.");
		echo urldecode(json_encode($arrTemp));
		return ;
	}
	
//03 从SQL里面读取记录，判断是否是重复的，根据手机号，
	
	$fileName = 'finish/finish_'.$sID.'.txt';
	if(!is_file($fileName)){
		$arrTemp = array("errcode"=>2,"errmsg"=>"Can not find  ID(".$sID.") finish record file.");
		echo urldecode(json_encode($arrTemp));
		return ;
	}
		
	//开始批量读记录
	$fp = fopen($fileName,"r");	
	$sLineTotal = '';
	$sLine = '';
	$arrLine = array();
	$num = 0;
	
	while(! feof($fp)){
		$sLine = trim(fgets($fp));
		if($sLine == '') break;
		$arrLine[] = json_decode($sLine);
		$num++;
	}
	
	$arrTemp = array("errcode"=>0,"errmsg"=>"OK","Num"=>$num,"List"=>$arrLine);
	echo urldecode(json_encode($arrTemp));
	return ;
		
		
	