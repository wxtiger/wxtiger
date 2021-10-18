<?php
/**
*/

if(!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['Weixin_RootPath']);

include_once ROOTWeixin.'/weixin/system/class/msg.insider.php';
//include_once ROOTWeixin.'/weixin/system/class/staff.insider.php';
include_once ROOTWeixin.'/weixin/system/class/tools.share.php';
include_once ROOTWeixin.'/weixin/system/class/log.share.php';
include_once ROOTWeixin.'/weixin/system/class/app.share.php';


class WXInsider {

/**

*/

	public $corpName = '';
	public $corpID = '';	
	public $corpSecret = '';
	public $token = '';
	
	private $myTools;
	private $myLog;
	private $myApp;
	private $myMsg;
	private $myStaff;
	private $qyCustomer;
	
	private $conn;
	private $tokenPath = ROOTWeixin.'/weixin/system/class/token';
	
	public function __construct($corpName=''){
		
		$this->myTools = new WXTools();
		$this->myLog = new WXLog();
		$this->myApp = new WXApp();
		
		$this->tokenPath = ROOTWeixin.'/weixin/system/class/token';
		$this->corpName = $this->myTools->systemGet('CorpName');
		$this->corpID = $this->myTools->systemGet('CorpAppID');
		$this->corpSecret = $this->myTools->systemGet('CorpAppSecret');
		
		$this->token = $this->getToken();
		$this->myMsg = new QYMsg($this);
		//$this->myStaff = new QYStaff($this);
		
	}

	public function getClass($sName){
				
		if($this->token == ''){
			$this->token = $this->getToken();
		}
		
		switch ($sName){
		case 'WXMsg':
			return $this->myMsg;
		case 'WXStaff':
			return $this->myStaff;
		case 'WXLog':
			return $this->myLog;
		case 'WXTools':
			return $this->myTools;
		case 'WXApp':
			return $this->myApp;
		case 'SQL_Connect': 			
			return $this->getConn();		
		default:
			return null;
		}
	}
	
	//获得数据库链接
	public function getConn(){
		
		if($this->conn != null){
			return $this->conn;
		} 
		
		//连接数据库
		$conn = mysqli_connect("127.0.0.1","admin","admin","Weixin");
		if ( mysqli_connect_error ()){
			return null;
		}
		if (!$conn) {
			return null;
		}
		
		$this->conn = $conn;
		return $this->conn;	
	}
	
	
	//获得Token
	public function getToken($isNew=0){
		//$isNew 强行取最新的token
		
		if($isNew!=1){
			if($this->token != ''){
				return $this->token;
			}
		}
		
		
		$fileName = $this->tokenPath.'/token_'.$this->corpID.'.json';
		$obj = json_decode('{"expire_time":0,"access_token":""}');
		
		if(is_file($fileName) && $isNew!=1){
			$obj = json_decode(file_get_contents($fileName));
			if ($obj->expire_time >= time()) {
				$this->token = $obj->access_token;
				return $obj->access_token;
			}
		}		
		
		// 如果是企业号用以下URL获取access_token
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->corpID."&corpsecret=".$this->corpSecret;	
		//echo $this->myTools->httpsGet($url);
		$res = json_decode($this->myTools->httpsGet($url));
		
		//property_exists(json, 'access_token')
		if(!isset( $mydata['access_token']) ) {
			return '';
		}
		
		$access_token = $res->access_token;
		
		if ($access_token){
			$obj->expire_time = time() + 6000;
			$obj->access_token = $access_token;
			file_put_contents($fileName,json_encode($obj));
			
			$this->token = $access_token;
			return $access_token;
		}		
		return '';	
	}
	
	//获得外部客户的Token
	public function getCustomerToken(){
		
		
		$secret = $this->myTools->systemGet('CorpCustomerSecret');
		
		$fileName = $this->tokenPath.'/tokenCustomer_'.$this->corpID.'.json';
		if(is_file($fileName)){
			$data = json_decode(file_get_contents($fileName));
			if ($data->expire_time >= time()) {
				return $data->access_token;
			}
		}
		// 如果是企业号用以下URL获取access_token	
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->corpID."&corpsecret=".$secret;
		  
		$res = json_decode($this->myTools->httpsGet($url));
		$access_token = $res->access_token;
		if ($access_token) {
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			file_put_contents($fileName, json_encode($data));
		}
		return $access_token;
	}
	
	//获得通讯录的Token
	public function getAddressToken($corpName){
		
		$secret = $this->myTools->systemGet('CorpAddressSecret');
		$fileName = $this->tokenPath.'/tokenAddress_'.$this->corpID.'.json';
		if(is_file($fileName)){
			$data = json_decode(file_get_contents($fileName));
			if ($data->expire_time >= time()) {
				return $data->access_token;
			}
		}
		
		// 如果是企业号用以下URL获取access_token	
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->corpID."&corpsecret=".$secret;
		  
		$res = json_decode($this->myTools->httpsGet($url));
		$access_token = $res->access_token;
		if ($access_token) {
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			file_put_contents($fileName, json_encode($data));
		}
		return $access_token;
	}
	
	//获得企业应用的Token
	public function getAppToken($appID='0'){

		$secret = $this->myTools->systemGet('CorpAppSecret_'.$appID);
		
		$fileName = $this->tokenPath.'/tokenApp_'.$this->corpID.'_'.$appID.'.json';
		if(is_file($fileName)){
			$data = json_decode(file_get_contents($fileName));
			if ($data->expire_time >= time()) {
				return $data->access_token;
			}
		}		
		
		// 如果是企业号用以下URL获取access_token	
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->corpID."&corpsecret=".$secret;
		  
		$res = json_decode($this->myTools->httpsGet($url));
		$access_token = $res->access_token;
		if ($access_token) {
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			file_put_contents($fileName, json_encode($data));
		}
		return $access_token;
	}
		
	//根据Code获得人员的ID
	public function getWXIDbyCode($code){
		//根据Code，获得用户的微信ID
		$strUrl = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=".$this->token."&code=".$code;
		$result = json_decode($this->myTools->httpsGet($strUrl)); 
		return $result->UserId;
	}

	//判断当前PHP是否运行执行
	public function isAllowRun(){
		
		/*
		//限制其他服务器调用
	echo $_SERVER['HTTP_USER_AGENT'];	//获得浏览器对象
	echo $_SERVER['HTTP_USER_AGENT'];	//获得浏览器对象
	
	if($_SERVER['SERVER_NAME'] !='localhost'){
		echo '';
		return;
	}else{
		if($_SERVER['SERVER_ADDR']!=''){
			echo '';
			return;
		}
	}
	*/
		return true;
	}
	
 
}