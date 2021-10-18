<?php
/*
http://localhost/weixin/insider/app/machinerepair/php/machineSave.php

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
	$sCurWXID = getObjectValue($objSubmit,'CurWXID');
	$sMachineID = getObjectValue($objSubmit,'MachineID');
	$sSubmitType = getObjectValue($objSubmit,'SubmitType');
	
	$machineRepair = new MachineRepair('');
	$result = $machineRepair->doMachineSubmitInit($strSubmit);
	if($result != ''){
		echo $result;
		return;
	}

//04 保存到数据库

	//只读数据
	if($sSubmitType == 'Read'){
		if($machineRepair->mID == '' ){
			echo '{"errcode":10,"errmsg":"Machine ID can not find."}';
			return;
		}
		
		echo $machineRepair->readMachineByMachineID($sMachineID);
		return;
	}
		
	//判断重复编号
	if($sSubmitType == 'NewAdd'){
		if($machineRepair->mID != '' ){
			//if($machineRepair->mMachineStatus != 'Delete'){
			//	echo '{"errcode":10,"errmsg":"Machine ID is same."}';
			//	return;
			//}
		}
		
		$machineRepair->mMachineName = getObjectValue($objSubmit,'MachineName');	
		$machineRepair->mMachineID = $objSubmit->MachineID;
		$machineRepair->mMachineType = getObjectValue($objSubmit,'MachineType');
		$machineRepair->mMachineLocation = getObjectValue($objSubmit,'MachineLocation');
		$machineRepair->mMachineOwner = getObjectValue($objSubmit,'MachineOwner');
		$machineRepair->mMachineUserID = getObjectValue($objSubmit,'MachineUserID');
		$machineRepair->mMachineDepartment = getObjectValue($objSubmit,'MachineDepartment');
		$machineRepair->mMachineClass = getObjectValue($objSubmit,'MachineClass');
		$machineRepair->mBuyDate = getObjectValue($objSubmit,'BuyDate','1900-01-01');
		$machineRepair->mUsedDate = getObjectValue($objSubmit,'UsedDate','1900-01-01');
		$machineRepair->mMachineSupplier = getObjectValue($objSubmit,'MachineSupplier');
		$machineRepair->mMachineFileList = getObjectValue($objSubmit,'MachineFileList')	;
		$machineRepair->mMachineStatus = getObjectValue($objSubmit,'MachineStatus','Idle');
		$machineRepair->mMachineComment =getObjectValue($objSubmit,'MachineComment');
		$machineRepair->mWarrantyDate = getObjectValue($objSubmit,'WarrantyDate','1900-01-01');
		
		if($machineRepair->mID != '' ){
			$result = $machineRepair->updateMachineByMachineID($sMachineID);
			$myLog->saveLog($sCurWXID." 03 更新记录(".$sMachineID.")：".$result);
		}else{
			$result = $machineRepair->insertMachine();
			$myLog->saveLog($sCurWXID." 03 保存到数据库：".$result);
		}
	}
	
	//如果是是删除记录
	if($sSubmitType == 'Delete'){
		if($machineRepair->mID == ''){
			echo '{"errcode":10,"errmsg":"Machine ID can not find."}';
			return;
		}
		
		$machineRepair->mMachineStatus = 'Delete';
		$result = $machineRepair->updateMachineByMachineID($sMachineID);
		$myLog->saveLog($sCurWXID." 03 删除记录(".$sMachineID.")：".$result);
	}


	if($result != 'OK'){
		echo '{"errcode":9,"errmsg":"'.$result.'"}';
		return;
	}
			
	//发送消息通知
	echo '{"errcode":0,"errmsg":"OK","SubmitType":"'.$sSubmitType.'","MachineID":"'.$machineRepair->mMachineID.'"}';
	return;
	
	function getObjectValue($obj,$skey,$svalue=''){
		
		if(property_exists($obj, $skey)){
			return $obj->$skey;
		}else{
			return $svalue;
		}
	
	
	}