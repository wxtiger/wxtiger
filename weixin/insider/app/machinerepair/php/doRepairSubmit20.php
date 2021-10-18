<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/doRepairSubmit20.php

*/

//00 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	
	header("Content-Type:text/html; charset=utf-8");
	
//01 定义对象
//02 处理参数

	//获得参数	
	$wxBase = new WXInsider();		
	$myMsg = $wxBase->getClass('WXMsg');
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
	
	$strSubmit = urldecode(file_get_contents("php://input"));
	$objSubmit = json_decode($strSubmit);	
	$sCurWXID = $objSubmit->CurWXID;
	$sRepairID = $objSubmit->RepairID;
	
	$machineRepair = new MachineRepair($sRepairID);
	$result = $machineRepair->doSubmitInit($strSubmit);
	if($result != ''){
		echo $result;
		return;
	}
	
	//保存数据库	
	$machineRepair->mCheckName = $objSubmit->CurName;
	$machineRepair->mCheckUserID = $objSubmit->CurWXID;
	$machineRepair->mCheckDate = date("Y-m-d H:i:s");
	
	//Cancel
	if($objSubmit->SubmitType == 'Cancel'){
		
		//保存流程记录
		$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'Cancel','',$objSubmit->CancelComment);		
		$myLog->saveLog($sCurWXID." 03 保存流程：".$result); //保存日志
		
		$machineRepair->mRepairStatus = '60';
		$machineRepair->mCheckComment = $objSubmit->CancelComment;		
		$result = $machineRepair->updateRepairByRepairID($sRepairID);
		$myLog->saveLog($sCurWXID." 04 更新到数据库：".$result); //保存日志
	
		if($result == 'OK'){
			echo '{"errcode":0,"errmsg":"OK"}';
		}else{
			echo '{"errcode":3,"errmsg":"'.$result.'"}';
		}
		return;	
	}
	
	//Approved
	
		//保存流程记录
		$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'Approved',$objSubmit->NextName,'');
		$myLog->saveLog($sCurWXID." 03 保存流程：".$result);
		
		$machineRepair->mRepairStatus = '40';
		$machineRepair->mCheckComment = '';
		$machineRepair->mSolvedName = $objSubmit->NextName;
		$machineRepair->mSolvedUserID = $objSubmit->NextWXID;
		
		$result = $machineRepair->updateRepairByRepairID($sRepairID);
		$myLog->saveLog($sCurWXID." 04 更新到数据库：".$result);
	
		if($result == 'OK'){
			echo '{"errcode":0,"errmsg":"OK"}';
		}else{
			echo '{"errcode":3,"errmsg":"'.$result.'"}';
		}
		return;	
	