<?php
/*

http://localhost/weixin/insider/app/machinerepair/php/readWorkflowbyID.php?ID=2021070010&
*/

//00： 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	header("Content-Type:text/html; charset=utf-8");
	
	$wxBase = new WXInsider();
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
	
//01:获得参数
	$sWXID = $myApp->UrlGetValue('CurWXID','17665201558');
	$sID = $myApp->UrlGetValue('ID');
	if($sID == ''){
		echo '{"errcode":1,"errmsg":Repair ID is empty."}';
		return;
	}
	
	//日志记录
	$strTemp = $sWXID." 查询流程，by: ".$sID;
	$myLog->saveLog($strTemp);

	if($sWXID == '' || $sID == ''){
		$arrTemp = array("errcode"=>1,"errmsg"=>"Repair ID is empty.");
		echo urldecode(json_encode($arrTemp));
		return ;
	}
	
//03 从SQL里面读取记录，判断是否是重复的，根据手机号，
	
	$fileName = 'workflow/'.$sID.'.txt';
	if(!is_file($fileName)){
		$arrTemp = array("errcode"=>2,"errmsg"=>"Can not find Repair ID(".$sID.") workflow file.");
		echo urldecode(json_encode($arrTemp));
		return ;
	}
	//echo $fileName;
	
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
		
		
	