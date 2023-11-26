function getqrpic(){
	var getvcurl='login.php?do=getqrpic&r='+Math.random(1);
	$.get(getvcurl, function(d) {
		if(d.saveOK ==0){
			$('#qrimg').attr('qrsig',d.qrsig);
			$('#qrimg').html('<img onclick="getqrpic()" src="data:image/png;base64,'+d.data+'" title="点击刷新">');
		}else{
			alert(d.msg);
		}
	});
}
function ptuiCB(code,uin,sid,skey,pskey,pskey2,nick){
	var msg='请扫描二维码';
	switch(code){
		case '0':
			$('#login').html('<div class="alert alert-success">QQ验证成功！'+decodeURIComponent(nick)+'</div><br/><a href="../download_get.php?my=updater&qq='+uin+'" target="_blank">下载更新包</a><br/><a href="../download_get.php?my=installer&qq='+uin+'" target="_blank">下载安装包</a>');
			$('#qrimg').hide();
			$('#submit').hide();
			$('#login').attr("data-lock", "true");
			//var url="/user/addqq2?uin="+a+"&skey="+b+"&cookie="+c+"&r="+Math.random(1);
			//loadScript(url);
			break;
		case '1':
			getqrpic();
			//document.getElementById('loginpic').src='/qlogin/captcha.php?do=ptqrshow&appid=549000929&e=2&l=M&s=3&d=72&v=4&daid=147&t='+Math.random(1);
			alert('请重新扫描二维码');
			msg='请重新扫描二维码';
			break;
		case '2':
			alert('请使用QQ手机版扫描二维码后再点击验证');
			msg='使用QQ手机版扫描二维码';
			break;
		case '3':
			alert('扫码成功，请在手机上确认授权登录');
			msg='扫码成功，请在手机上确认授权登录';
			break;
		case '4':
			alert('你的QQ未通过验证，请使用购买授权的QQ扫码！');
			msg='你的QQ未通过验证，请使用购买授权的QQ扫码！';
			break;
		case '5':
			alert('QQ验证失败，请解除登录异常后重试！');
			msg='QQ验证失败，请解除登录异常后重试！';
			break;
		default:
			msg=sid;
			break;
	}
	$('#loginmsg').html(msg);
}
function loadScript(c) {
	if ($('#login').attr("data-lock") === "true") return;
	var qrsig=$('#qrimg').attr('qrsig');
	c = c || "login.php?do=qqlogin&qrsig="+decodeURIComponent(qrsig)+"&r=" + Math.random(1);
	var a = document.createElement("script");
	a.onload = a.onreadystatechange = function() {
		if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
			if (typeof d == "function") {
				d()
			}
			a.onload = a.onreadystatechange = null;
			if (a.parentNode) {
				a.parentNode.removeChild(a)
			}
		}
	};
	a.src = c;
	document.getElementsByTagName("head")[0].appendChild(a)
}
function loginload(){
	if ($('#login').attr("data-lock") === "true") return;
	var load=document.getElementById('loginload').innerHTML;
	var len=load.length;
	if(len>2){
		load='.';
	}else{
		load+='.';
	}
	document.getElementById('loginload').innerHTML=load;
}
$(document).ready(function(){
	getqrpic();
	$('#submit').click(function(){
		loadScript();
	});
	//window.setInterval(loginload,1000);
	//window.setInterval(loadScript,3000);
});