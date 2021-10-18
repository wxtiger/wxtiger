
function MathRand(numCount){ 
	//随机产生指定的整数
	var Num=""; 
	for(var i=0;i<numCount;i++) { 
		Num+=Math.floor(Math.random()*10); 
	}
	return Num;
} 

function DateFormat(date,sType){
                var y = date.getFullYear();  
                var m = date.getMonth() + 1;  
                m = m < 10 ? ('0' + m) : m;  
                var d = date.getDate();  
                d = d < 10 ? ('0' + d) : d;  
                var h = date.getHours();  
                h=h < 10 ? ('0' + h) : h;  
                var minute = date.getMinutes();  
                minute = minute < 10 ? ('0' + minute) : minute;  
                var second=date.getSeconds();  
                second=second < 10 ? ('0' + second) : second;  
				
				if(sType=="YMDhms"){
					return y + m + d + h + minute + second;  
				}
				if(sType=="YMD"){
					return y + m + d;  
				}
                return y + '-' + m + '-' + d+' '+h+':'+minute+':'+second;  
};  

//设置日期字段的格式
function myformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function myparser(s){
	if (!s) return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
		return new Date(y,m-1,d);
	} else {
		return new Date();
	}
}


function StringMiddle(source,strFrom,strTo){
	
	var n1=source.indexOf(strFrom)	
	if(n1<1){
		return "";
	}

	n1 = n1+strFrom.length;
	n2 = source.indexOf(strTo,n1);
	if(n2<1){
		return source.substring(n1);
	}else{
		return source.substring(n1,n2);
	}

	

}


//发送手机验证码
function sendMobileSMS(mobile,sText){
		var requestURL = "http://aaaa/insider/share/js/SendMobileSMS/SendMobileSMS.php?Mobile="+mobile+"&Text="+sText+"&";
  
		return;
}

//发送邮件验证码
function sendEmailVerify(email,sText){
		var requestURL = "http://aaaa/insider/share/js/SendEmailVerify/sendemailverify.php?Email="+email+"&Text="+sText+"&";

		return;
		
}

function sendMsgtoStaff(wxid,appid,sText){
	//http://wechat.zeiss.com.cn/insider/share/js/sendmsgtostaff/sendmsgtostaff.php?WXID=tiger.yang@zeiss.com&AppId=2&Text=aaa&
		var requestURL = "http://aaaa/insider/share/js/sendmsgtostaff/sendmsgtostaff.php?WXID="+wxid+"&AppId="+appid+"&Text="+sText+"&";

		return;
}

//将URL拆成数组
function UrlGetRequest() { 

/*
var Request = new Object(); 
Request = GetRequest(); 
var 参数1,参数2,参数3,参数N; 
参数1 = Request['参数1']; 
参数2 = Request['参数2']; 
参数3 = Request['参数3']; 
参数N = Request['参数N']; 
*/
	var url = decodeURI(location.search); //获取url中"?"符后的字串 
	var theRequest = new Object(); 
	if (url.indexOf("?") != -1) { 
		var str = url.substr(1); 
		strs = str.split("&"); 
		for(var i = 0; i < strs.length; i ++) { 
			theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]); 
		} 
	} 
	return theRequest; 
	
} 

		


//显示待处理提示
function ajaxLoading(sText) { 

	if(sText == ''){
		sText = '正在处理，请稍候。。。';
	}
	
	$("<div class=\"datagrid-mask\"></div>").css({display:"block",width:"100%",height:$(window).height()}).appendTo("body");   
    $("<div class=\"datagrid-mask-msg\"></div>").html(sText).appendTo("body").css({height:"50px",display:"block",left:($(document.body).outerWidth(true) - 300) / 2,top:($(window).height() - 45) / 4});   

} 

function ajaxLoadEnd() { 
	 $(".datagrid-mask").remove(); 
	 $(".datagrid-mask-msg").remove(); 
}

//将数字转换成中文
function Arabia_to_Chinese(Num){
 for(i=Num.length-1;i>=0;i--)
 {
  Num = Num.replace(",","")//替换tomoney()中的“,”
  Num = Num.replace(" ","")//替换tomoney()中的空格
 }
 Num = Num.replace("￥","")//替换掉可能出现的￥字符
 if(isNaN(Num)) 
 { //验证输入的字符是否为数字
  //alert("请检查小写金额是否正确");
  return '';
 }
 //字符处理完毕后开始转换，采用前后两部分分别转换
 part = String(Num).split(".");
 newchar = ""; 
 //小数点前进行转化
 for(i=part[0].length-1;i>=0;i--)
 {
  if(part[0].length > 10)
  {
   //alert("位数过大，无法计算");
   return "";
  }//若数量超过拾亿单位，提示
  tmpnewchar = ""
  perchar = part[0].charAt(i);
  switch(perchar)
  {
   case "0": tmpnewchar="零" + tmpnewchar ;break;
   case "1": tmpnewchar="壹" + tmpnewchar ;break;
   case "2": tmpnewchar="贰" + tmpnewchar ;break;
   case "3": tmpnewchar="叁" + tmpnewchar ;break;
   case "4": tmpnewchar="肆" + tmpnewchar ;break;
   case "5": tmpnewchar="伍" + tmpnewchar ;break;
   case "6": tmpnewchar="陆" + tmpnewchar ;break;
   case "7": tmpnewchar="柒" + tmpnewchar ;break;
   case "8": tmpnewchar="捌" + tmpnewchar ;break;
   case "9": tmpnewchar="玖" + tmpnewchar ;break;
  }
  switch(part[0].length-i-1)
  {
   case 0: tmpnewchar = tmpnewchar +"元" ;break;
   case 1: if(perchar!=0)tmpnewchar= tmpnewchar +"拾" ;break;
   case 2: if(perchar!=0)tmpnewchar= tmpnewchar +"佰" ;break;
   case 3: if(perchar!=0)tmpnewchar= tmpnewchar +"仟" ;break;
   case 4: tmpnewchar= tmpnewchar +"万" ;break;
   case 5: if(perchar!=0)tmpnewchar= tmpnewchar +"拾" ;break;
   case 6: if(perchar!=0)tmpnewchar= tmpnewchar +"佰" ;break;
   case 7: if(perchar!=0)tmpnewchar= tmpnewchar +"仟" ;break;
   case 8: tmpnewchar= tmpnewchar +"亿" ;break;
   case 9: tmpnewchar= tmpnewchar +"拾" ;break;
  }
  newchar = tmpnewchar + newchar;
 }
 //小数点之后进行转化
 if(Num.indexOf(".")!=-1)
 {
  if(part[1].length > 2) 
  {
   //alert("小数点之后只能保留两位,系统将自动截断");
   part[1] = part[1].substr(0,2)
  }
  for(i=0;i<part[1].length;i++)
  {
   tmpnewchar = ""
   perchar = part[1].charAt(i)
   switch(perchar)
   {
    case "0": tmpnewchar="零" + tmpnewchar ;break;
    case "1": tmpnewchar="壹" + tmpnewchar ;break;
    case "2": tmpnewchar="贰" + tmpnewchar ;break;
    case "3": tmpnewchar="叁" + tmpnewchar ;break;
    case "4": tmpnewchar="肆" + tmpnewchar ;break;
    case "5": tmpnewchar="伍" + tmpnewchar ;break;
    case "6": tmpnewchar="陆" + tmpnewchar ;break;
    case "7": tmpnewchar="柒" + tmpnewchar ;break;
    case "8": tmpnewchar="捌" + tmpnewchar ;break;
    case "9": tmpnewchar="玖" + tmpnewchar ;break;
   }
   if(i==0)tmpnewchar =tmpnewchar + "角";
   if(i==1)tmpnewchar = tmpnewchar + "分";
   newchar = newchar + tmpnewchar;
  }
 }
 //替换所有无用汉字
 while(newchar.search("零零") != -1)
  newchar = newchar.replace("零零", "零");
 newchar = newchar.replace("零亿", "亿");
 newchar = newchar.replace("亿万", "亿");
 newchar = newchar.replace("零万", "万");
 newchar = newchar.replace("零元", "元");
 newchar = newchar.replace("零角", "");
 newchar = newchar.replace("零分", "");
 if (newchar.charAt(newchar.length-1) == "元" || newchar.charAt(newchar.length-1) == "角")
     newchar = newchar+"整"
 return newchar;
}

//用户输入的文本内容，删除其中坏的字符
function StringDelBad(source){
	
	if(source == '') return '';
	
	source = source.replace(/\r\n/g,'<br>');
	source = source.replace(/\n\r/g,'<br>');
	source = source.replace(/\n/g,'');
	source = source.replace(/\r/g,'');
	source = source.replace(/"/g,'“');
	source = source.replace(/'/g,"‘");
	source = source.replace(/&/g,"＆");	
	return source;
}

//删除字符串数组空元素
function StringDelEmpty(source){
	arrTemp = source.split(";");
	strTemp = '';
	for(i=0 ; i<arrTemp.length ; i++){
		if(arrTemp[i].trim() != ''){
			strTemp += ';'+arrTemp[i].trim();
		}
	}
	if(strTemp == ''){
		return '';
	}else{
		return strTemp.substring(1);
	}
}

