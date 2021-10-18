function addObj(objLine){

	objList = document.getElementById("idList");
	
	var objNew_li = document.createElement("li"); 
	var objNew_a = document.createElement("a"); 
	var objNew_div = document.createElement("div"); 
	
	objNew_a.className = "cl";
	objNew_a.target="_blank";		
	
	objNew_a.href = 'register.php?ID='+objLine.RepairID+'&';	
	
	numLine++;
	sTemp = '<div class="list-content">('+numLine+')【'+objLine.RepairID+'】 <b>'+objLine.RepairStatusText+'</b>';
	sTemp += '<br><b>设备</b>：'+objLine.MachineName+'('+objLine.MachineID+')';
	
	sTemp += '<br><b>创建者</b>：'+objLine.SubmitName+'('+objLine.SubmitUserID+')';
	sTemp += '<br><b>创建时间</b>：'+objLine.SubmitDate+'</div>';
	
	objNew_div.innerHTML = sTemp;
    	
	objList.appendChild(objNew_li);
	objNew_li.appendChild(objNew_a);
	objNew_a.appendChild(objNew_div);
	
}

function delObj(){
	obj = document.getElementById("idList");	
	while(obj.hasChildNodes()) {
        obj.removeChild(obj.firstChild);
    }
}

function doOpen(){
	//http://wechat.zeiss.com.cn/insider/app/crmTicket/html/myticket/listshow.html
	
	//获取URL参数
		var Request = new Object(); 
		Request = GetRequest(); 
		sCode = Request['code'];
		sWXID = window.localStorage.getItem("WXID");
		//sWXID = "tiger.yang@zeiss.com"
		$("#idWXCode").val(sCode);				
		$("#idWXID").val(sWXID);		
		
	//提交查询
		var requestURL = "http://wechat.zeiss.com.cn/insider/app/address/staffopen.php?Code="+sCode+"&WXID="+sWXID+"&";
	//ajax
		$.ajax({
		url:requestURL,
		type:'GET', //GET
		async:true,    //或false,是否异步
		data:{},
		timeout:5000,    //超时时间
		dataType:'json', 		
		success:function(jsonResp,textStatus,jqXHR){					
			/*
			if(jsonResp.errcode != 0){	
				alert("仅限蔡司员工在蔡司Insider里面打开，不可以刷新，不可以转发，\n\n请重新搜索打开试试。\nby tiger on 2017-07-20");
				location.href = "http://wechat.zeiss.com.cn/insider/app/main/welcome.html";
				return;										
			}
			
			if(jsonResp.UserName == ""){
				alert("不能识别您的身份，可能是您没有关注蔡司Insider，3请关注后再试试");
				location.href = "http://wechat.zeiss.com.cn/insider/app/main/welcome.html";
				return;
			}
			*/		
			window.localStorage.setItem("WXID",jsonResp.WXID);
			window.localStorage.setItem("Code",sCode);
			
			$("#idWXID").val(jsonResp.WXID);
			$("#idUserName").val(decodeURI(jsonResp.UserName));
			$("#idUserMobile").val(jsonResp.Mobile);
			$("#idUserEmail").val(jsonResp.Email);
			$("#idADAccount").val(jsonResp.ADAccount);
			$("#idDivision").val(jsonResp.Division);
			
			//读取SQL数据到HTML网页上
			//doSearch();
		}		
	})
}		

//返回CIP状态的文本描述
function getStatusMsg(sStatus){
	if(sStatus == '10'){
		return '草拟中';
	}else if(sStatus == '15'){
		return '待确定';
	}else if(sStatus == '90'){
		return '取消';	
	}else if(sStatus == '20'){
		return '正在处理';	
	}else if(sStatus == '30'){
		return '待验证';	
	}else if(sStatus == '100'){
		return '流程结束';	
		
	}
	
}

//验证身份
function doOpen_checkUser(sFunctionName){
	ajaxLoading('');

	var Request = new Object(); 
	Request = GetRequest(); 
	sCode = Request['code'];
	sState = Request['state'];	
	sCIPID = Request['CIPID'];	
	$("#idCIPID").val(sCIPID);
	sWXID = '';
	
	var requestURL = "/insider/app/address/staffopen.php?Code="+sCode+"&WXID="+sWXID+"&";
	
	$.ajax({
		url:requestURL,
		type:'GET', //GET
		async:true,    //或false,是否异步
		data:'',
		timeout:5000,    //超时时间
		dataType:'json', 		
		success:function(jsonResp,textStatus,jqXHR){
			ajaxLoadEnd();
			
			if(jsonResp.errcode != 0){	
				showMsg("仅限蔡司员工在蔡司Insider里面打开，不可以刷新，不可以转发，\n\n请重新搜索打开试试。");
				location.href = "http://wechat.zeiss.com.cn/insider/app/main/welcome.html";
				return;										
			}
			if(jsonResp.UserName == ""){
				showMsg("不能识别您的身份，可能是您没有关注蔡司Insider，请关注后再试试");
				location.href = "http://wechat.zeiss.com.cn/insider/app/main/welcome.html";
				return;
			}
			
			//保存当前用户信息
			$("#idWXID").val(jsonResp.WXID);
			$("#idUserName").val(decodeURI(jsonResp.UserName));
			$("#idUserMobile").val(jsonResp.Mobile);
			
			doSearch('New');					
			return;	
		}
		
	})

}

		