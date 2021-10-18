<?php

//https://localhost/weixin/insider/app/machinerepair/register.php

//01 开始获得参数，
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'php/class.machinerepair.php';
	header("Content-Type:text/html; charset=utf-8");
	
	//定义对象
	$wxBase = new WXInsider();		
	$myMsg = $wxBase->getClass('WXMsg');
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
	
	//01:获得参数
	$sRepairID = $myApp->UrlGetValue('ID');	
	$machineRepair = new MachineRepair($sRepairID);
		
	//日志记录
	//$strTemp = $sCIPID.' 正在打开';
	//$myLog->saveLog($strTemp);

	 //       https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxba8f527910e41f7a&redirect_uri=/insider/app/cip_sz3.0/register10.html?&response_type=code&scope=SCOPE&state=zeiss&connect_redirect=1#wechat_redirect
	//$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxba8f527910e41f7a&redirect_uri=/insider/app/cip_sz3.0/register10.html?&response_type=code&scope=SCOPE&state=zeiss#wechat_redirect';
	
	$url = 'register10.html?ID='.$sRepairID.'&';
	if($sRepairID == ''){
		echo '<script>location.href="'.$url.'";</script>';
		return ;
	}
	
//03 从SQL里面读取记录，判断是否是重复的，根据手机号，
	$result = $machineRepair->readRepairByRepairID($sRepairID);			
	$objTemp = json_decode($result);	
	
	if($objTemp->errcode != 'OK' || $machineRepair->mID == ''){	
		echo '<script>location.href="'.$url.'";</script>';
		return;
	}
	
	$status =$machineRepair->mRepairStatus;
	//$url1 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxba8f527910e41f7a&redirect_uri=wechat.zeiss.com.cn/insider/app/cip_sz3.0/';
	//$url2 = '?CIPID='.$sCIPID.'&response_type=code&scope=SCOPE&state='.$sCIPID.'#wechat_redirect';
	
	$url1 = '';
	$url2 = '?ID='.$sRepairID.'&';
	
	if($status == ''){	
		$status == '10';
	}
	
	if($status == '90'){
		$status = '100';
	}
	
	$url = $url1.'register'.$status.'.html'.$url2;
	echo '<script>location.href="'.$url.'";</script>';
	return;
		
	

	
	