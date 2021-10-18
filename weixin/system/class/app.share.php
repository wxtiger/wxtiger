<?php

/**
应用函数库，解决应用逻辑方面的常用函数，
适合企业微信和微信公众号



*/





if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
//echo ROOTWeixin;

include_once ROOTWeixin.'/weixin/system/class/log.share.php';
include_once ROOTWeixin.'/weixin/system/class/tools.share.php';

//$myApp = new WXAPP();
//$conn = $myApp->getConn();



class WXApp{
/**

*/
		
	private $conn;	
	private $myLog;
	private $myTools;
	public $myToken;
	
	//模板消息的参数
	public $customerID;
	public $title;			//消息标题
	public $remark;
	public $kayword1;
	public $kayword2;
	public $kayword3;
	public $kayword4;
	public $gotourl;
		
	public function __construct($token='') {
		//构造函数， 创建时，
		$this->myLog = new WXLog();
		$this->myTools = new WXTools();
		$this->myToken = $token;
		$this->conn =  $this->getConn();
	}
	
	//获得数据库链接
	public function getConn(){
		
		if($this->conn != null){
			return $this->conn;
		} 
		
		//连接数据库
		$conn = mysqli_connect("127.0.0.1","admin","admin","weixin");
		if ( mysqli_connect_error ()){
			return null;
		}
		if (!$conn) {
			return null;
		}
		$this->conn = $conn;
		return $this->conn;	
	}
	
	//记录用户其他操作
	public function doSaveAction($sOfficial,$sWXID,$sActionType,$sActionComment){
		
		//02: 不检查用户是否已经关注过
		$sText2 = htmlspecialchars($sActionComment); //strip_tags
			$sText2 = str_replace('+','＋',$sText2);
			$sText2 = str_replace('=','＝',$sText2);
			$sText2 = str_replace(',','，',$sText2);
			$sText2 = str_replace('&','﹠',$sText2);
			$sText2 = str_replace('#','﹟',$sText2);
			$sText2 = str_replace('%','﹪',$sText2);
			$sText2 = str_replace('?','？',$sText2);
			$sText2 = str_replace('"','“',$sText2);
			$sText2 = str_replace("'","‘",$sText2);
			$sText2 = str_replace(" ","　",$sText2);
			$sText2 = str_replace("\n","　",$sText2);
			
		
		//05 插入一条操作记录				
			$strSQL = "INSERT INTO WXRecord
				   (WXID
				   ,CreatedDate
				   ,ActionType
				   ,Comment
				   ,Official)
			 VALUES
				   ('".$sWXID."'
				   ,now()
				   ,'".$sActionType."'
				   ,'".$sText2."'
				   ,'".$sOfficial."'
				   )";

		$query = $this->conn->query($strSQL);
		if($query == false){
			//出错日志
			$strTemp = "doSaveAction(): 插入新纪录错误：".$strSQL;
			$this->myLog->errorLog($strTemp);
			
			return 'SQL error('.$strSQL.')"}';
		}
		return 'OK';		
	}
	
	
	//获取公众号入口的URL的二维码
	public function getOfficialUrl($token,$sKey,$sSecond=0){
		$url  = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;  

		if($sSecond = ''){
				$data = json_encode(array('action_name'=>'QR_LIMIT_STR_SCENE', 'action_info'=>array('scene'=>array('scene_str'=>$sKey)))); 
		}else{
				$data = json_encode(array('expire_seconds'=>$sSecond,'action_name'=>'QR_LIMIT_STR_SCENE', 'action_info'=>array('scene'=>array('scene_str'=>$sKey)))); 
		}
		$result = $this->myTools->httpsPost($url, $data);

		$obj = json_decode($result);
		$sUrl = str_replace('\/','/',$obj->url);
		return $sUrl;	
	}
	
	

	//发消息给企业微信的员工
	public function sendMsgToStaff($sOfficial,$wxid,$appid,$sText){
		
		$sUrl = 'http://'.$this->myTools->systemGet('HttpServerName').'/weixin/system/tools/sendMsgtoStaff.php?';
		$sUrl = $sUrl.'Official='.$sOfficial.'&WXID='.$wxid.'&AppId='.$appid.'&Text='.urlencode($sText).'&';
				
		return $this->myTools->httpGet($sUrl);
	}
	//发消息给企业微信的管理员
	public function sendMsgToManager($sOfficial,$sText){
		
		if($sOfficial == '' ){
			$wxid = '17665201558';
			$appid = '1000002';
		}
		
		$sUrl = 'http://'.$this->myTools->systemGet('HttpServerName').'/weixin/system/tools/sendMsgtoStaff.php?';
		$sUrl = $sUrl.'Official='.$sOfficial.'&WXID='.$wxid.'&AppId='.$appid.'&Text='.urlencode($sText).'&';
				
		return $this->myTools->httpGet($sUrl);
	}

	//发送模板消息
	public function sendTemplateMsg($token,$templateID){
	
		$url  = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;  

		$strFirst = array('value'=>$this->title."\n", 'color'=>'#173177');
		$strText1 = array('value'=>$this->kayword1, 'color'=>'#173177');
		$strText2 = array('value'=>$this->kayword2, 'color'=>'#173177');
		$strText3 = array('value'=>$this->kayword3, 'color'=>'#173177');
		$strText4 = array('value'=>$this->kayword4, 'color'=>'#173177');
		$strRemark = array('value'=>"\n".$this->remark, 'color'=>'#173177');
		$arrData = array('first'=>$strFirst,'keyword1'=>$strText1,'keyword2'=>$strText2,'keyword3'=>$strText3,'keyword4'=>$strText4,'remark'=>$strRemark);

		$data = json_encode(array('touser'=>$this->customerID, 'template_id'=>$templateID,'url'=>$this->gotourl,'data'=>$arrData));   
		return $this->myTools->httpsPost($url, $data);  
	}
	
		
	//获得信息
	public function getMsgString(){
		
		$token = $this->myTools->systemGet('MsgToken');
		$encodingAesKey = $this->myTools->systemGet('MsgAesKey');
		$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $this->corpID);
		
		//开始获得参数，
		$sReqMsgSig = $_GET['msg_signature'];
		$sReqTimeStamp = $_GET['timestamp'];
		$sReqNonce = $_GET['nonce'];
		
		// post请求的密文数据
		$sReqData = file_get_contents('php://input');
		
		$sMsg = "";  // 解析之后的明文
		$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
		if ($errCode != 0) {
			return '';
		}
		
		return $sMsg;		
	}
	
	//获得URL的参数
	public function UrlGetValue($sName,$sValue=''){
		
		$sFrom = '';
		if(isset($_GET[$sName])){
			$sFrom = $_GET[$sName];	
		}	
		if($sFrom == ''){
			$sFrom = $sValue;
		}
		return $sFrom;
	}
	//UrlPostValue
	public function UrlPostValue($sName,$sValue=''){
		
		$sFrom = '';
		if(isset($_POST[$sName])){
			$sFrom = $_POST[$sName];	
		}	
		if($sFrom == ''){
			$sFrom = $sValue;
		}
		return $sFrom;
	}
	
	
}

