<?php
///weixin/system/class/wxjssdk/wxPageInit.php?Official=&URL=http://www.vvtiger.com&

require_once "jssdk.php";

$sUrl = $_GET['URL'];
$sOfficial = $_GET['Official'];
$sOfficialType = $_GET['OfficialType'];

$jssdk = new JSSDK($sOfficial,$sUrl,$sOfficialType);
$signPackage = $jssdk->getSignPackage();

echo  '{"appId":"'.$signPackage["appId"].'","timestamp":"'.$signPackage["timestamp"].'","nonceStr":"'.$signPackage["nonceStr"].'","signature":"'.$signPackage["signature"].'"}';
 