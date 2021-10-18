<?php

/**

create table QYStaff(
ID INT NOT NULL AUTO_INCREMENT,
Official	    VARCHAR(50),
EmployeeId      VARCHAR(50),
EmployeeType    VARCHAR(50),
EmployeeStatus  VARCHAR(50),
Department      VARCHAR(50),
Title           VARCHAR(100),
UserName        VARCHAR(50),
UserMobile      VARCHAR(50),
UserEmail       VARCHAR(50),
JoinDate        Datetime,
LeaveDate       Datetime,
PassportID      VARCHAR(50),
Location        VARCHAR(50),
Team            VARCHAR(50),
Function        VARCHAR(50),
Division        VARCHAR(50),
UserComment     LONGTEXT,
WeixinID        VARCHAR(50),
WXName          VARCHAR(50),
WXUserID        VARCHAR(50),
WXUnionID       VARCHAR(50),
WXOpenID        VARCHAR(50),
WXCustomerID    VARCHAR(50),
WXID            VARCHAR(50),
CreatedDate     Datetime,
UpdatedDate     Datetime,
PRIMARY KEY(ID)
) DEFAULT charset=utf8mb4 COLLATE utf8mb4_unicode_ci;


ID 
Official
EmployeeId
EmployeeType
EmployeeStatus	//0：待入职，10:正常在职，90：待离职，100：离职
Department
Title
UserName
UserMobile
UserEmail
JoinDate
LeaveDate
PassportID
Location
Team
Function
Division
UserComment

WeixinID		//真实的微信名字，用户可能会修改
WXName			//微信昵称
WXUserID		//企业微信的账号
WXUnionID		//内部统一账号
WXOpenID		//微信支付账号
WXCustomerID	//作为企业微信客户的账号
WXID			//关注公众号服务号的账号

CreatedDate
UpdatedDate


*/

if(!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['Weixin_RootPath']);
include_once ROOTWeixin.'/system/class/base.insider.vvtiger.php';

class QYStaff{
/**
* 类名：微信用户类
*
*
* @author tiger yang
* @version 2016-09-05
* @package com.zeiss.zctyan.WXMsg
*/
	public $WXID ;				//员工微信账号
		
	//员工人事数据库字段
	public $hrID;
	public $hrEmployeeId;
	public $hrEmployeeType;
	public $hrEmployeeStatus;
	public $hrDepartment;
	public $hrTitle;
	public $hrUserName;
	public $hrUserMobile;
	public $hrUserEmail;
	public $hrJoinDate;
	public $hrLeaveDate = '0000-00-00';
	public $hrPassportID;
	public $hrLocation;
	public $hrTeam;
	public $hrFunction;
	public $hrDivision;
	public $hrUserComment;
	
	public $hrWeixinID;
	public $hrWXName;
	public $hrWXUserID;
	public $hrWXUnionID;
	public $hrWXOpenID;
	public $hrWXCustomerID;
	public $hrWXID;

	public $hrCreatedDate;
	public $hrUpdatedDate;


	//在微信后台的联系人资料
	public $wxUserID;			//用户名字
	public $wxName;				//用户名字
	public $wxMobile;			//用户手机号码
	public $wxEmail;			//用户邮件地址
	public $wxDepartmentID;		//部门ID
	public $wxPosition;			//职务
	public $wxGender;			//微信性别	0表示未定义，1表示男性，2表示女性
	public $WeixinID;			//用户真实的微信ID，可能为空，
	public $wxHeadMediaID;		//头像ID
	public $wxStatus;			//微信状态	1=已关注，2=已禁用，4=未关注
	public $wxLanguage;			//微信的语言

	private $conn;	
	private $myLog;
	private $myTools;
	private $myToken;
	private $myMsg;
	private $myApp;
	
	private $ResultOK = '{"errcode":0,"errmsg":"OK"}';
	
		
	public function __construct($wxBase) {
		//构造函数， 创建时，
		$this->hrOfficial = $wxBase->corpName;
		$this->myToken = $wxBase->getToken();
		$this->myMsg = $wxBase->getClass('WXMsg');
		$this->myLog = $wxBase->getClass('WXLog');
		$this->myTools = $wxBase->getClass('WXTools');
		$this->myApp = $wxBase->getClass('WXApp');
		$this->conn = $wxBase->getConn();
	}

	//根据UserID返回OpenID
	public function getOpenIDByUserID($sUserID){
		$url =  'https://qyapi.weixin.qq.com/cgi-bin/user/convert_to_openid?access_token='.$this->myToken;
		$data = '{"userid": "'.$sUserID.'"}';
		$obj = json_decode($this->myTools->httpsPost($url,$data));
		if($obj->errcode == 0 && $obj->openid!= ''){
			return $obj->openid;
		}
		return '';		
	}
	
	//根据OpenID返回UserID
	public function getUserIDByOpenID($sOpenID){
		$url =  ' https://qyapi.weixin.qq.com/cgi-bin/user/convert_to_userid?access_token='.$this->myToken;
		$data = '{"openid": "'.$sOpenID.'"}';
		$obj = json_decode($this->myTools->httpsPost($url,$data));
		if($obj->errcode == 0 && $obj->userid!= ''){
			return $obj->userid;
		}
		return '';		
	}
		
	//根据CustomerID获得OpenID
	public function getOpenIDByCustomerID($sOpenID){
		$url =  'https://qyapi.weixin.qq.com/cgi-bin/externalcontact/convert_to_openid?access_token='.$this->myToken;
		$data = '{"external_userid": "'.$sOpenID.'"}';
		$obj = json_decode($this->myTools->httpsPost($url,$data));
		if($obj->errcode == 0 && $obj->openid!= ''){
			return $obj->openid;
		}
		return '';
		
	}
	
	
	//新增记录
	public function insertToSQL(){
		
		if($this->hrWXUserID.$this->hrUserMobile.$this->hrEmployeeId == ''){
			return 'Error User ID is empty';
		}
		
		
		
		//否则就是插入新记录
		$strSQL = "INSERT INTO QYStaff
           (CreatedDate		   
           ,Official
           ,EmployeeId
           ,EmployeeType
           ,EmployeeStatus
           ,Department
           ,Title
           ,UserName
           ,UserMobile
           ,UserEmail
           ,JoinDate
           ,LeaveDate
           ,PassportID
           ,Location
           ,Team
           ,Function
           ,Division
           ,UserComment
           ,WeixinID
           ,WXName
           ,WXUserID
           ,WXUnionID
           ,WXOpenID
           ,WXCustomerID
           ,WXID
           ,UpdatedDate
           )
     VALUES
           (now()			
			,'".$this->hrOfficial."'
			,'".$this->hrEmployeeId."'
			,'".$this->hrEmployeeType."'
			,'".$this->hrEmployeeStatus."'
			,'".$this->hrDepartment."'
			,'".$this->hrTitle."'
			,'".$this->hrUserName."'
			,'".$this->hrUserMobile."'
			,'".$this->hrUserEmail."'
			,'".$this->hrJoinDate."'
			,'".$this->hrLeaveDate."'
			,'".$this->hrPassportID."'
			,'".$this->hrLocation."'
			,'".$this->hrTeam."'
			,'".$this->hrFunction."'
			,'".$this->hrDivision."'
			,'".$this->hrUserComment."'
			,'".$this->hrWeixinID."'
			,'".$this->hrWXName."'
			,'".$this->hrWXUserID."'
			,'".$this->hrWXUnionID."'
			,'".$this->hrWXOpenID."'
			,'".$this->hrWXCustomerID."'
			,'".$this->hrWXID."'
           ,now())";
		   
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error SQL:'.$strSQL;
			$this->myLog->saveLog($strTemp);
			//echo $strSQL,'<br>';
			return "Error SQL";
		}else{
			return "OK";
		}
		
	}
	
	//获得当前用户的微信号
	public function getWXID(){		
		return $this->wxUserID;		
	}
	
			
	//根据微信号，搜索单个用户，从微信后台资料
	public function getWXUserByWXID($wxid){
		//根据用户的WXID，获得用户的资料
		if($wxid == ""){
			//如果参数为空， 则默认为当前的ID
			$wxid = $this->WXID;
		}
		if($wxid == ""){
			$this->wxStatus = 4;
			return 1;
		}

		$strUrl = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=".$this->myToken."&userid=".$wxid;
		$result = json_decode($this->myTools->httpsGet($strUrl)); 
		//echo $this->myTools->httpsGet($strUrl),'<br>';
		//如果结果为错，则退出
		if($result->errcode != 0){
			$this->wxStatus = 4;
			$this->errCode = $result->errcode;
			$this->errMsg = $result->errmsg;
			return 3;
		}
		$this->wxUserID = $result->userid;
		$this->wxName = $result->name;
		$this->wxMobile = $result->mobile;
		$this->wxDepartmentID = $result->department;
		//$this->wxLanguage = $result->language;
		$this->wxPosition = $result->position;
		$this->wxGender = $result->gender;
		$this->wxEmail = $result->email;
		//$this->WeixinID = $result->weixinid;
		$this->wxHeadMediaID = $result->avatar;
		$this->wxStatus = $result->status;	//关注状态: 1=已关注，2=已禁用，4=未关注
	
		return 0 ;
		
	}
	 
	//清空数据
	public function setAllEmpty(){
		//用户的人事资料
		$this->hrID = '';
		$this->hrEmployeeId = '';
		$this->hrEmployeeType = '';
		$this->hrEmployeeStatus = '';
		$this->hrDepartment = '';
		$this->hrTitle = '';
		$this->hrUserName = '';
		$this->hrUserMobile = '';
		$this->hrUserEmail = '';
		$this->hrJoinDate = '';
		$this->hrLeaveDate = '';
		$this->hrPassportID = '';
		$this->hrLocation = '';
		$this->hrTeam = '';
		$this->hrFunction = '';
		$this->hrDivision = '';
		$this->hrUserComment = '';
	
		$this->hrWeixinID = '';
		$this->hrWXName = '';
		$this->hrWXUserID = '';
		$this->hrWXUnionID = '';
		$this->hrWXOpenID = '';
		$this->hrWXCustomerID = '';
		$this->hrWXID = '';

		$this->hrCreatedDate = '';
		$this->hrUpdatedDate = '';


		//在微信后台的联系人资料
		$this->wxUserID = '';			//用户名字
		$this->wxName = '';				//用户名字
		$this->wxMobile = '';			//用户手机号码
		$this->wxEmail = '';			//用户邮件地址
		$this->wxDepartmentID = '';		//部门ID
		$this->wxPosition = '';			//职务
		$this->wxGender = '';			//微信性别	0表示未定义，1表示男性，2表示女性
		$this->WeixinID = '';			//用户真实的微信ID，可能为空，
		$this->wxHeadMediaID = '';		//头像ID
		$this->wxStatus = '';			//微信状态	1=已关注，2=已禁用，4=未关注
		$this->wxLanguage = '';			//微信的语言
		
	
	}


	private function setSQLItem($row){
		
		$this->hrID = $row['ID'];	
		$this->hrOfficial = $row['Official'];	
		$this->hrEmployeeId = $row['EmployeeId'];	
		$this->hrEmployeeType = $row['EmployeeType'];	
		$this->hrEmployeeStatus = $row['EmployeeStatus'];	
		$this->hrDepartment = $row['Department'];	
		$this->hrTitle = $row['Title'];	
		$this->hrUserName = $row['UserName'];	
		$this->hrUserMobile = $row['UserMobile'];	
		$this->hrUserEmail = $row['UserEmail'];	
		$this->hrJoinDate = $row['JoinDate'];	
		$this->hrLeaveDate = $row['LeaveDate'];	
		$this->hrPassportID = $row['PassportID'];	
		$this->hrLocation = $row['Location'];	
		$this->hrTeam = $row['Team'];	
		$this->hrFunction = $row['Function'];	
		$this->hrDivision = $row['Division'];	
		$this->hrUserComment = $row['UserComment'];	
		$this->hrWeixinID = $row['WeixinID'];	
		$this->hrWXName = $row['WXName'];	
		$this->hrWXUserID = $row['WXUserID'];	
		$this->hrWXUnionID = $row['WXUnionID'];	
		$this->hrWXOpenID = $row['WXOpenID'];	
		$this->hrWXCustomerID = $row['WXCustomerID'];	
		$this->hrWXID = $row['WXID'];	
		$this->hrCreatedDate = date("Y-m-d H:i:s" ,$row['CreatedDate']);	
		$this->hrUpdatedDate = date("Y-m-d H:i:s" ,$row['UpdatedDate']);	

	}
	
	private function setHRItemByWX(){
		//这里有个问题，是以HR数据库为准，还是以用户修改的内容为准
		$this->hrUserName = $this->wxName;	
		$this->hrUserMobile = $this->wxMobile;	
		$this->hrUserEmail = $this->wxMobile;	
		$this->hrWXName = $this->wxName;	
		$this->hrWXUserID = $this->wxUserID;	
		
	}
	
	
	
	
	//根据微信号，读取单个用户的资料，从HRIS里面，
	public function readHRUserByUserID($userid){
		 	
		//如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"Conn is error"}';
		}

		if($userid == ''){
			return '{"errcode":2,"errmsg":"User ID is empty"}';
		}
			
		
		//先精确搜索
		$strSQL = "SELECT * from QYStaff where Official='".$this->hrOfficial."' AND WXUserID = '".$userid."' ";
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);		
		if(!$query){ //查询不到结果	
			return '{"errcode":4,"errmsg":"SQL is error"}';
		}		
		//赋值
		while ($row = $query->fetch_assoc()) {	
			$this->setSQLItem($row);			
			return '{"errcode":0,"errmsg":"OK"}';	
		}		
		return '{"errcode":9,"errmsg":"Record is empty"}';			
	 }

	public function readHRUserByMobile($mobile){
		 	
		//如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"Conn is error"}';
		}

		if($mobile == ''){
			return '{"errcode":2,"errmsg":"User ID is empty"}';
		}
			
		
		//先精确搜索
		$strSQL = "SELECT * from QYStaff where Official='".$this->hrOfficial."' AND UserMobile = '".$mobile."' ";
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);		
		if(!$query){ //查询不到结果	
			return '{"errcode":4,"errmsg":"SQL is error"}';
		}		
		//赋值
		while ($row = $query->fetch_assoc()) {	
			$this->setSQLItem($row);			
			return '{"errcode":0,"errmsg":"OK"}';	
		}		
		return '{"errcode":9,"errmsg":"Record is empty"}';			
	 }
	
	public function readHRUserByID($sID){
		 	
		//如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"Conn is error"}';
		}

		if($sID == ''){
			return '{"errcode":2,"errmsg":"$sID is empty"}';
		}
			
		
		//先精确搜索
		$strSQL = "SELECT * from QYStaff where Official='".$this->hrOfficial."' AND ID = ".$sID." ";
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);		
		if(!$query){ //查询不到结果	
			return '{"errcode":4,"errmsg":"SQL is error"}';
		}		
		//赋值
		while ($row = $query->fetch_assoc()) {	
			$this->setSQLItem($row);			
			return '{"errcode":0,"errmsg":"OK"}';	
		}		
		return '{"errcode":9,"errmsg":"Record is empty"}';			
	 }


	//更新数据库
	public function updateByArray($arrStaff){
		
		//01 先找记录
		$result = '';
		$sNewType = '';
		
		if($this->hrID == '' && $arrStaff['ID'] != ''){
			$result = $this->readHRUserByID($arrStaff['ID']);
			$sNewType = 'ID';
		}
		if($result != $this->ResultOK || $this->hrID == ''){
			if($arrStaff['WXUserID'] != ''){
				$result = $this->readHRUserByUserID($arrStaff['WXUserID']);
				$sNewType = 'UserID';
			}
		}		
		if($result != $this->ResultOK || $this->hrID == ''){
			if($arrStaff['UserMobile'] != ''){
				$result = $this->readHRUserByMobile($arrStaff['UserMobile']);
				$sNewType = 'Mobile';
			}
		}
		
		echo $sNewType,'<br>';
		
		//判断是否是新记录
		$isNew = false;
		if($result != $this->ResultOK || $this->hrID == ''){
			$isNew = true;
		}
			
		//02 开始赋值
		if($arrStaff['ID'] != '') $this->hrID = $arrStaff['ID'];
		if($arrStaff['Official'] != '') $this->hrOfficial = $arrStaff['Official'];
		if($arrStaff['EmployeeId'] != '') $this->hrEmployeeId = $arrStaff['EmployeeId'];
		if($arrStaff['EmployeeType'] != '') $this->hrEmployeeType = $arrStaff['EmployeeType'];
		if($arrStaff['EmployeeStatus'] != '') $this->hrEmployeeStatus = $arrStaff['EmployeeStatus'];
		if($arrStaff['Department'] != '') $this->hrDepartment = $arrStaff['Department'];
		if($arrStaff['Title'] != '') $this->hrTitle = $arrStaff['Title'];
		if($arrStaff['UserName'] != '') $this->hrUserName = $arrStaff['UserName'];
		if($arrStaff['UserMobile'] != '') $this->hrUserMobile = $arrStaff['UserMobile'];
		if($arrStaff['UserEmail'] != '') $this->hrUserEmail = $arrStaff['UserEmail'];
		if($arrStaff['JoinDate'] != '') $this->hrJoinDate = $arrStaff['JoinDate'];
		if($arrStaff['LeaveDate'] != '') $this->hrLeaveDate = $arrStaff['LeaveDate'];
		if($arrStaff['PassportID'] != '') $this->hrPassportID = $arrStaff['PassportID'];
		if($arrStaff['Location'] != '') $this->hrLocation = $arrStaff['Location'];
		if($arrStaff['Team'] != '') $this->hrTeam = $arrStaff['Team'];
		if($arrStaff['Function'] != '') $this->hrFunction = $arrStaff['Function'];
		if($arrStaff['Division'] != '') $this->hrDivision = $arrStaff['Division'];
		if($arrStaff['UserComment'] != '') $this->hrUserComment = $arrStaff['UserComment'];
		if($arrStaff['WeixinID'] != '') $this->hrWeixinID = $arrStaff['WeixinID'];
		if($arrStaff['WXName'] != '') $this->hrWXName = $arrStaff['WXName'];
		if($arrStaff['WXUserID'] != '') $this->hrWXUserID = $arrStaff['WXUserID'];
		if($arrStaff['WXUnionID'] != '') $this->hrWXUnionID = $arrStaff['WXUnionID'];
		if($arrStaff['WXOpenID'] != '') $this->hrWXOpenID = $arrStaff['WXOpenID'];
		if($arrStaff['WXCustomerID'] != '') $this->hrWXCustomerID = $arrStaff['WXCustomerID'];
		if($arrStaff['WXID'] != '') $this->hrWXID = $arrStaff['WXID'];
		
		//03 更新数据
		if($isNew){
			$result = $this->insertToSQL();
		}else{
			if($sNewType == 'ID'){
				$result = $this->updateHRUserByID($arrStaff['ID']);
			}else if($sNewType == 'UserID'){
				$result = $this->updateHRUserByUserID($arrStaff['WXUserID']);
			}else if($sNewType == 'Mobile'){
				$result = $this->updateHRUserByMobile($arrStaff['UserMobile']);
			}
		}		
		return $result;		
	}
	
	private function getUpdateString(){
		return "UPDATE QYStaff
			SET EmployeeId = '".$this->hrEmployeeId."'
				,EmployeeType = '".$this->hrEmployeeType."'
				,EmployeeStatus = '".$this->hrEmployeeStatus."'
				,Department = '".$this->hrDepartment."'
				,Title = '".$this->hrTitle."'
				,UserName = '".$this->hrUserName."'
				,UserMobile = '".$this->hrUserMobile."'
				,UserEmail = '".$this->hrUserEmail."'
				,JoinDate = '".$this->hrJoinDate."'
				,LeaveDate = '".$this->hrLeaveDate."'
				,PassportID = '".$this->hrPassportID."'
				,Location = '".$this->hrLocation."'
				,Team = '".$this->hrTeam."'
				,Function = '".$this->hrFunction."'
				,Division = '".$this->hrDivision."'
				,UserComment = '".$this->hrUserComment."'
				,WeixinID = '".$this->hrWeixinID."'
				,WXName = '".$this->hrWXName."'
				,WXUserID = '".$this->hrWXUserID."'
				,WXUnionID = '".$this->hrWXUnionID."'
				,WXOpenID = '".$this->hrWXOpenID."'
				,WXCustomerID = '".$this->hrWXCustomerID."'
				,WXID = '".$this->hrWXID."'				 
				,UpdatedDate = now()";
	}

	private function updateHRUserByID($sID=''){
	
		if($sID != ''){
			$this->hrID = $sID;
		}
		if($this->hrID == ''){
			return '{"errcode":1,"errmsg":"ID is empty"}';
		}
		
		//否则就是插入新记录
		$strSQL = $this->getUpdateString()." WHERE ID = ".$this->hrID;		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->saveLog($strTemp);			
			return '{"errcode":9,"errmsg":"Error SQL"}';
		}else{
			return $this->ResultOK;
		}
		
	}
	private function updateHRUserByUserID($sUserID){

		if($sUserID == ''){
			return '{"errcode":1,"errmsg":"ID is empty"}';
		}
		
		//否则就是插入新记录
		$strSQL = $this->getUpdateString()." WHERE Official='".$this->hrOfficial."' AND WXUserID = ".$sUserID;		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->saveLog($strTemp);			
			return '{"errcode":9,"errmsg":"Error SQL"}';
		}else{
			return $this->ResultOK;
		}
		
	}
	private function updateHRUserByMobile($sUserMobile){
	
		if($sUserMobile == ''){
			return '{"errcode":1,"errmsg":"ID is empty"}';
		}
		
		//否则就是插入新记录
		$strSQL = $this->getUpdateString()." WHERE Official='".$this->hrOfficial."' AND UserMobile = ".$sUserMobile;		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->saveLog($strTemp);			
			return '{"errcode":9,"errmsg":"Error SQL"}';
		}else{
			return $this->ResultOK;
		}
		
	}
		
	
	
	
	
	
	

	//返回当前用户在微信后台的部门编号，
	private function getWXDepartmentID(){
		//根据用户的部门， 获得用户在微信上的编号
		return '0';	   
	}
	
	//返回当前用户在微信后台的部门名字，
	public function getDepartmentName(){
		//根据用户的部门， 获得用户在微信上的编号
		return 'Other';			   
	}
	
	public function getDepartmentList(){
		$arrTemp = array('');
		return $arrTemp;
	}
	
	//获得当前用户领导的微信号
	public function getManagerWXID(){
		return '';
	}
	
	
	
	
	
	
	

	
	//根据邮件地址，读取单个用户的资料，从HRIS里面，
	public function readUserfromSQLByEmail($sEmail){

		$this->isEnabled = '0';
		
		 //如果没联机上，就退出
		if($this->conn == false){
			return "1";
		}
		
		if($sEmail == ''){
			return '2';
		}
		
		//查询数据
		$strUrl = "SELECT TOP 1 * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND ([Email] = '".$sEmail."') ";
		
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果
			return "3";
		}

		//赋值
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			$this->setSQLItem($row);
		}	
		return "0";
	 }

	//读取单个用户的资料，从HRIS里面，
	public function readUserfromSQLByID($sID){
		 	
		$this->isEnabled = '0';
		
		 //如果没联机上，就退出
		if($this->conn == false){
			return "1";
		}
		if($sID == ''){
			return '2';
		}
		
		//查询数据
		$strUrl = "SELECT TOP 1 * from [Zeiss_HRIS].[dbo].[BasicInfo] where [ID] = ".$sID." ";
		
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);		
		if($query === false){ //查询不到结果
			return "3";
		}

		//赋值
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			$this->setSQLItem($row);
		}	
		return "0";
	 }
	 
	 
	//搜索用户，从HRIS里，根据手机号，或中英文名字
	public function searchDivisionStaff($sDivision,$sKey,$sFrom,$sTo){

		 //如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"conn is error","Num":0}';
		}
		if($sKey == ''){
			return '{"errcode":2,"errmsg":"key is empty","Num":0}';
		}
		
		$sKey = str_replace('-','',$sKey);
		$sKey = str_replace(' ','',$sKey);
		$sKey = trim($sKey);
		
		if($sDivision != ''){
			$sDivision = " AND ([Divsion]='".$sDivision."')";
		}
		
		//判断是否是手机号码
		if(strlen($sKey) == 4 && is_numeric($sKey)){	
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND ([Mobile] like '%".$sKey."' OR [DirectTelephone] like '%".$sKey."')  ".$sDivision." order by [Jointdate] ";
			
		}else if(strlen($sKey) == 11 && substr($sKey,0,1)=='1'){			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([Mobile],'-', '' ) like '%".$sKey."') ".$sDivision." order by [Jointdate] ";
		}else{
			//就算是名字
			$sKey1 = mb_convert_case($sKey,MB_CASE_LOWER);
			$sKey2 = urldecode($sKey);	
						
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([EmployeeName_EN],' ', '' ) like '%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN2] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%' OR [ExternalTitle_EN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%'OR [ExternalTitle_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%') ".$sDivision." order by [Jointdate] ";
			
		}

		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);		
		if($query === false){ //查询不到结果
			return  '{"errcode":3,"errmsg":"record is empty","Num":0}';
		}

		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  
		if($sFrom == ""){
			$numFrom = 0;
		}else{
			$numFrom = (int)$sFrom;
		}
		
		if($sTo == ""){
			$numTo = 8;
		}else{
			$numTo = (int)$sTo;
		}
				
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setSQLItem($row);
				
				$team = $this->officeName.' '.$this->team;
				
				$strLine = array('WXID'=>$this->getWXID(),'UserName'=>$this->username,'Mobile'=>$this->mobile,'Email'=>$this->email,'Team'=>$team,'Title'=>$this->titleCN,'Division'=>$this->division);
				$arrLine[] = $strLine;
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		return  urldecode(json_encode($arrReturn));
	}
	 
	//搜索用户，从HRIS里，根据手机号，或中英文名字
	public function searchUserfromSQL($sKey,$sFrom,$sTo){

		 //如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"conn is error","Num":0}';
		}
		if($sKey == ''){
			return '{"errcode":2,"errmsg":"key is empty","Num":0}';
		}
		
		$sKey = str_replace('-','',$sKey);
		$sKey = str_replace(' ','',$sKey);
		$sKey = trim($sKey);
		
		//判断是否是手机号码
		if(strlen($sKey) == 4 && is_numeric($sKey)){	
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (right(REPLACE([Mobile],'-', ''),4) = '".$sKey."' OR [Ext] = '".$sKey."') order by [Jointdate] ";
			
			
		}else if(strlen($sKey) == 11 && substr($sKey,0,1)=='1'){			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([Mobile],'-', '' ) like '%".$sKey."%') order by [Jointdate] ";
		}else{
			//就算是名字
			$sKey1 = mb_convert_case($sKey,MB_CASE_LOWER);	
			
			if(''.strpos($sKey,'%') != ''){
				$sKey2 = urldecode($sKey);		//urldecode
			}else{
				$sKey2 = ($sKey);
			}
			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([EmployeeName_EN],' ', '' ) like '%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN2] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%') order by [Jointdate] ";
		}
		
		$sDivision = '';
		//判断是否是手机号码
		if(strlen($sKey) == 4 && is_numeric($sKey)){	
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND ([Mobile] like '%".$sKey."' OR [DirectTelephone] like '%".$sKey."')  ".$sDivision." order by [Jointdate] ";
			
		}else if(strlen($sKey) == 11 && substr($sKey,0,1)=='1'){			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([Mobile],'-', '' ) like '%".$sKey."') ".$sDivision." order by [Jointdate] ";
		}else{
			//就算是名字
			$sKey1 = mb_convert_case($sKey,MB_CASE_LOWER);
			$sKey2 = urldecode($sKey);	
						
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND (REPLACE([EmployeeName_EN],' ', '' ) like '%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN2] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%' OR [ExternalTitle_EN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%'OR [ExternalTitle_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%') ".$sDivision." order by [Jointdate] ";
			
		}

		
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果
			return  '{"errcode":3,"errmsg":"record is empty","Num":0}';
		}

		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  
		if($sFrom == ""){
			$numFrom = 0;
		}else{
			$numFrom = (int)$sFrom;
		}
		
		if($sTo == ""){
			$numTo = 8;
		}else{
			$numTo = (int)$sTo;
		}
				
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setSQLItem($row);
				
				$team = $this->officeName.' '.$this->division;
				
				$strLine = array('WXID'=>$this->getWXID(),'UserName'=>$this->username,'Mobile'=>$this->mobile,'Email'=>$this->email,'Team'=>$team,'Title'=>$this->titleCN,'Division'=>$this->division);
				$arrLine[] = $strLine;
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		return  urldecode(json_encode($arrReturn));
	 }

	//搜索离职用户，从HRIS里，根据手机号，或中英文名字
	public function searchLeaveUserfromSQL($sKey,$sFrom,$sTo){
		 	
		 //如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"Error conn","Num":0}';
		}
		if($sKey == ''){
			return  '{"errcode":2,"errmsg":"Error key","Num":0}';
		}
		
		$sKey = str_replace('-','',$sKey);
		$sKey = str_replace(' ','',$sKey);
		$sKey = trim($sKey);
		
		//判断是否是手机号码
		if(strlen($sKey) == 11 && substr($sKey,0,1)=='1'){			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where (Not ([deleted] = 0)) AND (REPLACE([Mobile],'-', '' ) like '%".$sKey."%') order by [Jointdate] ";
		}else{
			//就算是名字
			$sKey1 = mb_convert_case($sKey,MB_CASE_LOWER);
			$sKey2 = urldecode($sKey);		//urldecode
			
			$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where (Not ([deleted] = 0)) AND (REPLACE([EmployeeName_EN],' ', '' ) like '%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN2] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey1)."%' OR [EmployeeName_CN] like N'%".iconv('utf-8', 'GBK//IGNORE',$sKey2)."%') order by [Jointdate] ";
		}
		
		//echo $strTemp;
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果		
			return  '{"errcode":3,"errmsg":"record empty","Num":0}';
		}
		
		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  
		if($sFrom == ""){
			$numFrom = 0;
		}else{
			$numFrom = (int)$sFrom;
		}
		
		if($sTo == ""){
			$numTo = 8;
		}else{
			$numTo = (int)$sTo;
		}
				
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setSQLItem($row);
				
				$team = $this->officeName.' '.$this->division;
				
				$strLine = array('WXID'=>$this->getWXID(),'UserName'=>$this->username,'Mobile'=>$this->mobile,'Email'=>$this->email,'Team'=>$team,'Title'=>$this->titleCN);
				$arrLine[] = $strLine;
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		return  urldecode(json_encode($arrReturn));
		
	 }

	//从HRIS里面，搜索批量用户，根据部门编号
	public function searchUserfromSQLByTeam($sTeamId,$sFrom='0',$sTo='8'){
		 	
		 //如果没联机上，就退出
		if($this->conn == false){
			$this->isEnabled = "0";
			return "1";
		}
		
		$sKey = $this->getUrlKeyByTeamId($sTeamId,'');
		if($sKey == ""){
			$sKey = $this->getUrlKeyByTeamId('',$sTeamId);
		}
		if($sKey == ""){
			$arrReturn = array("errcode"=>1,"errmsg"=>"Error","Num"=>0);
			return  urldecode(json_encode($arrReturn));		
		}
		
		//判断是否是手机号码
		$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([deleted] = 0) AND ".$sKey." order by [Jointdate] ";	
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果			
			$arrReturn = array("errcode"=>1,"errmsg"=>"Error","Num"=>0);
			return  urldecode(json_encode($arrReturn));
		
		}		
		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  
		if($sFrom == ""){
			$numFrom = 0;
		}else{
			$numFrom = (int)$sFrom;
		}
		
		if($sTo == ""){
			$numTo = 8;
		}else{
			$numTo = (int)$sTo;
		}
				
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setSQLItem($row);
				
				$strLine = array('WXID'=>$this->getWXID(),'UserName'=>$this->username,'Mobile'=>$this->mobile,'Email'=>$this->email);
				$arrLine[] = $strLine;
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		return  urldecode(json_encode($arrReturn));		
		
	 }

	//从HRIS里面，搜索批量用户，根据领导
	public function searchUserByManager($sManagerId,$sFrom='0',$sTo='100'){
		 			
		$sKey = " [ManagerEmployeeNo] = '".$sManagerId."'";		
		
		//判断是否是手机号码
		$strUrl = "SELECT * from [Zeiss_HRIS].[dbo].[BasicInfo] where (DateDiff(dd,[Jointdate],getdate())>-3) AND ([deleted] = 0) AND ".$sKey." order by [Jointdate] ";	
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果			
			$arrReturn = array("errcode"=>1,"errmsg"=>"Error","Num"=>0);
			return  urldecode(json_encode($arrReturn));
		
		}		
		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  
		if($sFrom == ""){
			$numFrom = 0;
		}else{
			$numFrom = (int)$sFrom;
		}
		
		if($sTo == ""){
			$numTo = 8;
		}else{
			$numTo = (int)$sTo;
		}
				
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setSQLItem($row);
				
				$sEmployeeId = $this->getWXID();
				
				$strLine = array('WXID'=>$sEmployeeId,'UserName'=>$this->username,'Mobile'=>$this->mobile,'Email'=>$this->email);
				$arrLine[] = $strLine;
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		return  urldecode(json_encode($arrReturn));		
		
	 }
	
	//根据编号，获得部门的SQL搜索条件	
	public function getUrlKeyByTeamId($teamId='',$teamName='No'){

		if($teamId==''&&$teamName=='No'){
			return '';
		}
		
		if($teamName=='CZS' ){
			return " ([Branch] = 'CZS' OR [Branch] = 'CZS,BO' OR [Branch] = 'CZS,CO' OR [Branch] = 'CZS,GO' )";
		}
		
		if($teamName=='CN COM' ){
			return " [Branch] = 'CN COM' ";
		}
		
		//Support
		$sCZS = " ([Branch] = 'CZS' OR [Branch] = 'CZS,BO' OR [Branch] = 'CZS,CO' OR [Branch] = 'CZS,GO' ) ";
		if($teamName=='Support' ){
			return $sCZS." AND [Divsion]='Support'";
		}
		
		if($teamId == "0101" || $teamName=='S-AC' || $teamName=='AC'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-AC' OR [Team]='AC')";
		}
		
		if($teamName=='Communication'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='Communication')";
		}
		
		if($teamId == "0102" || $teamName=='S-AD' || $teamName=='AD' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-AD' OR [Team]='AD') ";
		}
		
		if($teamId == "0103" || $teamName=='S-Controlling' ||$teamName=='Controlling' ){
			return $sCZS." AND [Divsion]='Support' AND [Team]='Controlling' ";
		}
		
		if($teamId == "0104" || $teamName=='S-HR' ||$teamName=='HR'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-HR' OR [Team]='HR') ";
		}
		
		if($teamId == "0105" || $teamName=='S-Learning'  ){
			return $sCZS." AND [Divsion]='Support' AND [Team]='S-HR Learning Center' ";
		}
		
		if($teamId == "0106" || $teamName=='S-IT' ||$teamName=='IT' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-IT' OR [Team]='IT') ";
		}
		
		if($teamId == "0107" || $teamName=='S-Legal' || $teamName=='Legal'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-Legal' OR [Team]='Legal')";
		}
		
		if($teamId == "0108" || $teamName=='S-LOG' || $teamName=='LOG'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-LOG' OR [Team]='LOG')";
		}
		
		if($teamId == "0109" || $teamName=='S-LQ' ){
			return $sCZS."  AND [Divsion]='Support' AND [Team]='S-LQ' ";
		}
		
		if($teamId == "0110" || $teamName=='S-MD' || $teamName=='MG'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-MD' OR [Team]='MG' ) ";
		}
		
		if($teamId == "0111" || $teamName=='S-Procurement' || $teamName=='Purchasing'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-Procurement' OR [Team]='Purchasing') ";
		}
		
		if($teamId == "0112" || $teamName=='S-Quality' || $teamName=='Quality'){
			return $sCZS." AND [Divsion]='Support' AND [Team]='Quality' ";
		}
				
		if($teamId == "0113" || $teamName=='S-RA' || $teamName=='RA&CA'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-RA' OR [Team]='RA&CA')";
		}
		
		//if($teamId == "0114" || $teamName=='Sourcing' ){
		//	return $sCZS." AND [Divsion]='Support' AND [Team]='Sourcing' ";
		//}
		
		if($teamId == "0115" || $teamName=='S-FM' || $teamName=='Facility'){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='S-FM' OR [Team]='Facility') ";
		}
		
		if($teamName=='Real Estate' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='Real Estate') ";
		}
		if($teamName=='SBD' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='SBD') ";
		}
		if($teamName=='IC' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='IC') ";
		}
		if($teamName=='SBI' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='SBI') ";
		}
		if($teamName=='Wipe' ){
			return $sCZS." AND [Divsion]='Support' AND ([Team]='Wipe') ";
		}
					
		if($teamId == "0199" || $teamName=='S-Other' ){
			return $sCZS." AND [Divsion]='Support' AND [Team] not in ('S-AC','S-AD','Controlling','S-HR','S-HR Learning Center','S-IT','S-Legal','S-LOG','S-LQ','S-MD','Quality','S-FM','S-Procurement','S-RA') ";
		}
		
		//MED
		if($teamName=='MED' ){
			return $sCZS." AND [Divsion]='MED'";
		}
		
		if($teamId == "0201" || $teamName=='MED MG' ){
			return $sCZS." AND [Divsion]='MED' AND [Team]='MG' ";
		}
		
		if($teamId == "0202" || $teamName=='MED SA' ){
			//return $sCZS." AND [Divsion]='MED' AND ([FunctionEmployee]='SA' OR [Team]='SA') ";
			return $sCZS." AND [Divsion]='MED' AND ([Team]='SA' OR [FunctionEmployee] = 'SA' ) ";
		}
		
		if($teamId == "0203" || $teamName=='MED SV' ){
			return $sCZS." AND [Divsion]='MED' AND [Team]='SV' ";
		}
		
		if($teamId == "0204" || $teamName=='MED AP' ){
			return $sCZS." AND [Divsion]='MED' AND [Team]='AP' ";
		}
		
		if($teamId == "0205" || $teamName=='MED MKT' ){
			return $sCZS." AND [Divsion]='MED' AND ([Team]='MKT&AP' OR [Team]='MKT') ";
		}
		
		if($teamId == "0206" || $teamName=='MED OPE' ){
			return $sCZS." AND [Divsion]='MED' AND [Team]='OPE' ";
		}
		
		if($teamId == "0207" || $teamName=='MED IORT' ){
			return $sCZS." AND [Divsion]='MED' AND [Team]='IORT' ";
		}
		
		//if($teamId == "0208" || $teamName=='MED BD' ){
		//	return $sCZS." AND [Divsion]='MED' AND [Team]='BD' ";
		//}
				
		if($teamId == "0299" || $teamName=='MED Other' ){
			return $sCZS." AND [Divsion]='MED' AND [Team] not in ('AP','BD','IORT','MG','MKT','MKT&AP','OPE','SA','SV') AND [FunctionEmployee] not in ('SA')";
		}
		
		//MIC
		if($teamName=='RMS' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS')";
		}
		
		if($teamId == "0301" || $teamName=='RMS MG' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='MG' ";
		}
		
		if($teamId == "0302" || $teamName=='RMS SA' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='SA' ";
		}
		
		if($teamId == "0303" || $teamName=='RMS SV' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='SV' ";
		}
		
		if($teamId == "0304" || $teamName=='RMS Product' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='Product' ";
		}
		
		if($teamId == "0305" || $teamName=='RMS MKT' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='MKT' ";
		}
		
		if($teamId == "0306" || $teamName=='RMS ODM' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='ODM' ";
		}
		
		//if($teamId == "0307" || $teamName=='RMS Training' ){
		//	return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='Training' ";
		//}
		
		//if($teamId == "0308" || $teamName=='RMS LAB' ){
		//	return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='LAB' ";
		//}
		
		if($teamId == "0309" || $teamName=='RMS AP' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team]='AP' ";
		}
				
		if($teamId == "0399" || $teamName=='RMS Other' ){
			return $sCZS." AND ([Divsion]='MIK' OR [Divsion]='RMS') AND [Team] not in ('MG','SA','SV','Product','MKT','ODM','AP') ";
		}
		
		//IMT
		if($teamName=='IQS' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS')";
		}
		if($teamId == "0401" || $teamName=='IQS MG' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='MG' ";
		}
		
		if($teamId == "0402" || $teamName=='IQS SA' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='SA' ";
		}
		
		if($teamId == "0403" || $teamName=='IQS SV' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='SV' ";
		}
		
		if($teamId == "0404" || $teamName=='IQS AP' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='AP' ";
		}
		
		if($teamId == "0405" || $teamName=='IQS MKT' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='MKT' ";
		}
		
		if($teamId == "0406" || $teamName=='IQS Aftersales' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='AfterSales' ";
		}
		
		if($teamId == "0407" || $teamName=='IQS CIC' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='CustomerCare' ";
		}
		
		if($teamId == "0408" || $teamName=='IQS Project' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='Customer Project' ";
		}
		
		if($teamId == "0409" || $teamName=='IQS CZAI' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='CZAI' ";
		}
		
		//if($teamId == "0410" || $teamName=='IQS PRO' ){
		//	return " [Branch] like 'CZS%' AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='PRO' ";
		//}
		
		//if($teamId == "0411" || $teamName=='' ){
		//	return " [Branch] like 'CZS%' AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team]='Software Business Development' ";
		//}			
				
		if($teamId == "0499" || $teamName=='IQS Other' ){
			return $sCZS." AND ([Divsion]='IMT' OR [Divsion]='IQS') AND [Team] not in ('MG','SA','SV','Product','MKT','ODM','Training','LAB') ";
		}
		
		//IMT Factory
		if($teamName=='IMTFactory' ){
			return " [Branch] like '%CZIMT%' ";
		}
		if($teamId == "0501" || $teamName=='0501' ){
			return " [Branch] like '%CZIMT%' AND [Team]='Support' ";
		}
		
		if($teamId == "0502" || $teamName=='0502' ){
			return " [Branch] like '%CZIMT%' AND [Team]='Production' ";
		}
		
		if($teamId == "0599" || $teamName=='0599' ){
			return " [Branch] like '%CZIMT%' AND [Team] not in ('Support','Production') ";
		}
		
		//R&D
		if($teamName=='R&D' ){
			return $sCZS." AND [Divsion]='R&D' ";
		}
		//COP
		if($teamName=='COP' ){
			return $sCZS." AND [Divsion]='COP'  ";
		}
		//SMS
		if($teamName=='SMS' ){
			return $sCZS." AND [Divsion]='SMS'  ";
		}
		//PCS
		if($teamName=='PCS' ){
			return $sCZS." AND [Divsion]='PCS'  ";
		}
		
		//SiC
		if($teamName=='SiC' ){
			return $sCZS." AND [Divsion]='SiC'  ";
		}
		
		//Spectroscopy
		if($teamName=='Spectroscopy' ){
			return $sCZS." AND [Divsion]='Spectroscopy'  ";
		}
		
		
		//CZSZ
		if($teamName=='Suzhou' ){
			return " [Branch] = 'CZSZ' ";
		}
		if($teamId == "0601" || $teamName=='0601' ){
			return " [Branch] = 'CZSZ' AND [Team]='GMO' ";
		}		
		if($teamId == "0602" || $teamName=='0602' ){
			return " [Branch] = 'CZSZ' AND [Team]='CSO' ";
		}
		if($teamId == "0603" || $teamName=='0603' ){
			return " [Branch] = 'CZSZ' AND [Team]='Product Sustaining and Quality' ";
		}
		if($teamId == "0604" || $teamName=='0604' ){
			return " [Branch] = 'CZSZ' AND [Team]='Production/Engineering' ";
		}
		if($teamId == "0605" || $teamName=='0605' ){
			return " [Branch] = 'CZSZ' AND [Team]='QSM' ";
		}
		if($teamId == "0606" || $teamName=='0606' ){
			return " [Branch] = 'CZSZ' AND [Team]='SCM' ";
		}		
		if($teamId == "0699" || $teamName=='0607' ){
			return " [Branch] = 'CZSZ' AND [Team] not in ('GMO','CSO','Product Sustaining and Quality','Production/Engineering','QSM','SCM') ";
		}
		
		//CZFE
		if($teamName=='Hongkong' ){
			return " [Branch] = 'CZFE' ";
		}
		
		if($teamId == "0701"  ){
			return " [Branch] = 'CZFE' AND [Team]='MG' ";
		}
		if($teamId == "0702"  ){
			return " [Branch] = 'CZFE' AND [Team]='S-AC' ";
		}
		if($teamId == "0703"  ){
			return " [Branch] = 'CZFE' AND [Team]='S-AD' ";
		}
		if($teamId == "0704"  ){
			return " [Branch] = 'CZFE' AND [Team]='S-IT' ";
		}
		if($teamId == "0705"  ){
			return " [Branch] = 'CZFE' AND [Team]='S-LQ' ";
		}
		if($teamId == "0706"  ){
			return " [Branch] = 'CZFE' AND [Team]='SA' ";
		}
		if($teamId == "0707"  ){
			return " [Branch] = 'CZFE' AND [Team]='SV' ";
		}
		if($teamId == "0708"  ){
			return " [Branch] = 'CZFE' AND [Team]='Vision' ";
		}
		if($teamId == "0799"  ){
			return " [Branch] = 'CZFE' AND [Team] not in ('MG','S-AC','S-AD','S-IT','S-LQ','SA','SV','Vision') ";
		}
		
	//CSZ
		if($teamId == "0801" || $teamName=='CZS,Shanghai' ){
			return " [Branch] = 'CZS' AND [Location]='Shanghai'  ";
		}
		if($teamId == "0802" || $teamName=='CZS,BO' ){
			return " [Branch] = 'CZS,BO' ";
		}
		if($teamId == "0803" || $teamName=='CZS,GO' ){
			return " [Branch] = 'CZS,GO' ";
		}
		if($teamId == "0804" || $teamName=='CZS,CO' ){
			return " [Branch] = 'CZS,CO' ";
		}
		if($teamId == "0805" || $teamName=='Home Office' ){
			return " [Branch] = 'CZS' AND [Location]!='Shanghai'  ";
		}
	//Taiwan
		if($teamName=='Taiwan' ){
			return " [Branch] = 'CZTW' ";
		}
		
		return "";
		
	}

	//根据特殊搜索条件，搜索批量用户，从HRIS里面，
	public function searchUserfromSQLByKey($sKey,$sText,$sFrom,$sTo){
		 	
		 //如果没联机上，就退出
		if($this->conn == false){
			$this->isEnabled = "0";
			$arrReturn = array("errcode"=>1,"errmsg"=>"Error conn","Num"=>0);
			return  urldecode(json_encode($arrReturn));
		}
		
		if($sKey == ''){
			$this->isEnabled = "0";
			$arrReturn = array("errcode"=>2,"errmsg"=>"Error Key:".$sKey,"Num"=>0);
			return  urldecode(json_encode($arrReturn));
		}
		
		//获得选择范围
		$numFrom = (int)$sFrom;
		if($sFrom == ""){
			$numFrom = 0;
		}		
		$numTo = (int)$sTo;
		if($sTo == ""){
			$numTo = 8;
		}
		
		//根据关键字，获得搜索代码
		$strUrl = '';		
		$strUrlStart = "SELECT top ".((string)$numTo)." * from [Zeiss_HRIS].[dbo].[BasicInfo] where ([Branch] != 'CN COM') AND ";
		$strStaff = " ([deleted] = 0) ";
		
		if($sKey == "Staff New All"){
			//搜索所有30天内的新员工
			$strUrl = $strUrlStart.$strStaff." AND DateDiff(dd,[Jointdate],getdate())>=-".$sText." AND DateDiff(dd,[Jointdate],getdate())<30 AND ([deleted] = 0) order by [Jointdate]";	
			
		}else if($sKey == "Staff New Employee"){
			//搜索所有30天内的新员工
			$strUrl = $strUrlStart.$strStaff." AND DateDiff(dd,[Jointdate],getdate())<=".$sText." AND DateDiff(dd,[Jointdate],getdate())>0 AND ([deleted] = 0)  order by [Jointdate]";	
			
		}else if($sKey == "Staff New Intern"){
			//搜索30天内入职的实习生
			$strUrl = $strUrlStart.$strStaff." AND ([StaffType] = 'Intern' ) AND DateDiff(dd,[Jointdate],getdate())<=".$sText." AND DateDiff(dd,[Jointdate],getdate())>0 AND ([deleted] = 0)  order by [Jointdate]";	
			
		}else if($sKey == "Staff New Not Intern"){
			//搜索30天内的正式员工
			$strUrl = $strUrlStart.$strStaff." AND (NOT [StaffType] = 'Intern' ) AND DateDiff(dd,[Jointdate],getdate())<=".$sText." AND DateDiff(dd,[Jointdate],getdate())>0 AND ([deleted] = 0)  order by [Jointdate]";	
			
		}else if($sKey == "Staff Old All"){
			//搜索30天内离职的所有人
			$strUrl = $strUrlStart." (not ".$strStaff.") AND DateDiff(dd,[Leavedate],getdate())<=".$sText." AND DateDiff(dd,[Leavedate],getdate())>0 order by [Leavedate]";	
			
		}else if($sKey == "Staff Old Intern"){	
			//搜索30天内离职的实习生
			$strUrl = $strUrlStart." ([StaffType] = 'Intern' ) And (not ".$strStaff.") AND DateDiff(dd,[Leavedate],getdate())<=".$sText." AND DateDiff(dd,[Leavedate],getdate())>0 order by [Leavedate]";
		}else if($sKey == "Staff Old Intern"){	
			//搜索30天内离职的实习生
			$strUrl = $strUrlStart." (NOT [StaffType] = 'Intern' ) And (not ".$strStaff.") AND DateDiff(dd,[Leavedate],getdate())<=".$sText." AND  DateDiff(dd,[Leavedate],getdate())>0 order by [Leavedate]";	
					
		}else if($sKey == "Staff By Manager"){		
			//搜索指定人员下的所有下属
			$strUrl = $strUrlStart.$strStaff." AND [ManagerEmployeeNo]='".$sText."' order by [EmployeeNo]";
		}else if($sKey == "Staff By Staff"){		
			//搜索指定人员下的所有同属，同一个领导
			$strUrl = $strUrlStart.$strStaff." AND [ManagerEmployeeNo] = (SELECT [ManagerEmployeeNo] from [Zeiss_HRIS].[dbo].[BasicInfo] where  ([deleted] = 0) AND [EmployeeNo]='".$sText."')  order by [EmployeeNo] ";
		}
		
		if($strUrl == ''){
			$arrReturn = array("errcode"=>3,"errmsg"=>"Error URL is empty:".$sKey,"Num"=>0);
			return  urldecode(json_encode($arrReturn));
		}
		//echo $strUrl;
		
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strUrl);
		
		if($query === false){ //查询不到结果
			//$this->isSaveSQL = "0";
			
			$strTemp = "读取SQL结果：".$strUrl;
			$this->myLog->errorLog($strTemp);
			
			$arrReturn = array("errcode"=>3,"errmsg"=>"Error SQL is Empty","Num"=>0);
			return  urldecode(json_encode($arrReturn));		
		}
		
		$strLine = "";
		$numLine = 0;
		$arrLine = array();
		
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
								
				//显示的数量
				$numShow++;
				
				$strTemp1 = iconv('GBK//IGNORE','utf-8', $row['EmployeeName_EN']);			
				$strTemp2 = iconv('GBK//IGNORE','utf-8', $row['EmployeeName_CN']);
				
				if($strTemp2 != '' && $strTemp2 != $strTemp1){
					$this->username = $strTemp1.'('.$strTemp2.')';
				}else{
					$this->username = $strTemp1;
				}
				//echo $this->username,'<br>';
				
				$this->mobile = str_replace("-","",$row ['Mobile']);
				$this->email = iconv('GBK//IGNORE','utf-8', $row ['Email']);
				
				$this->officeName = iconv('GBK//IGNORE','utf-8', $row ['Branch']);				
				$this->employeeID = $row ['EmployeeNo'];
				$this->hrID = ($row ['EmployeeId']);
				
				$this->hrJoinDate = $row ['JointDate'];
				
				$strLine = array('WXID'=>$this->getWXID(),'UserName'=>($this->username),'Mobile'=>$this->mobile,'Email'=>$this->email);
				$arrLine[] = $strLine;
				
			}			
		}	
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"Staff"=>$arrLine);
		//print_r($arrReturn);
		return  urldecode(json_encode($arrReturn));		
		
	 }
	 	
		

		
		

	//创建新用户，在微信后台
	public function weixinUserAdd($wxid){
		//根据用户的WXID，获得用户的资料
		if($wxid == ""){
			//如果参数为空， 则默认为当前的ID
			$wxid = $this->WXID;
		}
		if($wxid == ""){
			$this->wxStatus = 4;
			return 'WXID is empaty';
		}
		
		if(strlen($this->mobile) != 11 && $this->email ==''){
			$this->wxStatus = 4;
			return 'Mobile and Email is empaty';
		}
				
		$sJson = '{"userid":"'.$wxid.'","name":"'.$this->username.'","department":"'.$this->getWXDepartmentID().'","mobile":"'.$this->mobile.'","email":"'.$this->email.'"}';
		if(strlen($this->mobile) != 11 ){
			
			if($this->officeName == 'CZFE' && $this->mobile != ''){
				$sMobile = ',"mobile":"+852'.$this->mobile.'"';
			}else if($this->officeName == 'CZTW' && $this->mobile != ''){
				$sMobile = ',"mobile":"+886'.$this->mobile.'"';
			}else{
				$sMobile = '';
			}			
			//$sMobile = '';
			$sJson = '{"userid":"'.$wxid.'","name":"'.$this->username.'","department":"'.$this->getWXDepartmentID().$sMobile.'","email":"'.$this->email.'"}';
		}
		
		$strUrl = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=".$this->myToken;
		$result = json_decode($this->myTools->https_post_json($strUrl, $sJson)); 
				
		//如果结果为错，则退出
		if($result->errcode != 0){
			$this->wxStatus = 4;			
			return $result->errmsg;
		}		
		return 'OK' ;
	} 
	 
	//删除用户，在微信后台
	public function weixinUserDel($wxid){
		//根据用户的WXID，获得用户的资料
		if($wxid == ""){
			//如果参数为空， 则默认为当前的ID
			$wxid = $this->WXID;
		}
		if($wxid == ""){
			$this->wxStatus = 4;
			return '1';
		}
			
		$strUrl = 'https://qyapi.weixin.qq.com/cgi-bin/user/delete?access_token='.$this->myToken.'&userid='.$wxid;
		$result = json_decode($this->myTools->https_get($strUrl)); 
				
		//如果结果为错，则退出
		if($result->errcode != 0){
			$this->wxStatus = 4;
			return $result->errmsg;
		}		
		return 'OK' ;
	} 
	  
	//更新用户，在微信后台
	public function weixinUserUpdate($wxid){
		//根据用户的WXID，获得用户的资料
		
		if($wxid == ""){
			$this->wxStatus = 4;
			return 'WXID is empty';
		}		
		$sJson = '{"userid":"'.$wxid.'","name":"'.$this->username.'","department":"'.$this->getWXDepartmentID().'","mobile":"'.$this->mobile.'"}';
		
		if(strlen($this->mobile) != 11 ){
			$sJson = '{"userid":"'.$wxid.'","name":"'.$this->username.'","department":"'.$this->getWXDepartmentID().'","email":"'.$this->email.'"}';
		}
		
		$strUrl = "https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token=".$this->myToken;
		$result = json_decode($this->myTools->https_post_json($strUrl,$sJson)); 
				
		//如果结果为错，则退出
		if($result->errcode != 0){
			$this->wxStatus = 4;
			return $result->errmsg;
		}		
		return 'OK' ;
	} 
	
	//在微信后台，增加用户角色，
	public function weixinRolesAdd($roleName,$arrWXID){
				
		$roleId = 1;	
		if($roleName=='CZS,BO'){
			$roleId = 3;
		}else if($roleName=='CZS,GO'){
			$roleId = 2;
		}else if($roleName=='CZS,CO'){
			$roleId = 4;
		}else if($roleName=='Guangzhou'){
			$roleId = 11;
		}else if($roleName=='Beijing'){
			$roleId = 12;
		}else if($roleName=='Chengdu'){
			$roleId = 13;
		}else if($roleName=='Shanghai'){
			$roleId = 14;
		}else if($roleName=='HomeOffice'){
			$roleId = 21;
		}else{
			$roleId = (int)$roleName;
		}
		
		$arrTemp = array('tagid'=>$roleId,'userlist'=>$arrWXID,'partylist'=>'');
		$sJson = json_encode($arrTemp);
		//echo $sJson,'<br>';
		
		$strUrl = "https://qyapi.weixin.qq.com/cgi-bin/tag/addtagusers?access_token=".$this->myToken;
		//echo $strUrl,'<br>';
		return $this->myTools->https_post_json($strUrl, $sJson); 
	} 
	 

	//转换成格式
	private function getTelDirect($source){
		
		
		if(strlen($source) < 7){
			return '';
		}
		
		//大陆手机号
		if(substr($source,0,1) == '1' && strlen($source) > 8){
			return '';
		}
		
		//香港手机号
		if(substr($source,0,1) == '9'){
			return $source;
		}
		
		//取最后8位
		return substr($source,-8);
		
		
			/*	
		$source = str_replace(' ','',$source);
		$source = str_replace(' ','',$source);
		
		if(substr($source,0,3) == '886'){
			return trim(substr($source,3));
		}
		if(substr($source,0,4) == '8621'){
			return trim(substr($source,4));
		}
		if(substr($source,0,5) == '+8621'){
			return trim(substr($source,5));
		}
		if(substr($source,0,6) == '+86021'){
			return trim(substr($source,6));
		}
		
		return $source;	
		*/		
	}

	//客服人员拉黑删除粉丝
	public function staffDelUser($token,$userid){
		
		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token='.$token;
		$data = '{ "openid_list" : ["'.$wxid.'"] }';		
		return $this->myTools->httpsPost($url, $data);		
	}
	
}

