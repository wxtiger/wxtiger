<?php
/*
常用工具类
时间：2021-07-26
作者：tiger
http://localhost/weixin/system/class/tools.share.php

*/


if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);

//$myTools = new WXTools;
//echo $myTools->systemGet('HttpServerName');
//return;

class WXTools {
	
	

	public function httpPost($url, $data_string='',$stime=5000) {  
	/*******************************************************************************************
	* 函数名称：向网络提交Post的Json数据，反馈Json数据
	*
	*
	* @param string url，json
	* @return json
	*/


		$ch = curl_init();  

		//http
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);  
		//curl_setopt($ch, CURLOPT_PROXY, "10.22.83.102:8080");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
			'Content-Type: application/json; charset=utf-8',  
			'Content-Length: ' . strlen($data_string))  
		);  
		ob_start();  
		$res = curl_exec($ch);  
		$return_content = ob_get_contents();  
		ob_end_clean();  
		return $res;
  
	//调用演示
	//$url  = "http://xx.xx.cn";  
	//$data = json_encode(array('a'=>1, 'b'=>2));   
  
	//httpPost($url, $data);  

	}  

	public function httpGet($url,$stime=5000) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $stime);	
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	 }

	public function httpsGet($url,$stime=5000){ 
	/*******************************************************************************************
	* 函数名称：向网络提交Get的数据，反馈Json数据
	*
	* Date： 2016-08-04
	* @param string url，json
	* @return json

	调用方法：
	$data = array('access_token'=>$this->token); 
	$header = array(); 
	$result = $this->https_get($strUrl, $data, $header, 30); 

	*/
	
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_TIMEOUT, $stime);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_PROXY, "10.22.83.102:8080");
			//curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			return curl_exec($ch); 
	} 
	
	public function httpsPost($url, $data_string,$stime=5000) {  
	/*
	HTTPS 方式提交Post Json数据，并返回结果
	时间：2016-08-31
	*/

		$ch = curl_init();  

		//https
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);  
		//curl_setopt($ch, CURLOPT_PROXY, "10.22.83.102:8080");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_TIMEOUT, $stime);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($data_string))); 
		
		$result=curl_exec($ch);  
		curl_close($ch);		

		return $result;
		  
	//调用演示
	//$url  = "http://xx.xx.cn";  
	//$data = json_encode(array('a'=>1, 'b'=>2));   
  
	//list($return_code, $return_content) = http_post_data($url, $data);  

	}  
	
	//数字转换成中文
	public function numberTocncap($data){
		/**
		*  人民币数字转中文币制
		* @param  string $data  待转换的数字
		* @return  string 返回转换后的中文数字
		*/
	
	   $capnum = array( "零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖" );
	   $capdigit = array( "", "拾", "佰", "仟" );
	   $subdata = explode( ".", $data );
	   $yuan = $subdata[0];
	   $j = 0;
	   $nonzero = 0;
	   for( $i=0; $i<strlen($subdata[0]); $i++ ){
		if( $i==0 ){ //确定个位
		  if($subdata[1]){
			$cncap = (substr($subdata[0],-1,1)!=0) ? "元" : "元零";
		  }else{
			$cncap = "元";
		  }
		 }
		 if( $i==4 ){ //确定万位
			$j = 0;
			$nonzero = 0;
			$cncap = "万" . $cncap;
		 }
		 if($i==8){ //确定亿位
			$j = 0;
			$nonzero = 0;
			$cncap = "亿" . $cncap;
		 }
		 $numb = substr($yuan,-1,1); //截取尾数
		 $cncap = ($numb) ? $capnum[$numb].$capdigit[$j].$cncap : (($nonzero)?"零".$cncap:$cncap);
		 $nonzero = ($numb) ? 1 : $nonzero;
		 $yuan = substr($yuan,0,strlen($yuan)-1); //截去尾数
		 $j++;
	   }
	   if($subdata[1]){
		$chiao=(substr($subdata[1],0,1))?$capnum[substr($subdata[1],0,1)]."角":"零";
		$cent=(substr($subdata[1],1,1))?$capnum[substr($subdata[1],1,1)]."分":"零分";
	   }
	   $cncap .= $chiao.$cent."整";
	   $cncap = preg_replace("/(零)+/","\\1",$cncap); //合并连续“零”
	   return $cncap;
	}
    
	//处理SQL字段，获得日期格式内容
	public function SQLDate2String($arrDate,$sType=''){
		//$arrDate = $dealerProject->mCreatedDate;
		//print_r($arrDate);
		
		$sDate = json_encode($arrDate);
		$sDate = json_decode($sDate)->date;
		//echo $arrDate->date;
		
		if($sDate == '1900-01-01 00:00:00'){
			return '';
		}
		if($sType == ''){
			return substr($sDate,0,19);
		}
		
		if($sType == 'YMD'){
			return substr($sDate,0,10);
		}
		
		return substr($sDate,0,19);
		
	}
  
	//获取配置文件参数
	public function configGet($file, $ini, $type="string"){
		/**
	 * 配置文件操作(查询了与修改)
	 * 默认没有第三个参数时，按照字符串读取提取''中或""中的内容
	 * 如果有第三个参数时为int时按照数字int处理。
	 *调用demo
	 <?php
    $name="admin";//kkkk
    $bb='234';
    $db=4561321;
    $kkk="admin";
	?>

		$name="admin";//kkkk
		$bb='234';
		 
		$bb=getconfig("./2.php", "bb", "string");
		updateconfig("./2.php", "name", "admin");
	*/
	
		//如果文件不存在，则返回空值
		if(!file_exists($file)) return "";
		
		$str = file_get_contents($file);
		//查找关键字是否存在
		$key = $ini."=";
		if(!strstr($str,$key)){
			//如果不存在，则返回空值
			return "";
		}
		
		if ($type=="int"){
			$config = preg_match("/".preg_quote($ini)."=(.*);/", $str, $res);
			return $res[1];
		}
		else{
			$config = preg_match("/".preg_quote($ini)."=\"(.*)\";/", $str, $res);
			if($res[1]==null){  
				$config = preg_match("/".preg_quote($ini)."='(.*)';/", $str, $res);
			}
			return $res[1];
		}
	}
 
	//更新配置文件
	public function configUpdate($file, $ini, $value,$type="string"){
		//如果文件不存在，则创建一个
		if(!file_exists($file)){
			$myfile = fopen($file, "w");
			$txt = "<?php \n";			
			fwrite($myfile, $txt);
			$txt = "$".$ini."=\"".$value."\";\n";			
			fwrite($myfile, $txt);
			fclose($myfile);
			return;
		} 
		
		$str = file_get_contents($file);
		
		//查找关键字是否存在
		$key = $ini."=";
		if(!strstr($str,$key)){
			//如果不存在，则直接添加
			$txt = "$".$ini."=\"".$value."\";\n";
			
			$handle = fopen($file, "a");
			fwrite($handle, $txt);
			fclose($handle);
			return "OK";
		}
		
		$str2="";
		if($type=="int"){   
			$str2 = preg_replace("/".preg_quote($ini)."=(.*);/", $ini."=".$value.";",$str);
		}
		else{
			$str2 = preg_replace("/".preg_quote($ini)."=(.*);/",$ini."=\"".$value."\";",$str);
		}
		file_put_contents($file, $str2);
	}
	
	//将网络文件保存到本地
	public function httpSaveFile($fileFrom,$fileTo) {
		 $ch = curl_init ($fileFrom);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		 
		 //curl_setopt($ch, CURLOPT_PROXY, "10.22.83.102:8080");
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		 curl_setopt($ch, CURLOPT_URL, $fileFrom);
			
		 $img = curl_exec ($ch);
		 curl_close ($ch);
		 $fp = fopen($fileTo,'w');
		 fwrite($fp, $img);
		 fclose($fp);
	}
	
	
	//获得系统参数
	public function systemGet($name,$official=''){
		
		if($official==''){
			$official = 'insider';			
		}
		
		$file = ROOTWeixin.'/weixin/system/class/config_'.$official.'.json';	

		//echo $file,'<br>';
		$obj = json_decode(file_get_contents($file),true);
		
		return $obj[$name];
		
	}
	
	
	
	
	
	
	
	
}