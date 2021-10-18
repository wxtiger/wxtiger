
//Ajax请求,
function wxGetInit(sOfficial,sType='Insider'){
	
	
   
	url = encodeURI(location.href);	
	
	intTemp = url.indexOf("?");
	if(intTemp>0){
		sUrl = url.substring(0,intTemp);
	}else{
		sUrl = url;
	}
	//alert(sUrl);
	
	var requestURL = "/weixin/system/class/wxjssdk/wxPageInit.php?OfficialType="+sType+"&Official="+sOfficial+"&URL="+location.href+"&";
	
	$.ajax({
		url:requestURL,
		type:'GET', //GET
		async:true,    //或false,是否异步
		timeout:5000,    //超时时间
		dataType:'json', 		
		success:function(jsonResp,textStatus,jqXHR){
			$("#idWXappId").val(jsonResp.appId);
					
			wx.config({
				debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
				appId: jsonResp.appId, 			// 必填，企业号的唯一标识，此处填写企业号corpid
				timestamp:jsonResp.timestamp , 	// 必填，生成签名的时间戳
				nonceStr: jsonResp.nonceStr, 	// 必填，生成签名的随机串
				signature: jsonResp.signature,	// 必填，签名，见附录1
			   // jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
				jsApiList: [
					'checkJsApi',
					'onMenuShareTimeline',
					'onMenuShareAppMessage',
					'onMenuShareQQ',
					'onMenuShareWeibo',
					'hideMenuItems',
					'showMenuItems',
					'hideAllNonBaseMenuItem',
					'showAllNonBaseMenuItem',
					'translateVoice',
					'startRecord',
					'stopRecord',
					'onRecordEnd',
					'playVoice',
					'pauseVoice',
					'stopVoice',
					'uploadVoice',
					'downloadVoice',
					'chooseImage',
					'previewImage',
					'uploadImage',
					'downloadImage',
					'getNetworkType',
					'openLocation',
					'getLocation',
					'hideOptionMenu',
					'showOptionMenu',
					'closeWindow',
					'scanQRCode',
					'chooseWXPay',
					'openProductSpecificView',
					'addCard',
					'chooseCard',
					'openCard'
				  ]
			});

			wx.ready(function(){
				//隐藏右上角菜单接口
				//wx.hideOptionMenu();
				$("#idWXSignOK").val(jsonResp.signature);
				
				wx.onMenuShareAppMessage({
				  title: $("#idWXShareTitle").val(), // 分享标题
				  desc: $("#idWXShareComment").val(), // 分享描述
				  link: $("#idWXSharePageUrl").val(), // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				  imgUrl: $("#idWXShareImgUrl").val(), // 分享图标
				  success: function () {
					// 用户点击了分享后执行的回调函数
				  }
				});
				
				wx.onMenuShareTimeline({
				  title: $("#idWXShareTitle").val(), // 分享标题
				  link: $("#idWXSharePageUrl").val(), // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				  imgUrl: $("#idWXShareImgUrl").val(), // 分享图标
				  success: function () {
				  // 用户点击了分享后执行的回调函数
				  }
				});

				wx.updateAppMessageShareData({ 
					title: $("#idWXShareTitle").val(), // 分享标题
					desc: $("#idWXShareComment").val(), // 分享描述
					link: $("#idWXSharePageUrl").val(), 
					imgUrl: $("#idWXShareImgUrl").val(), // 分享图标
					success: function () {
					  
					}
				});
				
				wx.updateTimelineShareData({ 
					title: $("#idWXShareTitle").val(), // 分享标题
					link: $("#idWXSharePageUrl").val(), // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
					imgUrl: $("#idWXShareImgUrl").val(), // 分享图标
					success: function () {
					  // 设置成功
					}
				});
  
			});
			wx.error(function(res){
				$("#idWXSignOK").val('');
			});

		}		
	})
}

			
		



// 3 智能接口
var voice = {
	localId: '',
    serverId: ''
};
// 3.1 识别音频并返回识别结果
function doWXRecordText(obj){
	//obj 是保存文本内容的目标字段对象， document.getElementById("idText")
    if(voice.localId == ''){
		alert('请先录制一段声音');
		return;
    }
    wx.translateVoice({
		localId: voice.localId,
		isShowProgressTips: 1, // 默认为1，显示进度提示
		complete: function (res){
			if (res.hasOwnProperty('translateResult')){
				$("#idWXRecordText").val(res.translateResult);
				if(obj !=null) obj.value += res.translateResult;
				return res.translateResult;
			} else {
				//alert('无法识别');
				$("#idWXRecordText").val("");
				return "";
			}
		}
    });
};

// 4 音频接口 
// 4.2 开始录音
function doWXRecordStart(){
    wx.startRecord({
		cancel: function (){
			alert('用户拒绝授权录音');
		}
    });
	
	// 4.4 监听录音自动停止
	wx.onVoiceRecordEnd({
		complete: function (res){
			voice.localId = res.localId;
			$("#idWXRecordLocalID").val(res.localId);
			alert('录音时间超过一分钟');
		}
	});
};

// 4.3 停止录音
function doWXRecordStop(){
    wx.stopRecord({
		success: function (res){
			voice.localId = res.localId;
			$("#idWXRecordLocalID").val(res.localId);
			
		},
		fail: function (res){
			alert(JSON.stringify(res));
		}
    });
	
	
};

// 4.5 播放音频
function doWXRecordPlayStart(){
    if (voice.localId == ''){
		alert('请先录制一段声音');
		return;
    }	
    wx.playVoice({
		localId: voice.localId
    });
	
	// 4.8 监听录音播放停止
	wx.onVoicePlayEnd({
		complete: function (res){
      //alert('录音（' + res.localId + '）播放结束');
		}
	});
  
};

// 4.6 暂停播放音频
function doWXRecordPlayPause(){
    wx.pauseVoice({
		localId: voice.localId
	});
};

// 4.7 停止播放音频
function doWXRecordPlayStop(){
    wx.stopVoice({
		localId: voice.localId
    });
};


// 4.8 上传语音
function doWXRecordUpload(sTips=1){
    if (voice.localId == ''){
		alert('请先录制一段声音');
		return;
    }
    wx.uploadVoice({
		localId: voice.localId,
		isShowProgressTips: sTips, // 默认为1，显示进度提示
		success: function (res){
			//alert('上传语音成功，serverId 为' + res.serverId);
			alert('上传语音成功');
			voice.serverId = res.serverId;
			$("#idWXRecordServerID").val(res.serverId);
		}
    });
};

/*

  // 4.9 下载语音
  function  doWXRecordDownload(){
    if (voice.serverId == ''){
      alert('请先使用 uploadVoice 上传声音');
      return;
    }
    wx.downloadVoice({
      serverId: voice.serverId,
      success: function (res){
        alert('下载语音成功，localId 为' + res.localId);
        voice.localId = res.localId;
      }
    });
  };
*/

  // 5 图片接口
  // 5.1 拍照、本地选图
var images = {
    localId: [],
    serverId: []
};

function doWXImgSelect(sCount=1,sType='compressed',sSource=['album', 'camera']){
	//alert(wx);
    wx.chooseImage({	
		count: sCount, // 默认9
		sizeType:sType,	// ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
		sourceType:sSource,	// ['album', 'camera'],
  
		success: function (res){
			images.localId = res.localIds;
			$("#idWXImageLocalID").val(res.localIds);
			doWXImgUpload();
		}
    });
};

//删除附件，取消上传
function doWXImgDelete(){
	images.localId = "";
	images.serverId = "";
	$("#idWXImageLocalID").val("");
	$("#idWXImageServerID").val("");
}

  // 5.2 图片预览
  function doWXImgShow(){
	imgUrl = $("#idWXImageLocalID").val();
	if(imgUrl == ""){
		alert("请先选择图片")
            return;
        }
	arrUrl = imgUrl.split(",");
	wx.previewImage({
		current:arrUrl[0],
		urls: arrUrl
	});
  };

// 5.3 上传图片
function doWXImgUpload(){
	if (images.localId.length == 0){
		alert('请先选择图片');
		return;
	}
	var i = 0, length = images.localId.length;
	images.serverId = [];
	function upload(){
		wx.uploadImage({
			localId: images.localId[i],
			isShowProgressTips: 1, 	// 默认为1，显示进度提示
			success: function (res){
				i++;
				//alert('已上传：' + i + '/' + length);
				images.serverId.push(res.serverId);
				if (i < length){
					upload();
				}else{
					//上传完成以后的处理代码，微信服务器上的图片ID：images.serverId[0]
					$("idWXImageServerID").val(images.serverId);	
				}
			},
			fail: function (res){
				alert(JSON.stringify(res));
				$("idWXImageServerID").val('');
			}
		});
	}
	upload();
};

