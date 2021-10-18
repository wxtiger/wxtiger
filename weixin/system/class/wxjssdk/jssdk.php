<?php

if(!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['Weixin_RootPath']);
include_once ROOTWeixin.'/system/class/base.insider.php';
//include_once ROOTWeixin.'/system/class/base.official.php';

class JSSDK {

  public $corpType = 'Insider';		//默认是企业号
  private $corpId;
  private $corpName ;
  private $url ; 
  
  private $token = '';
  private $myLog ;
  private $myTools;
 

  public function __construct($wxBase,$sUrl,$corpType = 'Insider') {	
  
	$this->corpName = $wxBase->corpName;
	$this->corpID = $wxBase->corpID;
	$this->corpType = $corpType;
	
	//$wxBase = new WXPublic($scorpName);
	$this->token =  $wxBase->getToken($this->corpName);
	$this->myLog = $wxBase->getClass('WXLog');
	$this->myTools = $wxBase->getClass('WXTools');	
	
	$this->url = $sUrl;
  }

	public function getSignPackage() {

		$jsapiTicket = $this->getJsApiTicket();		
		$strUrl = $_SERVER[REQUEST_URI];			
		$n1 = strpos($strUrl, "URL=");	
		$url = substr($strUrl,$n1+4);
		$url = substr($url,0,strlen($url)-1);
		//echo $url;

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		//echo $string;

		$signature = sha1($string);

		$signPackage = array(
		  "appId"     => $this->corpID,
		  "nonceStr"  => $nonceStr,
		  "timestamp" => $timestamp,
		  "url"       => $url,
		  "signature" => $signature,
		  "rawString" => $string
		);
		return $signPackage; 
  }

	private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

	private function getJsApiTicket() {
		
		$fileName = ROOTWeixin.'/system/class/wxjssdk/token_'.$this->corpName.'.json';
		$arrToken = json_decode('');
		
		if(is_file($fileName)){
			$arrToken = json_decode(file_get_contents($fileName));
			if($arrToken->expire_time >= time()) {
				$this->ticket = $arrToken->ticket;
				return $arrToken->ticket;
			}
		}
		
		//参考：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#1	
		
		if($this->corpType == 'Insider'){
			$url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
		}else{			
			$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token='.$this->token;	
		}
		$res = json_decode($this->myTools->httpsGet($url));
		
		$ticket = $res->ticket;
		if($ticket) {
			$arrToken->expire_time = time() + 7000;
			$arrToken->ticket = $ticket;
			file_put_contents($fileName, json_encode($arrToken));
						
			return $ticket;	
		}
		return '';
	
	
	}


}

