<?php

/**

http://www.vvtiger.com/weixin/system/class/msg.insider.vvtiger.php


*/

if(!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['Weixin_RootPath']);
include_once ROOTWeixin.'/weixin/system/class/token/WXBizMsgCrypt.php';

class QYMsg{
	/**


*/

	public $strUserFrom ;
	public $strUserTo ; 

	public $msgType ;	//消息类型
	public $msgId ;	//消息ID

	public $msgText ;	//文本内容的消息

	public $mediaId;	//媒体的ID
	public $picUrl;		//图形类型消息，图形的URL	

	public $format;		//语音格式
	public $recognition;	//语音识别出来的文字内容

	public $thumbMediaId;	//视频消息缩略图的媒体id

	public $location_X;		//地理位置
	public $location_Y;		//地理位置
	public $scale;			//地图缩放大小
	public $label;			//地理位置信息

	public $title;			//消息标题
	public $description;	//消息描述
	public $url;			//消息链接
	//public $url;			//消息链接

	public $event;			//事件类型，subscribe(订阅)、unsubscribe(取消订阅)
	public $eventKey;		//事件KEY值，qrscene_为前缀，后面为二维码的参数值
	public $ticket;			//二维码的ticket，可用来换取二维码图片

	public $latitude;		//地理位置纬度
	public $longitude;		//地理位置经度
	public $precision;		//地理位置精度
	
	public $errcode;
	public $errmsg;
	
	private $wxBase;
	private $myLog;
	private $myTools;
	private $myToken;
	private $myApp;
	private $myStaff;
	private $mOfficial;

	/**
* 函数名字：读取XML消息
* 时间：	2016-04-29
* *
* @param string strXML*
* @return 0：OK，1-99：错误

<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>

*/
	private $sReqTimeStamp ;
	private $sReqNonce;
	private $wxcpt;
	
	//初始化
	public function __construct($wxBase){
		$this->wxBase = $wxBase;
		$this->mOfficial = $wxBase->corpName;
		$this->myLog = $wxBase->getClass('WXLog');
		$this->myStaff = $wxBase->getClass('WXStaff');
		$this->myTools = $wxBase->getClass('WXTools');
		$this->myApp = $wxBase->getClass('WXApp');
		$this->conn = $wxBase->getConn();
		$this->myToken = $wxBase->getToken();
	}
	
	//加密信息
	public function encodeMsgString($sRespData){
		//加密消息
		$sEncryptMsg = ''; //xml格式的密文
		$errCode = $this->wxcpt->EncryptMsg($sRespData, $this->sReqTimeStamp, $this->sReqNonce, $sEncryptMsg);
		return $sEncryptMsg;
	}
		
	//解密微信服务器提交的内容
	public function decodeMsgString(){
				
		$token = $this->myTools->systemGet('MsgToken');
		$encodingAesKey = $this->myTools->systemGet('MsgAesKey');
		
		$this->wxcpt = new WXBizMsgCrypt($token, $encodingAesKey,$this->wxBase->corpID);
		
		//开始获得参数，
		$sReqMsgSig = $_GET['msg_signature'];
		$this->sReqTimeStamp = $_GET['timestamp'];
		$this->sReqNonce = $_GET['nonce'];
		
		// post请求的密文数据
		$sReqData = file_get_contents("php://input");	

		$sMsg = "";  // 解析之后的明文
		$errCode = $this->wxcpt->DecryptMsg($sReqMsgSig, $this->sReqTimeStamp, $this->sReqNonce, $sReqData, $sMsg);
				
		if ($errCode != 0) {
			$this->myLog->saveLog("001 ： 这里有错误"+$errCode);
			return '';
		}		
		return $sMsg;		
	}
	
	//读取解析消息
	public function readMsg($strXML) {

		try{
			//读入整个XML内容
			$xml = simplexml_load_string($strXML);
			//print_r($xml);

			//赋值
			$this->strUserFrom = (string)$xml->ToUserName;			
			$this->strUserTo = $xml->FromUserName;
			$this->msgType = trim($xml->MsgType);
			$this->msgId = $xml->MsgId;

			//事件类型
			if($this->msgType == "event"){
				$this->event = $xml->Event;
				$this->eventKey = $xml->EventKey;
				$this->ticket = $xml->Ticket;
				$this->latitude = $xml->Latitude;
				$this->longitude = $xml->Longitude;
				$this->precision = $xml->Precision;
				return;
			}


			//文本消息
			if($this->msgType == "text"){
				$this->msgText = $xml->Content;
				return;
			}

			//图文消息
			if($this->msgType == "image"){				
				$this->mediaId = $xml->MediaId;
				$this->picUrl = $xml->PicUrl;
				return;
			}

			//音频消息
			if($this->msgType == "voice"){
				$this->mediaId = $xml->MediaId;
				$this->format = $xml->Format;				
				$this->recognition = $xml->Recognition;		//语音识别的文字
				return;
			}

			//视频消息，小视频
			if($this->msgType == "video" || $this->msgType == "shortvideo"){				
				$this->mediaId = $xml->MediaId;
				$this->thumbMediaId = $xml->ThumbMediaId;
				return;
			}

			//地理位置
			if($this->msgType == "location"){
				$this->location_X = $xml->Latitude;
				$this->location_Y = $xml->Longitude;
				$this->scale = $xml->Precision;
				//$this->label = $xml->Label;
				return;
			}

			//链接消息
			if($this->msgType == "link"){
				$this->title = $xml->Title;
				$this->description = $xml->Description;
				$this->url = $xml->Url;
				$this->picUrl = $xml->PicUrl;
				return;
			}
			return 0;
		}catch(Exception $e){ 
			return -1;
		}

	}

	//获得反馈文本格式
	public function getMsgText($content){

	$xmlText = "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  </xml>";

		$msgType = "text";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$content);

	}
	
	//返回图片消息
	public function getMsgImage($mediaid){

	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>";

		$msgType = "image";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$mediaid);

	}

/**********************************************************************************************
* 函数名字：获得回复语音消息
* 时间：	2016-04-29
* *
* @param string 文本消息内容
* @return xml格式文本消息
*/
	public function getMsgVoice($mediaid){

	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>";

		$msgType = "voice";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$mediaid);

	}

/**********************************************************************************************
* 函数名字：获得回复视频消息
* 时间：	2016-04-29
* *
* @param string 文本消息内容
* @return xml格式文本消息
*/
	public function getMsgVideo($mediaid,$title,$description){

	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video>
</xml>";

		$msgType = "video";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$mediaid,$title,$description);

	}

/**********************************************************************************************
* 函数名字：获得回复音乐消息
* 时间：	2016-04-29
* *
* @param string 文本消息内容
Title	否	音乐标题
Description	否	音乐描述
MusicURL	否	音乐链接
HQMusicUrl	否	高质量音乐链接，WIFI环境优先使用该链接播放音乐
ThumbMediaId	是	缩略图的媒体id，通过素材管理中的接口上传多媒体文件，得到的id

* @return xml格式文本消息
*/
	public function getMsgMusic($title,$description,$url,$url2,$mediaid){

	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>";

		$msgType = "music";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$title,$description,$url,$url2,$mediaid);

	}

	//反馈单个图文消息
	public function getMsgImgText($title,$description,$picurl,$url){

	
	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>%s</Articles>
</xml>";

	//获得单项文本
	$xmlItem = "<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>";

		$line = sprintf($xmlItem,$title,$description,$picurl,$url);

		$num = 1;
		$msgType = "news";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		return sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$num,$line);

	}

	//返回多个图文消息
	public function getMsgArticles($num,$title,$description,$picurl,$url){

	$xmlText = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>%s</Articles>
</xml>";

	//获得单项文本
	$xmlItem = "<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>";



		
		
		$strLine = "";
		for($i=0;$i<$num;$i++){
			$line = sprintf($xmlItem,$title[$i],$description[$i],trim($picurl[$i]),$url[$i]);
			
			//$this->myLog->testLog($line);
			$strLine = $strLine.$line;
			
		}


		$msgType = "news";	//消息类型
		$time = time();		//当前时间
		$fromUsername = $this->strUserTo;
		$toUsername = $this->strUserFrom;

		$sReturn = sprintf($xmlText,$fromUsername,$toUsername,$time,$msgType,$num,$strLine);
		return $sReturn;

	}
	
	//主动发送消息给用户
	public function sendMsgTexttoUser($sWXID,$appid,$source){
	/**********************************************************************************************
* 函数名字： 将加密的消息发通告微信服务器发给用户
* 时间：	2016-08-30
* *

* //https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=$this->myToken
*/
		
		if($appid == ''|| is_null($appid) ){
			$slog='错误APPID：'.$data;
			$this->myLog->errorLog($slog);
			return '{"errcode":1,"errmsg": "appid is null",}';
		}
		
		if($sWXID== '' || is_null($sWXID) || strlen($sWXID)<8){
			$slog='错误WXID：'.$sWXID;
			$this->myLog->errorLog($slog);
			return '{"errcode":2,"errmsg": "WXID is error",}';
		}
		
		$url  = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->myToken;  
		$data = json_encode(array('touser'=>$sWXID, 'msgtype'=>'text', 'agentid'=>$appid, 'text'=>array('content'=>urlencode($source)))); 
		
		$json_string = $this->myTools->httpsPost($url, urldecode($data)); 	
		
		$obj=json_decode($json_string); 
		if($obj->errcode!=0){
			$slog='发送结果错误：'.$data.' '.$json_string;
			$this->myLog->errorLog($slog);
		}  
		return $json_string;
	}

	//主动发送卡片消息给用户
	public function sendMsgCardtoUser($sWXID,$appid,$sTitle,$Description,$sURL,$btntxt){
	/**********************************************************************************************


	$sWXID = "tiger.yang";
	$sAppID = 15;
	$sTitle = "测试卡片";
	$Description = "内容描述";
	$sURL = "http://www.com/index.dhtml";
	$sbtn = '更多';
	$result = $myMsg->sendMsgCardtoUser($sWXID,$sAppID,$sTitle,$Description,$sURL,$sbtn);
	echo $result;

*/

	//调用演示
		$url  = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->myToken;  
		
		$item1 = array("title"=>urlencode($sTitle),"description"=>urlencode($Description),"url"=>urlencode($sURL),"btntxt"=>urlencode($btntxt));

		$data = json_encode(array('touser'=>$sWXID, 'msgtype'=>'textcard', 'agentid'=>$appid, 'textcard'=>$item1));  
			
		//echo $data;
			
	//定义工具对象	
		$json_string = $this->myTools->httpsPost($url, urldecode($data));  

		$obj=json_decode($json_string); 
		if($obj->errcode!=0){
			$slog='错误：'.$data.$json_string;
			$this->myLog->errorLog($slog);
		}
		
		return $json_string;
	}
		
	//主动发送图文消息给用户
	public function sendMsgPicUrltoUser($sWXID,$appid,$sTitle,$Description,$sURL,$sPic){
	/**********************************************************************************************
* 函数名字： 将加密的图文消息发通告微信服务器发给用户
* 时间：	2016-08-30
* *


$sWXID = "tiger.yang";
$sAppID = 15;
$sTitle = "测试图文";
$Description = "内容描述";
$sURL = "http://www.111.com/index.dhtml";
$sPic = "http://image.shang-ma.com/public/20160829/cs.jpg";
$result = $myMsg->sendMsgPicUrltoUser($sWXID,$sAppID,$sTitle,$Description,$sURL,$sPic);
echo $result;





*/

	//调用演示	
		if($appid == ''|| is_null($appid) ){
			$slog='错误APPID：'.$data;
			$this->myLog->errorLog($slog);
			return '{"errcode":1,"errmsg": "appid is null",}';
		}
		if($sWXID== '' || is_null($sWXID) || strlen($sWXID)<8){
			$slog='错误WXID：'.$sWXID;
			$this->myLog->errorLog($slog);
			return '{"errcode":2,"errmsg": "WXID is error",}';
		}
		
		
		$url  = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->myToken;  
		
		$item1 = array("title"=>urlencode($sTitle),"description"=>urlencode($Description),"url"=>urlencode($sURL),"picurl"=>urlencode($sPic));
		$itemTotal = array("articles"=>array($item1));
		
		$data = json_encode(array('touser'=>$sWXID, 'msgtype'=>'news', 'agentid'=>$appid, 'news'=>$itemTotal));  
						
	//定义工具对象	
		$json_string = $this->myTools->httpsPost($url, urldecode($data));  	
		$obj = json_decode($json_string); 
		if($obj->errcode!=0){
			$slog='错误：'.$data.$json_string;
			$this->myLog->errorLog($slog);
		}
		
		return $json_string;
	}
	
	//主动发送图文列表给用户
	public function sendMsgListtoUser($sWXID,$appid,$sTitle,$Description,$sURL,$sPic){
	/**********************************************************************************************
* 函数名字： 将加密的图文消息发通告微信服务器发给用户
* 时间：	2016-08-30
* *

*/

	//调用演示
		$url  = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->myToken;  
		
		//获得数组长度
		$intCount = count($sTitle);
		$arrItem[$intCount];
		for($i=0 ; $i<$intCount ; $i++){
			$item = array("title"=>urlencode($sTitle[$i]),"description"=>urlencode($Description[$i]),"url"=>urlencode($sURL[$i]),"picurl"=>urlencode($sPic[$i]));
			//print_r($item);
			//array_push($arrItem,$arrItem);
			
			$arrItem[$i] = $item;
			
		}
		
		//print_r($arrItem);
		
		$itemTotal = array("articles"=>array_values($arrItem));
		
		$data = json_encode(array('touser'=>$sWXID, 'msgtype'=>'news', 'agentid'=>$appid, 'news'=>$itemTotal));  
		//echo $data;
			
	//定义工具对象
		$json_string = $this->myTools->httpsPost($url, urldecode($data));  	
		$obj=json_decode($json_string); 
		if($obj->errcode!=0){
			$slog='错误：'.$data.$json_string;
			$this->myLog->errorLog($slog);
		}
		return $json_string;
	}
	

}

