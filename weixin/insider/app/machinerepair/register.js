
//01 读取参数
function doReadQuery(){
	var Request = UrlGetRequest(); 
	$("#idRepairID").val(Request['ID']);	
}

//02 识别用户身份
function doReadUser(){
	//验证用户身份是否是在职员工	
	$("#idCurWXID").val('17665201558');	
	$("#idCurName").val('杨泰格');	
}

//03 读取报修数据
function doReadRepair(){
	//ajaxLoading('');
	
	sWXID = $("#idCurWXID").val();	
	sRepairID = $("#idRepairID").val();	
	var requestURL = "php/getRepairByRepairID.php?WXID="+sWXID+"&ID="+sRepairID+"&";
	
	ajaxLoading('读取资料...');	
	$.ajax({
		url:requestURL,
		type:'GET', dataType:'text', 
		error:function (XMLHttpRequest, textStatus, errorThrown) {
                ajaxLoadEnd();	
	　　　　　  alert("提交失败，请重新试试，"+textStatus);
				return;
        },
		complete:function(XMLHttpRequest,status){
	　　　　if(status=='timeout'){
	 　　　　　 ajaxLoadEnd();	
	　　　　　  alert("服务器无响应，请重新试试");
				return;
	　　　　}
　　	},	
		success:function(sText,textStatus,jqXHR){
			ajaxLoadEnd();			
			//alert(sText);
			objJson = JSON.parse(decodeURI(sText));
			
			if(objJson.errcode != 0){	
				alert("读取数据库出错："+objJson.errmsg);
				return;										
			}
			if((objJson.Num*1) < 1){
				alert("读取数据库出错：无此单号，"+sRepairID);
				return;	
			}
			
			objRepair = objJson.Line[0];
			//01 保修单 RepairID
			$('#idRepairID').val(objRepair.RepairID);
			$('#idRepairIDShow').html(objRepair.RepairID+' ('+objRepair.RepairStatusText+')');
			$('#idRepairStatus').val(objRepair.RepairStatus);
			//alert(objRepair.RepairStatus);
			doSetEvent();	//要读取状态以后，才能更新，
			
			//02 设备名
			sTemp = '<a href="javascript:doOpenMachineBody(\''+objRepair.MachineID+'\')">';
			sTemp += objRepair.MachineName+' ('+objRepair.MachineID+')</a>';
			$('#idMachineNameShow').html(sTemp);
			
			
			//03 创建者
			sTemp = objRepair.SubmitName+' (<a href="tel:'+objRepair.SubmitUserID+'">'+objRepair.SubmitUserID+'</a>)';
			sTemp += ' 时间：'+objRepair.SubmitDate;
			$('#idSubmitNameShow').html(sTemp);
			
			
			//解决问题的人，默认是Checker
			sTemp = objRepair.SolvedName;
			if(sTemp!=''){
				
				sTemp = objRepair.CheckName+' (<a href="tel:'+objRepair.CheckUserID+'">'+objRepair.CheckUserID+'</a>)';
				if(objRepair.SolvedName != objRepair.CheckName){
					sTemp = sTemp + ' / '+objRepair.SolvedName+' (<a href="tel:'+objRepair.SolvedUserID+'">'+objRepair.SolvedUserID+'</a>)';
				}
				$('#idNextWXID').val(objRepair.SolvedUserID);
				$('#idNextName').val(objRepair.SolvedName);
				
			}else{
				sTemp = objRepair.CheckName+' (<a href="tel:'+objRepair.CheckUserID+'">'+objRepair.CheckUserID+'</a>)';
				
				$('#idNextWXID').val(objRepair.CheckUserID);
				$('#idNextName').val(objRepair.CheckName);
			}
			//alert(objRepair.RepairStatus);
			//如果是20，在选择人员，否则就是显示认识
			if(objRepair.RepairStatus == '20'){
				$('#idSelectEnginner').combogrid('setValue',$('#idNextWXID').val());
				$('#idSelectEnginner').combogrid('setText',$('#idNextName').val());
			}else{
				$('#idSolvedNameShow').html(sTemp);
			}
			
			
			//报修内容
			$('#idSubmitComment').html(objRepair.SubmitComment);
						
			//显示附件 
			strTemp = objRepair.RepairFileList;
			strTemp = '20210729110710.jpg;20210729111001.jpg';
			if(strTemp!= ''){
				arrTemp = strTemp.split(';');
				for(i=0;i<arrTemp.length;i++){
				
					if(arrTemp[i] != ''){
						filename = 'file/'+arrTemp[i];
						sText = '';
						sText += '<li><div class="item">';
						sText += '<img onclick="doImgShow(\''+filename+'\')" src="'+filename+'" />';
						sText += '</div></li>';
							
						$("#idFileList").append(sText);
					}
				}
				//重新渲染
				$.parser.parse($('#idFileList').parent());					
			}
			
			
			strTemp = objRepair.SolvedName + ' on ' + objRepair.SolvedDate;
			$('#idShowFinishName').html(strTemp);
			
			//03 显示验证结果
			strTemp = objRepair.VerifyResult;
			arrTemp = strTemp.split('|');
			$('#idVerifyResult1').html(arrTemp[1]);
			$('#idVerifyResult2').html(arrTemp[2]);
			
			$('#idVerifyComment').textbox('setValue',objRepair.VerifyComment);
			
			strTemp = objRepair.VerifyName + ' on ' + objRepair.VerifyDate;
			$('#idShowVerifyName').html(strTemp);
			
			return;	
		}
		
	})

}

function doOpenMachineBody(sID){
	
	var myDiv = $('<div></div>'); 
	myDiv.attr('id','idwinMachine'); 
	$(document.body).append(myDiv); 
	 
	$('#idwinMachine').window({
		width:'95%',
		height:'60%',
		closed:true,
		iconCls:'icon-reload',
		title:'报修设备',
		cls:'c6'
	});	
	$('#idwinMachine').css('padding','5px');
	
	
	ajaxLoading('');
	sWXID = $("#idCurWXID").val();	
	//sRepairID = $("#idRepairID").val();
	
	var requestURL = "php/getMachineByMachineID.php?WXID="+sWXID+"&ID="+sID+"&";
	//alert(requestURL);
	
	$.ajax({
		url:requestURL,
		type:'GET', dataType:'text', 		
		success:function(sText,textStatus,jqXHR){
			ajaxLoadEnd();
			//alert(sText);
			
			jsonResp = JSON.parse(decodeURI(sText));
			
			if(jsonResp.errcode != 0){	
				return;										
			}
			if(jsonResp.Num == 0){
				return;
			}
			
			arrList = jsonResp.Line;
			//alert(jsonResp.Line);
			if(arrList.length == 0){
				return;
			}
			
			sLine = arrList[0];
			
			sText = '';
			sText += '<div style="margin:10px"><b>设备名称：</b>'+sLine.MachineName+' ('+sLine.MachineID+')</div>';
			sText += '<div style="margin:10px"><b>设备类型：</b>'+sLine.MachineType+'</div>';
			sText += '<div style="margin:10px"><b>设备主管：</b>'+sLine.MachineOwner+'</div>';
			sText += '<div style="margin:10px"><b>维保日期：</b>'+sLine.WarrantyDate+'</div>';
			sText += '<div style="margin:10px"><b>所在位置：</b>'+sLine.MachineLocation+'</div>';
			sText += '<div style="margin:10px"><b>备注说明：</b>'+sLine.MachineComment+'</div>';
			$('#idwinMachine').append('<div data-options="region:\'center\'" style="padding:5px;height:85%;"><div id="idMachineBodyShow">'+sText+'</div></div>');
			
			
			sText2 = '';
			sText2 += '<div data-options="region:\'south\',border:false" style="text-align:right;padding:5px 0 0;height:10px">		';	
			sText2 += '  <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" href="javascript:void(0)" onclick="javascript:$(\'#idwinMachine\').window(\'close\');" style="width:80px">Close</a>';
			sText2 += '</div>';
			$('#idwinMachine').append(sText2);
			
			$.parser.parse($('#idwinMachine'));
			$('#idwinMachine').window('open');	
			$('#idwinMachine').window('center');	
					
			return;
	
	
			
		}
		
	})
	
	
}
//04 判断处理权限,绑定事件
function doSetEvent(){

	//增加tab事件
	sStatus = $('#idRepairStatus').val();
	//alert(sStatus);
	if(sStatus == '20'){
		$('#idTab').tabs('select',0);
		$('#idTab').tabs('disableTab', 1);
		$('#idTab').tabs('disableTab', 2);			
	}else if(sStatus == '40'){
		$('#idTab').tabs('select',1);
		$('#idTab').tabs('disableTab', 2);	
	}else if(sStatus == '50'){
		$('#idTab').tabs('select',2);
	}else{
		$('#idTab').tabs('select',0);
	}
	
	
}

//显示图片窗口
function doImgShow(url){	

	if($('#idWinImgShow').length==0){	
		var myDiv = $('<div></div>'); 
		myDiv.attr('id','idWinImgShow'); 
		$(document.body).append(myDiv); 
		 
		$('#idWinImgShow').window({
			width:'95%',
			height:'60%',
			closed:true,
			closable:true,
			border:false,
			title:'',
			cls:'c0',
			tools:[{
				iconCls:'icon-add',
				handler:function(){
					alert('add');
				}
			},{
				iconCls:'icon-remove',
				handler:function(){
					alert('remove');
				}
			}]

		});	
		$('#idWinImgShow').css('padding','0px');
		
		//显示按钮
		sText2 = '';
		sText2 += '<div data-options="region:\'north\',border:false" style="text-align:right;padding:0px;height:0px">		';	
		sText2 += '  <a class="easyui-linkbutton" data-options="iconCls:\'icon-cancel\'" href="javascript:void(0)" onclick="javascript:$(\'#idWinImgShow\').window(\'close\');" style="width:30px"></a>';
		sText2 += '</div>';
		$('#idWinImgShow').append(sText2);
		
		sText1 = '';	
		sText1 += '<div data-options="region:\'center\'" style="padding:0px;text-align:center;">	';		
		sText1 += '<img id="idImgShow" src="'+url+'" ondblclick="$(\'#idWinImgShow\').window(\'close\');" style="weight:auto;height:100%" />';
		sText1 += '</div>';
		$('#idWinImgShow').append(sText1);
		
		
		
		$.parser.parse($('#idWinImgShow'));
	}
	
	//处理窗口内容
	$('#idImgShow').attr('src',url);
	
	//显示窗口
	$('#idWinImgShow').window('open');	
	$('#idWinImgShow').window('center');	
					
}

//显示完成情况的记录
function doShowFinishRecord(){
	ajaxLoading('');
	sWXID = $("#idWXID").val();	
	sID = $("#idRepairID").val();
	
	var requestURL = "php/readFinishReocrd.php?WXID="+sWXID+"&ID="+sID+"&";
	
	numRecord = 0;
	
	$.ajax({
		url:requestURL,
		type:'GET', dataType:'text', 
		error:function (XMLHttpRequest, textStatus, errorThrown) {
                ajaxLoadEnd();	
	　　　　　  alert("提交失败，请重新试试，"+textStatus);
				return;
        },
		complete:function(XMLHttpRequest,status){
	　　　　if(status=='timeout'){
	 　　　　　 ajaxLoadEnd();	
	　　　　　  alert("服务器无响应，请重新试试");
				return;
	　　　　}
　　	},
		success:function(sText,textStatus,jqXHR){
			ajaxLoadEnd();
			
			//alert(sText);
			jsonResp = JSON.parse(decodeURI(sText));
			
			if(jsonResp.errcode != 0){	
				return 0;										
			}
			
			if(jsonResp.Num == 0){
				return 0;
			}
			
			arrList = jsonResp.List;
			if(arrList.length == 0){
				return 0;
			}
			
			// {"CreatedDate":"2019-01-10 12:57:00","Status":"20","CreatedWXID":"tiger.yang@zeiss.com","CreatedName":"Tiger, Yang Hu(\u6768\u864e)",
			//"FinishText":"111","FinishResult":"222","FinishDate":"2019-01-16","FinishFee":"2019-01-16"}
			
			//清除所有记录
			$('#idShowFinishRecord').datalist('loadData',{total:0,rows:[]})
			//$('#idShowFinishRecord').datalist('deleteRow',0);
			
			for(i=0;i<arrList.length;i++){
			
				//alert(arrList[i].CreatedDate);
				numRecord++;
				
				sFrom = StringMiddle(arrList[i].CreatedName,'(',')');
				
				//显示图
				sResult = arrList[i].FinishResult
				if(sResult == 'ShowImage'){
					sHead = '【'+arrList[i].CreatedDate+'】'+sFrom;
					sBody = '';
					//增加一行
					url = 'file/'+arrList[i].FinishText;
					sText = '<a href="javascript:doImgShow(\''+url+'\')"><img class="list-image" style="width:32px;height:32px;" src="'+url+'"/>';               
					sText += '</a><div class="list-header">'+sHead+'</div>';
					sText += '<div class="list-content">'+sBody+'</div>';
					
				}else{
					sHead = '【'+arrList[i].CreatedDate+'】'+sFrom;
					sBody = '<b>完成情况</b>:'+arrList[i].FinishText;
					sBody += '<br><b>完成效果</b>：'+arrList[i].FinishResult;
					sBody += '<br><b>完成日期</b>：'+arrList[i].FinishDate;
					sBody += '&nbsp;&nbsp;<b>所费工时</b>：'+arrList[i].FinishFee+' 小时';
								
					//增加一行
					sText = '<div class="list-header">'+sHead+'</div>';
					sText += '<div class="list-content">'+sBody+'</div>';
				}				
						 
				$('#idShowFinishRecord').datalist('appendRow',{text:sText});
			}
			
			$('#idFinishRecordNum').val(numRecord);
			
			//重新渲染
			$.parser.parse($('#idShowFinishRecord').parent());
			return numRecord;	
		}
		
	})

}

//显示流转记录
function doShowWorkflow(){
	
	ajaxLoading('');
	sWXID = $("#idCurWXID").val();	
	sRepairID = $("#idRepairID").val();
	
	var requestURL = "php/readWorkflowbyID.php?WXID="+sWXID+"&ID="+sRepairID+"&";
	//alert(requestURL);
	
	$.ajax({
		url:requestURL,
		type:'GET', dataType:'text', 		
		success:function(sText,textStatus,jqXHR){
			ajaxLoadEnd();
			//alert(sText);
			jsonResp = JSON.parse(decodeURI(sText));
			
			if(jsonResp.errcode != 0){	
				return;										
			}
			if(jsonResp.Num == 0){
				return;
			}
			//alert(jsonResp.Num);
			
			arrList = jsonResp.List;
			if(arrList.length == 0){
				return;
			}
			
			//$('#idWorkflowList').datalist('getPanel').panel('clear');
			$('#idWorkflowList').datalist('loadData',{total:0,rows:[]})
			////Submit, Cancel,Approved,Finish,Solved,Return,Completed
			for(i=0;i<arrList.length;i++){
			
				
				objLine = arrList[i];
				//alert(objLine.DateTime);
				//return;
				
				//sFrom = StringMiddle(arrList[i].From,'(',')');
				//sTo = StringMiddle(arrList[i].To,'(',')');
				sFrom = objLine.UserFrom;
				sTo = objLine.UserTo;
				s1 = '【'+arrList[i].DateTime+'】'+sFrom;
				s2 = '【'+sFrom+'】 - 【'+sTo+'】';
				s21 = '';
				sLine = arrList[i];
				if(sLine.Status == '10' && sLine.Type == 'Submit'){
					s21 = '<img class="list-image" src="images/10Submit.png"/>';
					s2 = '新建，并提交给【'+sTo+'】';
				}else if(sLine.Status == '20' && sLine.Type == 'Cancel'){
					s21 = '<img class="list-image" src="images/20Cancel.png"/>';
					s2 = '关闭报修，'+sLine.Comment;
				}else if(sLine.Status == '20' && sLine.Type == 'Approved'){
					s21 = '<img class="list-image" src="images/20Approved.png"/>';
					s2 = '同意，并交给【'+sTo+'】处理';
				}else if(sLine.Status == '40' && sLine.Type == 'Finish'){
					s21 = '<img class="list-image" src="images/40Finish.png"/>';
					s2 = '填写处理记录:'+sLine.Comment.substring(0,10);
				}else if(sLine.Status == '40' && sLine.Type == 'ReturnCheck'){
					s21 = '<img class="list-image" src="images/40Return.png"/>';
					s2 = '退回协调员:【'+sTo+'】';
				}else if(sLine.Status == '40' && sLine.Type == 'Solved'){
					s21 = '<img class="list-image" src="images/40Solved.png"/>';
					s2 = '完成解决问题';
				}else if(sLine.Status == '50' && sLine.Type == 'Return'){
					s21 = '<img class="list-image" src="images/50Return.png"/>';
					s2 = '检验不合格，退回处理者:【'+sTo+'】'+sLine.Comment;
				}else if(sLine.Status == '50' && sLine.Type == 'Commpleted'){
					s21 = '<img class="list-image" src="images/50Commpleted.png"/>';
					s2 = '全部完成检验，'+sLine.Comment;
					
					
				}else{
					s2 = sLine.Status + sLine.Type;
				}
				
				
				
				//增加一行
				sText = s21+'<div class="list-header">'+s1+'</div>';
				sText += '<div class="list-content">'+s2+'</div>';
						 
				$('#idWorkflowList').datalist('appendRow',{text:sText});	
				
			}
			//重新渲染
			$.parser.parse($('#idWorkflowList').parent());
			return;	
		}
		
	})

}

//打开拒绝，回退窗口
function openWinCancel(sFunction,sTitle,sComment,sPrompt=''){	

	if(sPrompt == ''){
		sPrompt = sComment;
	}

	winName = 'idWinCancel'
	if($('#'+winName).length==0){	
		var myDiv = $('<div></div>'); 
		myDiv.attr('id',winName); 
		$(document.body).append(myDiv); 
		 
		$('#'+winName).window({
			width:'95%',
			height:'300px',
			closed:true,
			closable:true,
			iconCls:'icon-undo',
			title:sTitle,
			cls:'c3',
		});	
		$('#'+winName).css('padding','0px');
		
		//窗口内容
		sText1 = '';
		sText1 += '<div data-options="region:\'center\'" style="padding:5px;">';	
		sText1 += '	<ul class="m-list" data-options="';	
		sText1 += '			fit: true,';	
		sText1 += '			lines: true,';	
		sText1 += '			border: false">';	
		sText1 += '		<li class="m-list-group">◆'+sComment+'◆</li>	';					
		sText1 += '		<li>				';	
		sText1 += '		<div style="margin:5px">';	
		sText1 += '			 <input class="easyui-textbox" id="idCancelComment" style="width:99%;height:120px" ';	
		sText1 += '			 data-options="multiline:true,prompt:\''+sPrompt+'\',">';	
		sText1 += '		</div>';	
		sText1 += '		<br>';	
		sText1 += '		</li>';	
		sText1 += '	</ul>';	
		sText1 += '</div>';			
		$('#'+winName).append(sText1);
		
		//显示按钮
		sText2 = '';
		sText2 += '<div data-options="region:\'south\',border:false" style="text-align:right;padding:5px;height:10px">		';	
		sText2 += '  <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" href="javascript:'+sFunction+'()" style="width:100px;padding:5px;"> 确认 </a>&nbsp;';
		sText2 += '  <a class="easyui-linkbutton" data-options="iconCls:\'icon-cancel\'" href="javascript:void(0)" onclick="javascript:$(\'#'+winName+'\').window(\'close\');" style="width:100px;padding:5px;"> 取消 </a>';
		sText2 += '</div>';
		$('#'+winName).append(sText2);
		
		//刷新显示
		$.parser.parse($('#'+winName));
	}	
	
	//显示窗口
	$('#'+winName).window('open');	
	$('#'+winName).window('center');	
					
}

//打开流转记录窗口
function openWinWorkflow(){	

	winName = 'idWinWorkflow'
	if($('#'+winName).length==0){	
		var myDiv = $('<div></div>'); 
		myDiv.attr('id',winName); 
		$(document.body).append(myDiv); 
		 
		$('#'+winName).window({
			width:'95%',
			closed:true,
			closable:true,
			iconCls:'icon-reload',
			title:'流转记录',
			cls:'c1',
		});	
		$('#'+winName).css('padding','0px');
		$('#'+winName).css('height','500px');
		
		//窗口内容
		sText1 = '';
		sText1 += '<div data-options="region:\'center\'" style="padding:5px;height:89%;">';	
		sText1 += '<ul id="idWorkflowList" class="easyui-datalist" style="" data-options="';		
		sText1 += '						height:\'100%\',rownumbers: true, fit: false,';		
		sText1 += '						lines: true,border: false,nowrap:false">';
		sText1 += '</ul>';		
		sText1 += '</div>';			
		$('#'+winName).append(sText1);
		
		//显示按钮
		sText2 = '';
		sText2 += '<div data-options="region:\'south\',border:false" style="text-align:right;padding:5px;height:10px">		';	
		sText2 += '  <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" href="javascript:void(0)" onclick="javascript:$(\'#'+winName+'\').window(\'close\');" style="width:100px;"> 关闭 </a>';
		sText2 += '</div>';
		$('#'+winName).append(sText2);
		
		//刷新显示
		$.parser.parse($('#'+winName));
	}	
	
	//显示窗口
	$('#'+winName).window('open');	
	$('#'+winName).window('center');	
	doShowWorkflow();
					
}


//上传图片
function uploadImage(){
	
	//正在上传
	ajaxLoading('');
	sWXID = $('#idCurWXID').val();	
	var formData = new FormData();  
	formData.append('WXID',sWXID);
	formData.append('UploadType','ImageSubmit');	
	formData.append('FileUpload',document.getElementById('filebox_file_id_1').files[0]);
		　　　　　　　
    $.ajax({
		url: 'php/uploadImage.php',　　　　　　　　　　//上传地址
		type: 'POST',
		cache: false,　　
		data: formData,　　　　　　　　　　　　　//表单数据
		processData: false,
		contentType: false,
		dataType:'text', 
		success:function(sText){
			ajaxLoadEnd();	
			alert(sText);
			objJson = JSON.parse(decodeURI(sText));
			
			// {"errcode":0,"errmsg":"OK","FileName":"fileupload/aaaa_152834102283.png"}
				
			if(objJson.errcode != 0){
				alert('上传错误：'+objJson.errmsg);
				return;
			}
			
			$('#idFileUpload').val(objJson.FileName);
			
			
		}
	});    
}






