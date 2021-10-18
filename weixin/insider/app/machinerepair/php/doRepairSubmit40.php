<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/doRepairSubmit40.php

*/

//00 引入类库
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	
	header("Content-Type:text/html; charset=utf-8");
	
//01 定义对象

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
	
	
	
//02 保存数据库	
	//更新数据
	$machineRepair->mSolvedName = $objSubmit->CurName;
	$machineRepair->mSolvedUserID = $objSubmit->CurWXID;
	$machineRepair->mSolvedDate = date("Y-m-d H:i:s");
	
	//01 提交文本内容FinishText
	if($objSubmit->SubmitType == 'FinishText'){
		
		//保存流程记录
		$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'Finish','',$objSubmit->FinishText);		
		$myLog->saveLog($sCurWXID." 03 保存流程：".$result); //保存日志
			
		$result = $machineRepair->doSaveFinishLog($objSubmit,'Text');
		$myLog->saveLog($sCurWXID." 04 保存完成记录：".$result); //保存日志
		echo '{"errcode":0,"errmsg":"OK"}';
		return;	
	}
	
	//03 退回负责人
	if($objSubmit->SubmitType == 'Return'){
		
		//保存流程记录
		$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'ReturnCheck',$machineRepair->mCheckName,$objSubmit->CancelComment);
		$myLog->saveLog($sCurWXID." 03 保存流程：".$result);
		
		$machineRepair->mRepairStatus = '20';
		$machineRepair->mSolvedComment = $objSubmit->CancelComment;
		$machineRepair->mVerifyName = $machineRepair->mSubmitName;
		$machineRepair->mVerifyUserID = $machineRepair->mSubmitUserID;
		
		$result = $machineRepair->updateRepairByRepairID($sRepairID);
		$myLog->saveLog($sCurWXID." 04 更新到数据库：".$result);
	
		if($result == 'OK'){
			echo '{"errcode":0,"errmsg":"OK"}';
		}else{
			echo '{"errcode":3,"errmsg":"'.$result.'"}';
		}
		return;	
	}
	
	
	//04 提交完成	
	if($objSubmit->SubmitType == 'Completed'){
	
		//保存流程记录
		$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'Solved',$machineRepair->mSubmitName,'');
		$myLog->saveLog($sCurWXID." 03 保存流程：".$result);
		
		$machineRepair->mRepairStatus = '50';
		$machineRepair->mSolvedComment = '';
		$machineRepair->mVerifyName = $machineRepair->mSubmitName;
		$machineRepair->mVerifyUserID = $machineRepair->mSubmitUserID;
		
		$result = $machineRepair->updateRepairByRepairID($sRepairID);
		$myLog->saveLog($sCurWXID." 04 更新到数据库：".$result);
	
		if($result == 'OK'){
			echo '{"errcode":0,"errmsg":"OK"}';
		}else{
			echo '{"errcode":3,"errmsg":"'.$result.'"}';
		}
		return;	
	}