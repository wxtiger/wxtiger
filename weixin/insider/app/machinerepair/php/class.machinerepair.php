<?php
if (!defined('ROOTWeixin')) define("ROOTWeixin", $_SERVER['DOCUMENT_ROOT']);
include_once ROOTWeixin.'/weixin/system/class/base.insider.php';

/**
* 功能描述: 微信相关类的定义
*
http://localhost/weixin/insider/app/machinerepair/php/class.machinerepair.php

DROP TABLE MachineInfo ;
DROP TABLE MachineRepair ;

【MachineInfo】机器设备数据库
CREATE TABLE MachineInfo(
    ID INT NOT NULL PRIMARY Key AUTO_INCREMENT,
    MachineID VARCHAR(50) NOT NULL,		机器编号
    MachineName VARCHAR(200) ,			机器名称
    MachineType VARCHAR(100) ,			机器型号，打印机传真机等，
    MachineLocation VARCHAR(100) ,		所在位置
    MachineOwner VARCHAR(100) ,			所属人名
	
	MachineUserID VARCHAR(50) ,			所属人账号
	MachineDepartment VARCHAR(100) ,	所属部门
	MachineClass VARCHAR(50) ,			等级大类，公用设备，管理层设备，软件设备，不同类别不同负责人
	BuyDate DateTime,					购买时间
	UsedDate DateTime,					领用时间
	MachineSupplier VARCHAR(100) ,		供应商，品牌商，
	MachineFileList LongText,			图片，附件等资料链接
	
    MachineStatus VARCHAR(50) ,			状态，Idle，Using，Scrap，
    MachineComment LongText,			备注说明
    WarrantyDate DateTime,				维保时间
    CreatedDate DateTime,
    UpdatedDate DateTime
) DEFAULT charset=utf8mb4 COLLATE utf8mb4_unicode_ci;

	
	
	
	
ID
MachineID
MachineName
MachineType
MachineLocation
MachineOwner
MachineUserID
MachineDepartment
MachineClass
BuyDate
UsedDate
MachineSupplier
MachineFileList	
MachineStatus
MachineComment
WarrantyDate
CreatedDate
UpdatedDate

【MachineRepair】 报修记录数据库
CREATE TABLE MachineRepair(
    ID INT NOT NULL PRIMARY  Key  AUTO_INCREMENT,
    RepairID VARCHAR(50) NOT NULL,
    MachineID VARCHAR(50) NOT NULL,
    MachineName VARCHAR(200) ,
    RepairStatus  VARCHAR(50) ,
    RepairFileList LongText,

    SubmitDate DateTime,
    SubmitName  VARCHAR(200) ,
    SubmitUserID  VARCHAR(50) ,
    CreatedName  VARCHAR(200) ,
    CreatedUserID  VARCHAR(50) ,
    SubmitComment LongText,
    
    CheckDate DateTime,
    CheckName  VARCHAR(200) ,
    CheckUserID  VARCHAR(50) ,
    CheckComment LongText,
    CheckResult  VARCHAR(50) ,

    SolvedDate DateTime,
    SolvedName  VARCHAR(200) ,
    SolvedUserID  VARCHAR(50) ,
    SolvedComment LongText,
    SolvedResult  VARCHAR(50) ,

    VerifyDate DateTime,
    VerifyName  VARCHAR(200) ,
    VerifyUserID  VARCHAR(50) ,
    VerifyComment LongText,
    VerifyResult  VARCHAR(50) ,
	
	OtherComment LongText,
	WorkflowLog LongText,
    CreatedDate DateTime,
    UpdatedDate DateTime
) DEFAULT charset=utf8mb4 COLLATE utf8mb4_unicode_ci;
	

ID
RepairID
MachineID
MachineName
RepairStatus
RepairFileList

SubmitDate
SubmitName
SubmitUserID
CreatedName
CreatedUserID
SubmitComment

CheckDate
CheckName
CheckUserID
CheckComment
CheckResult

SolvedDate
SolvedName
SolvedUserID
SolvedComment
SolvedResult

VerifyDate
VerifyName
VerifyUserID
VerifyComment
VerifyResult

OtherComment
WorkflowLog
CreatedDate
UpdatedDate

*/

/*


$machineRepair = new MachineRepair();
$machineRepair->mID = '';
$machineRepair->mRepairID = '1234';
$machineRepair->mMachineID = '1234';
$machineRepair->mMachineName = '';
$machineRepair->mRepairStatus = '';
$machineRepair->mRepairFileList = '';

$machineRepair->mSubmitDate = '1900-01-01';
$machineRepair->mSubmitName = '';
$machineRepair->mSubmitUserID = '';
$machineRepair->mCreatedName = '';
$machineRepair->mCreatedUserID = '';
$machineRepair->mSubmitComment = '';

$machineRepair->mCheckDate = '1900-01-01';
$machineRepair->mCheckName = '';
$machineRepair->mCheckUserID = '';
$machineRepair->mCheckComment = '';
$machineRepair->mCheckResult = '';

$machineRepair->mSolvedDate = '1900-01-01';
$machineRepair->mSolvedName = '';
$machineRepair->mSolvedUserID = '';
$machineRepair->mSolvedComment = '';
$machineRepair->mSolvedResult = '';

$machineRepair->mVerifyDate = '1900-01-01';
$machineRepair->mVerifyName = '';
$machineRepair->mVerifyUserID = '';
$machineRepair->mVerifyComment = '';
$machineRepair->mVerifyResult = '';

$machineRepair->mOtherComment = '';
$machineRepair->mWorkflowLog = '';
$machineRepair->mCreatedDate = '';
$machineRepair->mUpdatedDate = '';

$result = $machineRepair->insertRepair();
echo $result ;


//新建数据
$machineRepair->mMachineID = '1234';
$machineRepair->mMachineName = '打印机';
$machineRepair->mMachineType = '打印机';
$machineRepair->mMachineLocation = '二楼';
$machineRepair->mMachineOwner = '杨虎';
$machineRepair->mMachineStatus = '可用';
$machineRepair->mMachineComment = '测试';
$machineRepair->mWarrantyDate = '2021-07-27';
$machineRepair->mCreatedDate = '';
$machineRepair->mUpdatedDate = '';
//$result = $machineRepair->insertMachine();
//echo $result ;

//更新数据
$machineRepair->mMachineName = '惠普打印机';
$result = $machineRepair->updateMachineByRecordID(1);
echo $result,'   11<br>' ;

//查看数据
$result = $machineRepair->readMachineByID(1);
echo $result,'  22<br>' ;
$obj = json_decode($result);

echo $obj->Line[0]->MachineName,'<br>' ;
return;
*/

//$machineRepair = new MachineRepair();
//$result = $machineRepair->getConfigValue('CheckerUserID');
//echo $result ;
//return;


//机器类
class MachineRepair{
	
	//表字段
	public $mID = '';
	public $mOfficial = '';
	public $mCreatedDate = '1900-01-01';
	public $mUpdatedDate = '1900-01-01';
	
	public $mMachineID = '';
	public $mMachineName = '';
    public $mMachineType = '';
    public $mMachineLocation = '';
    public $mMachineOwner = '';
    public $mMachineUserID = '';
    public $mMachineDepartment = '';
    public $mMachineClass = '';
    public $mBuyDate = '1900-01-01';
    public $mUsedDate = '1900-01-01';
    public $mMachineSupplier = '';
    public $mMachineFileList = '';
    public $mMachineStatus = '';
    public $mMachineComment = '';
    public $mWarrantyDate = '1900-01-01';
	
    public $mRepairID = '';
    public $mRepairStatus = '';
    public $mRepairFileList = '';

    public $mSubmitDate = '1900-01-01';
    public $mSubmitName = '';
    public $mSubmitUserID = '';
    public $mCreatedName = '';
    public $mCreatedUserID = '';
    public $mSubmitComment = '';

    public $mCheckDate = '1900-01-01';
    public $mCheckName = '';
    public $mCheckUserID = '';
    public $mCheckComment = '';
    public $mCheckResult = '';

    public $mSolvedDate = '1900-01-01';
    public $mSolvedName = '';
    public $mSolvedUserID = '';
    public $mSolvedComment = '';
    public $mSolvedResult = '';

    public $mVerifyDate = '1900-01-01';
    public $mVerifyName = '';
    public $mVerifyUserID = '';
    public $mVerifyComment = '';
    public $mVerifyResult = '';

    public $mOtherComment = '';
    public $mWorkflowLog = '';

	
	private $myLog;
	private $myTools;
	private $myApp;
	private $conn;
	private $fileConfig = 'config.json';
	private $fileWorkflow = 'workflow/.txt';
	
	//初始化
	public function __construct($sRepairID='') {
		//构造函数， 创建时，
		$wxBase = new WXInsider();	
		$this->myLog = $wxBase->getClass('WXLog');
		$this->myTools = $wxBase->getClass('WXTools');
		$this->myApp = $wxBase->getClass('WXApp');
		$this->conn = $wxBase->getConn();
		
		$this->fileConfig = 'config.json';
		
		$this->mRepairID = $sRepairID;
		
		
	}

//【数据库记录处理函数】

	//插入新纪录
	public function insertMachine(){
	
		//否则就是插入新记录
		$strSQL = "INSERT INTO MachineInfo
           (MachineID
			,MachineName
			,MachineType
			,MachineLocation
			,MachineOwner
			,MachineUserID
			,MachineDepartment
			,MachineClass
			,BuyDate
			,UsedDate
			,MachineSupplier
			,MachineFileList
			,MachineStatus
			,MachineComment
			,WarrantyDate
		  ,CreatedDate
		  ,UpdatedDate)	
		VALUES
           ('".$this->mMachineID."'
			,'".$this->mMachineName."'
			,'".$this->mMachineType."'
			,'".$this->mMachineLocation."'
			,'".$this->mMachineOwner."'
			,'".$this->mMachineUserID."'
			,'".$this->mMachineDepartment."'
			,'".$this->mMachineClass."'
			,'".$this->mBuyDate."'
			,'".$this->mUsedDate."'
			,'".$this->mMachineSupplier."'
			,'".$this->mMachineFileList."'
			,'".$this->mMachineStatus."'
			,'".$this->mMachineComment."'
			,'".$this->mWarrantyDate."'	
			,now()
			,now())";
		   
		//echo $strSQL,'<br>';
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);			
			return "Error";
		}
		return "OK";
	}

	public function insertRepair(){
		
		$this->mRepairID = $this->getRapairID();
		//否则就是插入新记录
		$strSQL = "INSERT INTO MachineRepair
           (RepairID
			,MachineID
			,MachineName
			,RepairStatus
			,RepairFileList
			,SubmitDate
			,SubmitName
			,SubmitUserID
			,CreatedName
			,CreatedUserID
			,SubmitComment
			,CheckDate
			,CheckName
			,CheckUserID
			,CheckComment
			,CheckResult
			,SolvedDate
			,SolvedName
			,SolvedUserID
			,SolvedComment
			,SolvedResult
			,VerifyDate
			,VerifyName
			,VerifyUserID
			,VerifyComment
			,VerifyResult
			,OtherComment
			,WorkflowLog
			,CreatedDate
			,UpdatedDate)	
		VALUES
           ('".$this->mRepairID."'
			,'".$this->mMachineID."'
			,'".$this->mMachineName."'
			,'".$this->mRepairStatus."'
			,'".$this->mRepairFileList."'
			,'".$this->mSubmitDate."'
			,'".$this->mSubmitName."'
			,'".$this->mSubmitUserID."'
			,'".$this->mCreatedName."'
			,'".$this->mCreatedUserID."'
			,'".$this->mSubmitComment."'
			,'".$this->mCheckDate."'
			,'".$this->mCheckName."'
			,'".$this->mCheckUserID."'
			,'".$this->mCheckComment."'
			,'".$this->mCheckResult."'
			,'".$this->mSolvedDate."'
			,'".$this->mSolvedName."'
			,'".$this->mSolvedUserID."'
			,'".$this->mSolvedComment."'
			,'".$this->mSolvedResult."'
			,'".$this->mVerifyDate."'
			,'".$this->mVerifyName."'
			,'".$this->mVerifyUserID."'
			,'".$this->mVerifyComment."'
			,'".$this->mVerifyResult."'
			,'".$this->mOtherComment."'
			,'".$this->mWorkflowLog."'
			,now()
			,now())";
		   
		//echo $strSQL,'<br>';
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = 'Error SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);			
			return "Error";
		}
		return "OK";
	}


	//读取设备数据
	private function setMachineField($row){		
		$this->mID = $row['ID'];
		$this->mMachineID = $row['MachineID'];
		$this->mMachineName = $row['MachineName'];
		$this->mMachineType = $row['MachineType'];
		$this->mMachineLocation = $row['MachineLocation'];
		$this->mMachineOwner = $row['MachineOwner'];
		$this->mMachineUserID = $row['MachineUserID'];
		$this->mMachineDepartment = $row['MachineDepartment'];
		$this->mMachineClass = $row['MachineClass'];
		$this->mBuyDate = $row['BuyDate'];
		if(is_null($this->mBuyDate)){
			$this->mBuyDate = $row['CreatedDate'];
		}
		$this->mBuyDate = substr($this->mBuyDate,0,10);
		
		$this->mUsedDate = $row['UsedDate'];
		if(!is_null($this->mUsedDate)){
			$this->mUsedDate = substr($this->mUsedDate,0,10);
		}
		
		$this->mMachineSupplier = $row['MachineSupplier'];
		$this->mMachineFileList = $row['MachineFileList'];		
		$this->mMachineStatus = $row['MachineStatus'];
		$this->mMachineComment = $row['MachineComment'];
		$this->mWarrantyDate = $row['WarrantyDate'];		
		if(!is_null($this->mWarrantyDate)){
			$this->mWarrantyDate = substr($this->mWarrantyDate,0,10);
		}
		
		$this->mCreatedDate = $row['CreatedDate'];
		$this->mUpdatedDate = $row['UpdatedDate'];
		
		$arrLine = array();
			$arrLine['RecordID'] = $this->mID;
			$arrLine['MachineID'] = $this->mMachineID;			
			$arrLine['MachineName'] = $this->mMachineName;
			$arrLine['MachineType'] = $this->mMachineType;
			$arrLine['MachineLocation'] = $this->mMachineLocation;
			$arrLine['MachineOwner'] = $this->mMachineOwner;
			$arrLine['MachineUserID'] = $this->mMachineUserID;
			$arrLine['MachineDepartment'] = $this->mMachineDepartment;
			$arrLine['MachineClass'] = $this->mMachineClass;
			$arrLine['BuyDate'] = $this->mBuyDate;
			$arrLine['UsedDate'] = $this->mUsedDate;
			$arrLine['MachineSupplier'] = $this->mMachineSupplier;
			$arrLine['MachineFileList'] = $this->mMachineFileList;
			$arrLine['MachineStatus'] = $this->mMachineStatus;
			$arrLine['MachineStatusText'] = $this->getMachineStatusText($this->mMachineStatus);
			$arrLine['MachineComment'] = $this->mMachineComment;
			$arrLine['WarrantyDate'] = $this->mWarrantyDate;
			$arrLine['CreatedDate'] = $this->mCreatedDate;
			$arrLine['UpdatedDate'] = $this->mUpdatedDate;
		return 	$arrLine;
		
	}
	public function readMachineByID($sID){
		
		if($sID == ''){
			return '{"errcode":1,"errmsg":"ID is empty."}';
		}
		
		$strSQL = "select * from MachineInfo where ID=".$sID;		
		$query = $this->conn->query($strSQL);
		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strURL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error"}';
		}
		
		$arrReturn = array();
		$arrLines = array();
		$num = 0;
		//赋值
		while ($row = $query->fetch_assoc()) {
			$num++;
			$arrLines[] = $this->setMachineField($row);
		}	
		
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['Num'] = $num;
		$arrReturn['Line'] = $arrLines;
		
		return json_encode($arrReturn);	
		
	}
	public function readMachineByMachineID($sMachineID){
		
		if($sMachineID == ''){
			return '{"errcode":1,"errmsg":"Machine ID is empty."}';
		}
		
		$strSQL = "select * from MachineInfo where MachineStatus!='Delete' AND MachineID='".$sMachineID."'";		
		$query = $this->conn->query($strSQL);
		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strURL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error"}';
		}
		
		$arrReturn = array();
		$arrLines = array();
		$num = 0;
		//赋值
		while ($row = $query->fetch_assoc()) {
			$num++;
			$arrLines[] = $this->setMachineField($row);
		}	
		
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['Num'] = $num;
		$arrReturn['Line'] = $arrLines;
		
		return json_encode($arrReturn);	
		
	}
	
	//读取报修数据
	private function setRepairField($row){		
		$this->mID = $row['ID'];
		$this->mMachineID = $row['MachineID'];
		$this->mMachineName = $row['MachineName'];
		$this->mRepairID = $row['RepairID'];
		$this->mRepairStatus = $row['RepairStatus'];
		$this->mRepairFileList = $row['RepairFileList'];
		$this->mSubmitDate = $row['SubmitDate'];
		$this->mSubmitName = $row['SubmitName'];
		$this->mSubmitUserID = $row['SubmitUserID'];
		$this->mCreatedName = $row['CreatedName'];
		$this->mCreatedUserID = $row['CreatedUserID'];
		$this->mSubmitComment = $row['SubmitComment'];
		$this->mCheckDate = $row['CheckDate'];
		$this->mCheckName = $row['CheckName'];
		$this->mCheckUserID = $row['CheckUserID'];
		$this->mCheckComment = $row['CheckComment'];
		$this->mCheckResult = $row['CheckResult'];
		$this->mSolvedDate = $row['SolvedDate'];
		$this->mSolvedName = $row['SolvedName'];
		$this->mSolvedUserID = $row['SolvedUserID'];
		$this->mSolvedComment = $row['SolvedComment'];
		$this->mSolvedResult = $row['SolvedResult'];
		$this->mVerifyDate = $row['VerifyDate'];
		$this->mVerifyName = $row['VerifyName'];
		$this->mVerifyUserID = $row['VerifyUserID'];
		$this->mVerifyComment = $row['VerifyComment'];
		$this->mVerifyResult = $row['VerifyResult'];
		$this->mOtherComment = $row['OtherComment'];
		$this->mWorkflowLog = $row['WorkflowLog'];		
		$this->mCreatedDate = $row['CreatedDate'];
		$this->mUpdatedDate = $row['UpdatedDate'];
		
		$arrLine = array();
			$arrLine['RecordID'] = $this->mID;
			$arrLine['MachineID'] = $this->mMachineID;			
			$arrLine['MachineName'] = $this->mMachineName;
			$arrLine['RepairID'] = $this->mRepairID;
			$arrLine['CreatedDate'] = $this->mCreatedDate;
			$arrLine['UpdatedDate'] = $this->mUpdatedDate;
			$arrLine['RepairStatus'] = $this->mRepairStatus;
			$arrLine['RepairStatusText'] = $this->getStatusText($this->mRepairStatus);
			$arrLine['RepairFileList'] = $this->mRepairFileList;
			$arrLine['SubmitDate'] = $this->mSubmitDate;
			$arrLine['SubmitName'] = $this->mSubmitName;
			$arrLine['SubmitUserID'] = $this->mSubmitUserID;
			$arrLine['CreatedName'] = $this->mCreatedName;
			$arrLine['CreatedUserID'] = $this->mCreatedUserID;
			$arrLine['SubmitComment'] = $this->mSubmitComment;
			$arrLine['CheckDate'] = $this->mCheckDate;
			$arrLine['CheckName'] = $this->mCheckName;
			$arrLine['CheckUserID'] = $this->mCheckUserID;
			$arrLine['CheckComment'] = $this->mCheckComment;
			$arrLine['CheckResult'] = $this->mCheckResult;
			$arrLine['SolvedDate'] = $this->mSolvedDate;
			$arrLine['SolvedName'] = $this->mSolvedName;
			$arrLine['SolvedUserID'] = $this->mSolvedUserID;
			$arrLine['SolvedComment'] = $this->mSolvedComment;
			$arrLine['SolvedResult'] = $this->mSolvedResult;
			$arrLine['VerifyDate'] = $this->mVerifyDate;
			$arrLine['VerifyName'] = $this->mVerifyName;
			$arrLine['VerifyUserID'] = $this->mVerifyUserID;
			$arrLine['VerifyComment'] = $this->mVerifyComment;
			$arrLine['VerifyResult'] = $this->mVerifyResult;
			$arrLine['OtherComment'] = $this->mOtherComment;
			$arrLine['WorkflowLog'] = $this->mWorkflowLog;
		return $arrLine;

	}
	public function readRepairByRepairID($sRepairID){
		
		if($sRepairID == ''){
			return '{"errcode":1,"errmsg":"Repair ID is empty."}';
		}
		
		$strSQL = "select * from MachineRepair where RepairID='".$sRepairID."'";		
		$query = $this->conn->query($strSQL);
		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strURL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error"}';
		}
		
		$arrReturn = array();
		$arrLines = array();
		$num = 0;
		//赋值
		while ($row = $query->fetch_assoc()) {
			$num++;
			$arrLines[] = $this->setRepairField($row);
		}	
		
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['Num'] = $num;
		$arrReturn['Line'] = $arrLines;
		
		return json_encode($arrReturn);	
	}
	
	
	
	
	//更新设备记录
	private function getMachineUpdate(){
		$strSQL = "UPDATE MachineInfo
		   SET MachineID = '".$this->mMachineID."'
			   ,MachineName = '".$this->mMachineName."'
			   ,MachineType = '".$this->mMachineType."'
			   ,MachineLocation = '".$this->mMachineLocation."'
			   ,MachineOwner = '".$this->mMachineOwner."'
			   ,MachineUserID = '".$this->mMachineUserID."'
			   ,MachineDepartment = '".$this->mMachineDepartment."'
			   ,MachineClass = '".$this->mMachineClass."'
			   ,BuyDate = '".$this->mBuyDate."'
			   ,UsedDate = '".$this->mUsedDate."'
			   ,MachineSupplier = '".$this->mMachineSupplier."'
			   ,MachineFileList = '".$this->mMachineFileList."'			   
			   ,MachineStatus = '".$this->mMachineStatus."'
			   ,MachineComment = '".$this->mMachineComment."'
			   ,WarrantyDate = '".$this->mWarrantyDate."'
			  ,UpdatedDate = now() ";
		return $strSQL;
	}
	public function updateMachineByRecordID($sRecordId){
			
		//否则就是插入新记录
		$strSQL = $this->getMachineUpdate()." WHERE ID = '".$sRecordId."'";
		
		$query = $this->conn->query($strSQL);

		if($query == false){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);
			
			return "Error";
		}else{
			return "OK";
		}
	}
	public function updateMachineByMachineID($sMachineId){
			
		//否则就是插入新记录
		$strSQL = $this->getMachineUpdate()." WHERE MachineID = '".$sMachineId."'";
		
		$query = $this->conn->query($strSQL);
		if($query == false){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);
			
			return "Error";
		}else{
			return "OK";
		}
		
	}

	private function getRepairUpdate(){
		$strSQL = "UPDATE MachineRepair
		   SET MachineID = '".$this->mMachineID."'
			   ,MachineName = '".$this->mMachineName."'
			   ,RepairStatus = '".$this->mRepairStatus."'
			   ,RepairFileList = '".$this->mRepairFileList."'
			   ,SubmitDate = '".$this->mSubmitDate."'
			   ,SubmitName = '".$this->mSubmitName."'
			   ,SubmitUserID = '".$this->mSubmitUserID."'
			   ,CreatedName = '".$this->mCreatedName."'
			   ,CreatedUserID = '".$this->mCreatedUserID."'
			   ,SubmitComment = '".$this->mSubmitComment."'
			   ,CheckDate = '".$this->mCheckDate."'
			   ,CheckName = '".$this->mCheckName."'
			   ,CheckUserID = '".$this->mCheckUserID."'
			   ,CheckComment = '".$this->mCheckComment."'
			   ,CheckResult = '".$this->mCheckResult."'			   
			   ,SolvedDate = '".$this->mSolvedDate."'
			   ,SolvedName = '".$this->mSolvedName."'
			   ,SolvedUserID = '".$this->mSolvedUserID."'
			   ,SolvedComment = '".$this->mSolvedComment."'
			   ,SolvedResult = '".$this->mSolvedResult."'
			   ,VerifyDate = '".$this->mVerifyDate."'
			   ,VerifyName = '".$this->mVerifyName."'
			   ,VerifyUserID = '".$this->mVerifyUserID."'
			   ,VerifyComment = '".$this->mVerifyComment."'
			   ,VerifyResult = '".$this->mVerifyResult."'
			   ,OtherComment = '".$this->mOtherComment."'
			   ,WorkflowLog = '".$this->mWorkflowLog."'
			  ,UpdatedDate = now() ";
		return $strSQL;
	}
	public function updateRepairByRepairID($sRepairId){
			
		//否则就是插入新记录
		$strSQL = $this->getRepairUpdate()." WHERE RepairID = '".$sRepairId."'";		
		$query = $this->conn->query($strSQL);
		if($query == false){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);
			
			return "Error";
		}else{
			return "OK";
		}
		
	}


//【相关功能函数】

	//获得报修流水号
	private function getRapairID(){
		
		$key = date("Ym");
		$num = 0;
		$obj = json_decode('{}');
		if(is_file($this->fileConfig)){
			
			$obj = json_decode(file_get_contents($this->fileConfig));
			if(property_exists($obj,'NumberKey') && property_exists($obj,'NumberValue')) {
				if($key == $obj->NumberKey){
					$num = ((int)$obj->NumberValue);
				}else{
					$obj->NumberKey = $key;
				}
			}			
		}
		
		$num = $num + 1;
		$obj->NumberValue = (string)$num;
		$obj->NumberKey = $key;
		file_put_contents($this->fileConfig,json_encode($obj));
		
		$num = $num + 10000;
		$sTemp = substr((string)$num,1);
		return $key.$sTemp;
		
	}
	
	//保存工作流记录
	public function doSaveWorkflow($sFrom,$type,$sTo='',$comment=''){
		if($this->mRepairID == ''){
			return '{"errcode":1,"errmsg":"Repair ID is empty."}';
		}
		$this->fileWorkflow = 'workflow/'.$this->mRepairID.'.txt';
		
		$arrTemp = array();
		$arrTemp['DateTime'] = date("Y-m-d H:i:s");
		$arrTemp['Status'] = $this->mRepairStatus;
		$arrTemp['UserFrom'] = $sFrom;
		$arrTemp['Type'] = $type;	//Submit, Cancel,Approved,Finish,Solved,Return,Completed
		$arrTemp['UserTo'] = $sTo;
		$arrTemp['Comment'] = $comment;
		
		file_put_contents($this->fileWorkflow,json_encode($arrTemp)."\r\n", FILE_APPEND);
		return '{"errcode":0,"errmsg":"OK"}';
		
	}
	
	//获得状态描述
	function getStatusText($sStatus=''){
		if($sStatus==''||$sStatus=='10'){
			return '草拟中';
		}else if($sStatus=='20'){
			return '待审核';
		}else if($sStatus=='30'){
			return '待确定';
		}else if($sStatus=='40'){
			return '处理中';
		}else if($sStatus=='50'){
			return '待校验';
		}else if($sStatus=='60'){
			return '直接关闭';
		}else if($sStatus=='100'){
			return '全部完成';
		}else if($sStatus=='90'){
			return '取消删除';
		}else{
			return $sStatus;
		}
		
	}
	
	function getMachineStatusText($sStatus=''){
		//Idle，Using，Scrap
		if($sStatus==''||$sStatus=='Idle'){
			return '闲置';
		}else if($sStatus=='Using'){
			return '在用';
		}else if($sStatus=='Scrap'){
			return '报废';		
		}else{
			return $sStatus;
		}
		
	}
	
	//获得应用参数
	public function getConfigValue($sKey){
		
		$obj = json_decode('{}');
		if(!is_file($this->fileConfig)){
			return '';
		}
			
		$obj = json_decode(file_get_contents($this->fileConfig));
		if(!property_exists($obj,$sKey)) {
			return '';
		}
		//echo $sKey;
		return $obj->$sKey;
			
	}
	
	//提交代码初始化
	public function doSubmitInit($strSubmit){
		//获得参数	
		//$strSubmit = urldecode(file_get_contents("php://input"));	
		if($strSubmit ==''){
			return '{"errcode":1,"errmsg":"Can not find data"}';
		}
		
		$sToday = date("Y");
		file_put_contents('ticket/submit_'.$sToday.'.csv', date("Y-m-d H:i:s").' '.$strSubmit."\r\n", FILE_APPEND); 	
		$objSubmit = json_decode($strSubmit);	
		$sCurWXID = '';
		if(property_exists($objSubmit, 'CurWXID')){
			$sCurWXID = $objSubmit->CurWXID;
		}
		$sRepairID = '';
		if(property_exists($objSubmit, 'RepairID')){
			$sRepairID = $objSubmit->RepairID;
		}
				
		//日志
		$this->myLog->saveLog($sCurWXID.' 01 提交处理:'.$sRepairID);
		
		if($strSubmit==''|| $sCurWXID == '' || $sCurWXID == 'undefined'){
			return '{"errcode":2,"errmsg":"Can not find your id, please try again."}';
		}
		
	//03 保存用户提交的Ticket内容
		//判断是否是重复提交
		$fileNewKey = 'ticket/user_'.$sCurWXID.'.csv';
		if(is_file($fileNewKey)){
			if($strSubmit == file_get_contents($fileNewKey)){
				
				//日志记录
				$strTemp = $sCurWXID.' 02 重复提交，结束';
				$this->myLog->saveLog($strTemp);
		
				return '{"errcode":0,"errmsg":"Repeat"}';
			}		 
		}
		file_put_contents($fileNewKey,$strSubmit);
		
		if($sRepairID == ''){
			return '';
		}
		
		$result = $this->readRepairByRepairID($sRepairID);		
		$objTemp = json_decode($result);	
		//日志记录
		$strTemp = $sCurWXID.' 02 读取('.$sRepairID.')'.$objTemp->errmsg;	
		$this->myLog->saveLog($strTemp);
		
		if($objTemp->errcode != 0){
			echo '{"errcode":1,"errmsg":"Can not find Repair ID '.$sRepairID.'  error:'.$objTemp->errmsg.'"}';
			return;
		}
	
	
		return '';
	}
	
	//提交代码初始化
	public function doMachineSubmitInit($strSubmit){
		//获得参数	
		//$strSubmit = urldecode(file_get_contents("php://input"));	
		if($strSubmit ==''){
			return '{"errcode":1,"errmsg":"Can not find data"}';
		}
		
		$sToday = date("Y");
		file_put_contents('ticket/submit_'.$sToday.'.csv', date("Y-m-d H:i:s").' '.$strSubmit."\r\n", FILE_APPEND); 	
		$objSubmit = json_decode($strSubmit);	
		$sCurWXID = '';
		if(property_exists($objSubmit, 'CurWXID')){
			$sCurWXID = $objSubmit->CurWXID;
		}
		$sMachineID = '';
		if(property_exists($objSubmit, 'MachineID')){
			$sMachineID = $objSubmit->MachineID;
		}
				
		//日志
		$this->myLog->saveLog($sCurWXID.' 01 提交处理:'.$sMachineID);
		
		if($strSubmit==''|| $sCurWXID == '' || $sCurWXID == 'undefined'){
			return '{"errcode":2,"errmsg":"Can not find your id, please try again."}';
		}
		
	//03 保存用户提交的Ticket内容
		//判断是否是重复提交
		$fileNewKey = 'ticket/user_'.$sCurWXID.'.csv';
		if(is_file($fileNewKey)){
			if($strSubmit == file_get_contents($fileNewKey)){
				
				//日志记录
				$strTemp = $sCurWXID.' 02 重复提交，结束';
				$this->myLog->saveLog($strTemp);
		
				return '{"errcode":0,"errmsg":"Repeat"}';
			}		 
		}
		file_put_contents($fileNewKey,$strSubmit);
		
		if($sMachineID == ''){
			return '';
		}
		
		$result = $this->readMachineByMachineID($sMachineID);		
		$objTemp = json_decode($result);	
		//日志记录
		$strTemp = $sCurWXID.' 02 读取('.$sMachineID.')'.$objTemp->errmsg;	
		$this->myLog->saveLog($strTemp);
		
		if($objTemp->errcode != 0){
			echo '{"errcode":1,"errmsg":"Can not find Machine ID '.$sMachineID.'  error:'.$objTemp->errmsg.'"}';
			return;
		}
	
	
		return '';
	}
	
	
	//保存完成记录
	public function doSaveFinishLog($obj,$sType='Text'){
		
		//保存完成记录
		$fileNewKey = 'finish/finish_'.$this->mRepairID.'.txt';
		$arrTemp = array('CreatedDate'=>date("Y-m-d H:i:s"),
							'Status'=>$this->mRepairStatus,
							'CreatedWXID'=>$obj->CurWXID,
							'CreatedName'=>$obj->CurName,
							'FinishText'=>$obj->FinishText,
							'FinishResult'=>$obj->FinishResult,
							'FinishDate'=>$obj->FinishDate,
							'FinishFee'=>$obj->FinishFee);	
		file_put_contents($fileNewKey, json_encode($arrTemp)."\r\n", FILE_APPEND); 
		
		return 'OK';
	}
	
	
	
	
//【数据库查询批量导出函数】	
		
	//查找记录，
	public function readListByClass($sWXID,$sClass,$sFrom,$sTo=''){
		
		 //如果没联机上，就退出
		if($this->conn == false){
			return '{"errcode":1,"errmsg":"Conn is Error"}';
		}
		
		//获得范围
		$numFrom = (int)$sFrom;
		if($sFrom == ''){
			$numFrom = 0;
		}		
		$numTo = (int)$sTo;
		if($sTo == ''){
			$numTo = 8;
		}
		
		$strUrlKey = "SELECT * from MachineRepair where RepairStatus != '90' ";		
		
		if($sClass == '01'){
			//我创建的所有
			$strSQL = $strUrlKey." AND (SubmitUserID = '".$sWXID."' OR CreatedUserID = '".$sWXID."') order by CreatedDate desc";		
		}else if($sClass == '02'){
			//我创建的未完成的
			//$strSQL = "SELECT * from [Weixin_ZeissService].[dbo].[SZ_CIP] where ([CIPStatus] = '10' OR [CIPStatus] = '30') AND ([RequestWXID] = '".$sWXID."') order by [CreatedDate] desc";		
		}else if($sClass == '11'){
			//我处理的所有的
			//$strSQL = "SELECT * from [Weixin_ZeissService].[dbo].[SZ_CIP] where [CIPStatus] = '100' AND ([FinishWXID] = '".$sWXID."' OR [ProcessorWXID] = '".$sWXID."') order by [CreatedDate] desc";		
		}else if($sClass == '12'){
			//我处理的所有的
			//$strSQL = "SELECT * from [Weixin_ZeissService].[dbo].[SZ_CIP] where ([CIPStatus] = '15' OR [CIPStatus] = '20')  AND ([FinishWXID] = '".$sWXID."' OR [ProcessorWXID] = '".$sWXID."') order by [CreatedDate] desc";		
		}else if($sClass == '21'){
			//别人处理完成的所有的
			//$strSQL = "SELECT * from [Weixin_ZeissService].[dbo].[SZ_CIP] where [CIPStatus] = '100'  order by [UpdatedDate] desc";		
		
		}
				
		//echo $strSQL,"<br>";
		$query = $this->conn->query($strSQL);		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strURL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error"}';
		}
		
		$arrReturn = array();
		$arrLines = array();
		$strLine = "";
		$numLine = 0;
		$arrLine = array();
		
		//赋值
		$numShow = 0;
		
		$num = 0;
		//赋值
		while ($row = $query->fetch_assoc()) {
			
			//总数量
			$numLine++;
			if($numLine>=$numFrom && $numLine <=$numTo){
				
				//显示的数量
				$numShow++;				
				$this->setRepairField($row);			
								
				$arrLine[] = array('No'=>$numLine,'RecordID'=>$this->mID,
									'RepairID'=>$this->mRepairID,
									'RepairStatus'=>$this->mRepairStatus,
									'RepairStatusText'=>$this->getStatusText($this->mRepairStatus),
									'MachineID'=>$this->mMachineID,
									'MachineName'=>$this->mMachineName,
									'SubmitDate'=>$this->mSubmitDate,
									'SubmitUserID'=>$this->mSubmitUserID,
									'SubmitName'=>$this->mSubmitName,
									'SubmitComment'=>$this->mSubmitComment
									);
				
			}			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"OK","Num"=>$numLine,"NumShow"=>$numShow,"From"=>$numFrom,"List"=>$arrLine);
		return  urldecode(json_encode($arrReturn));
		
		
	 }

	//导出数据库到页面文件
	public function repairExplodeJson($sFrom = 1,$sTo = 15,$searchKey = '',$sSortField='',$sSortType='A'){

		if($searchKey != ''){
			$searchKey = ' where '.$searchKey;
		}
	
		//排序字段
		if($sSortField == ''){
			$sSortField = 'CreatedDate';
		}else if($sSortField != ''){
			//$sSortField = '['.$sSortField.']';
		}else{
			$sSortField = 'CreatedDate';
		}
		
		//排序方式 asc
		if($sSortType=='desc'){
			$sSortType = 'desc';
		}else{
			$sSortType = '';
		}
	
	
		
		if($sFrom == ''){
			$numFrom = 1;
		}else{
			$numFrom = (int)$sFrom;
		}
		if($sTo == ''){
			$numTo = $numFrom + 14;
		}else{
			$numTo = (int)$sTo;
		}
	
		$strSQL = "SELECT * FROM MachineRepair ".$searchKey." order by ".$sSortField.' '.$sSortType;
		//echo $strSQL,"<br>";
		$query = $this->conn->query($strSQL);		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error '.$strSQL.'"}';
		}
		
		
  		$numTotal = 0;	
		$arrRow = array();
		while ($row = $query->fetch_assoc()) {
			
			//总数量
			$numTotal++;
			if($numTotal>=$numFrom && $numTotal<= $numTo){
				
				$arrLine = $this->setRepairField($row);
				$arrLine['NumLine'] = $numTotal;
				$arrRow[] = $arrLine;
			}
		}	
		
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","from"=>1,"to"=>15,"Num"=>$numTotal,"total"=>$numTotal,"rows"=>$arrRow,"URL"=>$strSQL);
		return  json_encode($arrReturn);
	}


	//导出数据库到页面文件
	public function machineExplodeJson($sFrom = 1,$sTo = 15,$searchKey = '',$sSortField='',$sSortType='A'){

		
		if($searchKey != ''){
			$searchKey = " where MachineStatus!='Delete' AND ".$searchKey;
		}else{
			$searchKey = " where MachineStatus!='Delete' ";
		}
	
		//排序字段
		if($sSortField == ''){
			$sSortField = 'CreatedDate';
		}else if($sSortField != ''){
			//$sSortField = '['.$sSortField.']';
		}else{
			$sSortField = 'CreatedDate';
		}
		
		//排序方式 asc
		if($sSortType=='desc'){
			$sSortType = 'desc';
		}else{
			$sSortType = '';
		}
	
	
		
		if($sFrom == ''){
			$numFrom = 1;
		}else{
			$numFrom = (int)$sFrom;
		}
		if($sTo == ''){
			$numTo = $numFrom + 14;
		}else{
			$numTo = (int)$sTo;
		}
	
		$strSQL = "SELECT * FROM MachineInfo   ".$searchKey." order by ".$sSortField.' '.$sSortType;
		//echo $strSQL,"<br>";
		$query = $this->conn->query($strSQL);		
		if($query === false){ //查询不到结果
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);		
			return '{"errcode":1,"errmsg":"SQL is Error '.$strSQL.'"}';
		}
		
		
  		$numTotal = 0;	
		$numShow = 0;
		$arrRow = array();
		while ($row = $query->fetch_assoc()) {
			
			//总数量
			$numTotal++;
			if($numTotal>=$numFrom && $numTotal<= $numTo){
				$numShow ++;
				$arrLine = $this->setMachineField($row);
				$arrLine['NumLine'] = $numTotal;
				$arrRow[] = $arrLine;
			}
		}	
		
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok",
			"from"=>$numFrom,"to"=>$numTo,
			"NumTotal"=>$numTotal,"NumShow"=>$numShow,
			"rows"=>$arrRow,"URL"=>$strSQL);
		return  json_encode($arrReturn);
	}















	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//获取记录列表
	public function readListByWXID($sWXID,$sYear='',$sMonth='',$sType='',$sFrom=1){
		
		if($sType != ''){
			$sType = " AND RecordType='".$sType."' ";
		}
		if($sYear != ''){
			$sYear = " AND YEAR(RecordDate)= ".$sYear;
		}
		if($sMonth != ''){
			$sMonth = " AND Month(RecordDate)= ".((int)$sMonth);
		}
		//ocsHG59CAU-Y6zD_E8-LJS_FNAX4
		$strSQL = "SELECT * from CarUseRecord where RecordStatus != '90' AND WXID = '".$sWXID."' ".$sType." ".$sYear." ".$sMonth." order by RecordDate desc ";
		
		//$strSQL = "update CarUseRecord set WXID = 'ocsHG59CAU-Y6zD_E8-LJS_FNAX4' ";
		
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);	
			//echo $strURL;
			return '{"errcode":1,"errmsg":"empty"}';
		}
		
		//array_reverse($a)
		$numTotal = 0;		
		$numCount = 0;
		$numFrom = (int)$sFrom;
		$numTo = $numFrom + 100;
		$numFee = 0;

		$arrLine = array();	
		//赋值
		while ($row = $query->fetch_assoc()) {
			
			$numTotal++;
			
			if($numTotal >= $numFrom && $numTotal< $numTo){
				$numCount++;
				
				$this->setRowField($row);
				
				$numFee = $numFee + (double)$this->mRecordFee;
				$sComment = $this->mRecordComment;
				
				//处理油价特殊显示
				/*
				if($this->mRecordType == 'JY'){
					$num1 = (double)$this->mNumField2;
					$s1 = '';
					if($num1!=0){
						$s1 = '加油'.((string)$num1).'升，';
					}
					$num2 = (double)$this->mNumField3;
					$s2 = '';
					if($num2!=0){
						$s2 = '油价'.((string)$num2).'元/升，';
					}
					$sComment =$s1.$s2.$sComment;
				}
				*/
				
				$arrTemp = array(
					'Num'=>$numCount,
					'WXID'=>$this->mWXID,
					'RecordID'=>$this->mID,
					'Date'=>$this->mRecordDate,
					'Type'=>$this->mRecordType,
					'Fee'=>$this->mRecordFee,
					'Comment'=>$sComment,
					'NumField1'=>$this->mNumField1,
					'NumField2'=>$this->mNumField2,
					'NumField3'=>$this->mNumField3,
					'CreatedDate'=>$this->mCreatedDate
				);
				$arrLine[] = ($arrTemp);		
			}
		}	
		
		$arrReturn = array();
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['NumTotal'] = $numTotal;
		$arrReturn['NumShow'] = $numCount;
		$arrReturn['NumFrom'] = $numFrom;
		$arrReturn['NumTo'] = $numTo;
		$arrReturn['NumFee'] = $numFee;
		
		$arrReturn['WXID'] = $sWXID;
		
		$arrReturn['List'] = ($arrLine);
		
		return json_encode($arrReturn);
	}
	
	//获取汇总记录列表
	public function readListTotalByWXID($sWXID,$sYear='',$sMonth=''){
		
		
		if($sYear != ''){
			$sYear = " AND YEAR(RecordDate)= ".$sYear;
		}
		if($sMonth != ''){
			$sMonth = " AND Month(RecordDate)= ".((int)$sMonth);
		}
		
		$strSQL = "select * from (SELECT RecordType,sum(RecordFee) as FeeTotal,sum(1) as ItemCount from CarUseRecord where RecordStatus != '90' AND WXID = '".$sWXID."' ".$sYear." ".$sMonth." group by RecordType) as a order by a.FeeTotal desc ";
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);	
			//echo $strURL;
			return '{"errcode":1,"errmsg":"empty"}';
		}
		
		//array_reverse($a)
		$numTotal = 0;		
		$numCount = 0;
		$numFee = 0;

		$arrLine = array();	
		//赋值
		while ($row = $query->fetch_assoc()) {
			
			$numTotal++;
			
			$numFee = $numFee + (double)$row['FeeTotal'];
			$arrTemp = array(
				'Num'=>$numTotal,
				'Type'=>$row['RecordType'],
				'Fee'=>$row['FeeTotal'],
				'ItemCount'=>$row['ItemCount']
			);
			$arrLine[] = ($arrTemp);	
		}	
		
		$arrReturn = array();
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['NumTotal'] = $numTotal;
		$arrReturn['NumFee'] = $numFee;
		$arrReturn['WXID'] = $sWXID;
		
		//$arrReturn['List'] = array_reverse($arrLine);
		$arrReturn['List'] = ($arrLine);
		
		return json_encode($arrReturn);
	}
	
	//获取按月记录列表
	public function readList2ByWXID($sWXID,$sYear='',$sMonth=''){
		
		
		if($sYear != ''){
			$sYear = " AND YEAR(RecordDate)= ".$sYear;
		}
		if($sMonth != ''){
			$sMonth = " AND Month(RecordDate)= ".((int)$sMonth);
		}
		//ocsHG59CAU-Y6zD_E8-LJS_FNAX4
		//$strSQL = "SELECT * from CarUseRecord where RecordStatus != '90' AND WXID = '".$sWXID."' ".$sYear." ".$sMonth." order by RecordDate desc ";
		
		$strSQL = "SELECT RecordDate,sum(1) as SumCount,sum(RecordFee) as SumFee,GROUP_CONCAT(RecordType) as SumType from CarUseRecord where RecordStatus != '90' AND WXID = '".$sWXID."' ".$sYear." ".$sMonth." group by RecordDate";
		
		//$strSQL = "update CarUseRecord set WXID = 'ocsHG59CAU-Y6zD_E8-LJS_FNAX4' ";
		
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);	
			//echo $strURL;
			return '{"errcode":1,"errmsg":"empty"}';
		}
		
		//array_reverse($a)
		$numTotal = 0;		
		$numCount = 0;
		$numFrom = (int)$sFrom;
		$numTo = $numFrom + 100;
		$numFee = 0;

		$arrLine = array();	
		
		//赋值
		while ($row = $query->fetch_assoc()) {
			
			$numTotal++;
			
			if($numTotal >= $numFrom && $numTotal< $numTo){
				$numCount++;
				$numFee  += $row['SumFee'];
				
				$arrTemp = array(
					'Num'=>$row['SumCount'],
					'Date'=>substr($row['RecordDate'],0,10),
					'Type'=>$row['SumType'],
					'Fee'=>$row['SumFee']
				);
				$arrLine[] = ($arrTemp);		
			}
		}	
		
		$arrReturn = array();
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['NumTotal'] = $numTotal;
		$arrReturn['NumShow'] = $numCount;
		$arrReturn['NumFrom'] = $numFrom;
		$arrReturn['NumTo'] = $numTo;
		$arrReturn['NumFee'] = $numFee;
		$arrReturn['WXID'] = $sWXID;
		
		$arrReturn['List'] = ($arrLine);
		
		return json_encode($arrReturn);
	}
	
	//获取记录列表
	public function readList3ByWXID($sWXID,$sYear='',$sMonth='',$sDay=''){
		
		if($sType != ''){
			$sType = " AND RecordType='".$sType."' ";
		}
		if($sYear != ''){
			$sYear = " AND YEAR(RecordDate)= ".$sYear;
		}
		if($sMonth != ''){
			$sMonth = " AND Month(RecordDate)= ".((int)$sMonth);
		}
		
		if($sDay != ''){
			$sDay = " AND Day(RecordDate)= ".((int)$sDay);
		}
		
		//ocsHG59CAU-Y6zD_E8-LJS_FNAX4
		$strSQL = "SELECT * from CarUseRecord where RecordStatus != '90' AND WXID = '".$sWXID."' ".$sType." ".$sYear." ".$sMonth." ".$sDay." order by RecordDate desc ";
		
		//$strSQL = "update CarUseRecord set WXID = 'ocsHG59CAU-Y6zD_E8-LJS_FNAX4' ";
		
		//echo $strSQL,'<br>';
		
		$query = $this->conn->query($strSQL);
		if(!$query){
			$strTemp = '读取SQL结果：'.$strSQL;
			$this->myLog->errorLog($strTemp);	
			//echo $strURL;
			return '{"errcode":1,"errmsg":"empty"}';
		}
		
		//array_reverse($a)
		$numTotal = 0;		
		$numCount = 0;
		$numFrom = (int)$sFrom;
		$numTo = $numFrom + 100;
		$numFee = 0;

		$arrLine = array();	
		//赋值
		while ($row = $query->fetch_assoc()) {
			
			$numTotal++;
			
			if($numTotal >= $numFrom && $numTotal< $numTo){
				$numCount++;
				
				$this->setRowField($row);
				
				$numFee = $numFee + (double)$this->mRecordFee;
				$sComment = $this->mRecordComment;
				
				//处理油价特殊显示
				/*
				if($this->mRecordType == 'JY'){
					$num1 = (double)$this->mNumField2;
					$s1 = '';
					if($num1!=0){
						$s1 = '加油'.((string)$num1).'升，';
					}
					$num2 = (double)$this->mNumField3;
					$s2 = '';
					if($num2!=0){
						$s2 = '油价'.((string)$num2).'元/升，';
					}
					$sComment =$s1.$s2.$sComment;
				}
				*/
				
				$arrTemp = array(
					'Num'=>$numCount,
					'WXID'=>$this->mWXID,
					'RecordID'=>$this->mID,
					'Date'=>$this->mRecordDate,
					'Type'=>$this->mRecordType,
					'Fee'=>$this->mRecordFee,
					'Comment'=>$sComment,
					'NumField1'=>$this->mNumField1,
					'NumField2'=>$this->mNumField2,
					'NumField3'=>$this->mNumField3,
					'CreatedDate'=>$this->mCreatedDate
				);
				$arrLine[] = ($arrTemp);		
			}
		}	
		
		$arrReturn = array();
		$arrReturn['errcode'] = 0;
		$arrReturn['errmsg'] = 'OK';
		$arrReturn['NumTotal'] = $numTotal;
		$arrReturn['NumShow'] = $numCount;
		$arrReturn['NumFrom'] = $numFrom;
		$arrReturn['NumTo'] = $numTo;
		$arrReturn['NumFee'] = $numFee;
		
		$arrReturn['WXID'] = $sWXID;
		
		$arrReturn['List'] = ($arrLine);
		
		return json_encode($arrReturn);
	}
	
	
	



	//删除记录
	public function deleteByRecordID($sRecordId){
			
		//否则就是插入新记录
		$strSQL = "UPDATE CarUseRecord
		   SET RecordStatus = '90'		   
			  ,UpdatedDate = now()
		 WHERE ID = ".$sRecordId;
		
		$query = $this->conn->query($strSQL);

		if($query == false){
			$strTemp = 'Error Update SQL:'.$strSQL;
			$this->myLog->errorLog($strTemp);
			
			return "Error";
		}else{
			return "OK";
		}
		
	}
	


	//导出数据库到Excel文件
	public function explodeExcel($sFileName){
		
		$strSQL = "SELECT * FROM FansChat where [OfficialName] = '".$this->mOfficialName."' order by [ChatDate] ";
		sqlsrv_query($this->conn,"set names 'UTF8'");		
		$query = sqlsrv_query( $this->conn, $strSQL);
		
		if($query === false){ //查询不到结果
			return  '{"errcode":3,"errmsg":"record is empty","Num":0}';
		}
		
		//copy($this->RootRepair.'/php/repairList_empty.csv',$sFileName );

		$strLine = "";
		$numLine = 0;
		$arrLine = array();
  		$numTotal = 0;		
		//赋值
		$numShow = 0;
		while ($row = sqlsrv_fetch_array ( $query,SQLSRV_FETCH_ASSOC) ) {
			
			//总数量
			$numTotal++;
			
			$strLine = '';
			$strLine .= '"'.$this->myTools->Field2Date($row['ChatDate']).'",';
			$strLine .= '"'.$row['FansWXID'].'",';
			
			if($row['ChatType'] == 'A'){
			
				$strLine .= '"'.iconv('GBK//IGNORE','utf-8', $row['ChatName']).'",';
				$strLine .= '"'.iconv('GBK//IGNORE','utf-8', $row['ChatText']).'",';
				//$strLine .= '"",';
				//$strLine .= '"",';
			}else{
				$strLine .= '"",';
				$strLine .= '"",';
				$strLine .= '"'.iconv('GBK//IGNORE','utf-8', $row['ChatName']).'",';
				$strLine .= '"'.iconv('GBK//IGNORE','utf-8', $row['ChatText']).'",';
			}
			
			file_put_contents($sFileName, $strLine."\r\n", FILE_APPEND);
			
		}	
		
		$arrReturn = array("errcode"=>0,"errmsg"=>"ok","Num"=>$numTotal);
		return  json_encode($arrReturn);
	}
	


	
	
	
	
	
	
	
}


