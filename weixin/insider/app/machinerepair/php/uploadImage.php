<?php  

//http://localhost/weixin/insider/app/machinerepair/php/uploadImage.php

//处理上传的事情

//00 初始化
	if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
	include_once ROOTWeixin.'/weixin/system/class/base.insider.php';
	include_once 'class.machinerepair.php';
	header("Content-Type:text/html; charset=utf-8");
	
	$wxBase = new WXInsider();
	$myLog = $wxBase->getClass('WXLog');
	$myTools = $wxBase->getClass('WXTools');
	$myApp = $wxBase->getClass('WXApp');
//01 获得参数	

	$wxid = $_POST['WXID'];	//$myApp->UrlPostValue('WXID');
	$stype = $myApp->UrlPostValue('UploadType');
	
	if($wxid == '' ){
		echo '{"errcode":1,"errmsg":"WXID is empty."}';
		return;
	}
	if( $stype ==''){
		echo '{"errcode":2,"errmsg":"WXID is empty。"}';
		return;
	}
	
	$uploaded_file=$_FILES['FileUpload']['tmp_name'];  
	$file_true_name=$_FILES['FileUpload']['name'];
	
	$sLog = $wxid.' 01 上传文件：'.$file_true_name;
	$myLog->saveLog($sLog);
	
//02 开始处理
	//新文件名
	$fileNew = $wxid.'_'.time().rand(1,1000).substr($file_true_name,strrpos($file_true_name,"."));
	$card_path= ROOTWeixin.'/weixin/insider/app/machinerepair/file';	
	
	//目标文件
	$move_to_file=$card_path."/".$fileNew;	
	
	//复制文件
	$result = copy($uploaded_file,iconv("utf-8","gb2312",$move_to_file));	
	if(!$result) {  
		echo '{"errcode":1,"errmsg":"error"}'; 
		return;
	} 
	
//03 反馈结果
    $sLog = $wxid.' 99 上传成功：';
	$myLog->saveLog($sLog);

	echo '{"errcode":0,"errmsg":"OK","FileName":"'.$fileNew.'"}'; 
	return;
	
      
    