<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/doRepairSubmit10.php

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
	if($strSubmit == ''){
		echo '{"errcode":1,"errmsg":"Can not find data"}';
		return;
	}
	
	$objSubmit = json_decode($strSubmit);		
	$sCurWXID = '';
	if(property_exists($objSubmit, 'CurWXID')){
		$sCurWXID = $objSubmit->CurWXID;
	}
	
	$sRepairID = '';
	if(property_exists($objSubmit, 'RepairID')){
		$sRepairID = $objSubmit->RepairID;
	}
	
	
	$machineRepair = new MachineRepair($sRepairID);
	$result = $machineRepair->doSubmitInit($strSubmit);
	if($result != ''){
		echo $result;
		return;
	}
	

//04 保存到数据库

	//检查并保存图片
	$strFileList = '';
	$strImageList = '';	
	if($objSubmit->MainImageList != ""){
		$arrFile = explode(';',$objSubmit->MainImageList);		
		$arrCount = count($arrFile);
		
		$filePath = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id=';				
		$fileNewKey = $sCurWXID.'_'.date("YmdHis");		
		for($i=0 ; $i<$arrCount ; $i++){
			
			if($arrFile[$i] !=''){
				if($i == 0){
					$fileNew = $fileNewKey.'.jpg';
				}else{
					$fileNew = $fileNewKey.'_'.((string)$i).'.jpg';
				}
				
				//保存附件
				$arrTemp = explode('|',$arrFile[$i]);			
				$myTools->httpSaveFile($filePath.$arrTemp[1],'../file/'.$fileNew);
				
				//保存日志
				$strTemp = $sCurWXID." 03 图片文件 ".$fileNew;
				$myLog->saveLog($strTemp);
				
				$strImageList .= ';'.$fileNew;
			}
		}
		$strImageList = substr($strImageList,1);
	}	
	
	//检查并保存录音
	$strVoiceList = '';	
	if($objSubmit->MainVoiceList != ""){
		$arrFile = explode(';',$objSubmit->MainVoiceList);		
		$arrCount = count($arrFile);	
		
		$filePath = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id=';		
		$fileNewKey = $sCurWXID.'_'.date("YmdHis");		
		for($i=0 ; $i<$arrCount ; $i++){
			
			if($arrFile[$i] !=''){
				if($i == 0){
					$fileNew = $fileNewKey.'.rm';
				}else{
					$fileNew = $fileNewKey.'_'.((string)$i).'.rm';
				}			
				
				//保存附件	
				$myTools->httpSaveFile($filePath.$arrFile[$i],'../file/'.$fileNew);
				
				//保存日志
				$strTemp = $sCurWXID." 03 声音文件 ".$fileNew;
				$myLog->saveLog($strTemp);
				
				$strVoiceList .= ';'.$fileNew;
			}
		}
		
		if($strImageList == ''){
			$strFileList = substr($strVoiceList,1);
		}else{
			$strFileList = $strImageList.$strVoiceList;
		}
	}	
	
	//保存数据库	
	$machineRepair->mMachineName = $objSubmit->MachineName;	
	$machineRepair->mMachineID = $objSubmit->MachineID;
	$machineRepair->mRepairFileList = $strFileList;
	$machineRepair->mSubmitDate = date("Y-m-d H:i:s");
	$machineRepair->mSubmitName = $objSubmit->UserName;		//.'('.$objSubmit->UserMobile.')';
	$machineRepair->mSubmitUserID = $objSubmit->UserMobile;	//$objSubmit->CurWXID;
	$machineRepair->mCreatedName = $objSubmit->CurName;
	$machineRepair->mCreatedUserID = $objSubmit->CurWXID;
	$machineRepair->mSubmitComment = $objSubmit->RepairComment;	
	$machineRepair->mRepairStatus = '20';
	$machineRepair->mCheckName = $machineRepair->getConfigValue('CheckerName');
	$machineRepair->mCheckUserID = $machineRepair->getConfigValue('CheckerUserID');
	$result = $machineRepair->insertRepair();
	$myLog->saveLog($sCurWXID." 03 保存到数据库：".$result);
	
	if($result != 'OK'){
		echo '{"errcode":9,"errmsg":"'.$result.'"}';
		return;
	}
				
	//保存流程记录
	$machineRepair->mRepairStatus = '10';
	$result = $machineRepair->doSaveWorkflow($objSubmit->CurName,'Submit',$machineRepair->mCheckName);
	$myLog->saveLog($sCurWXID." 04 保存流程：".$result);
	
	//发送消息通知
	echo '{"errcode":0,"errmsg":"OK","RepairID":"'.$machineRepair->mRepairID.'"}';
	return;
	