<?php
/**
 * 域名防红
**/
$mod='blank';
include("../api.inc.php");
$title='域名防红';
include './head.php';
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="panel panel-default">
<div class="panel-heading">
<h2 class="panel-title">域名防红</h2>
</div>
<li class="list-group-item">
<div class="input-group">
<span class="input-group-addon">我的网址</span><input style="background: rgba(255, 251, 251, 0.7)" class="form-control" id="longurl" value="http://<?php echo $user['url']?>" />
			</div><br/>
      <div class="well well-sm">如果您的网址在QQ报毒，您可以使用此功能生成防毒短链接！</div>
              <button type="button" class="btn btn-info form-control" id="start">立即生成我的防红链接</button>
			<li class="list-group-item" role="alert" id="dwzdate"><center></center></li>
</li>
			<script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('#start').click(function(){
		$.ajax({
			type:"post",
			url: "http://www.sz267.cn/fzdd/dwz.php",
			dataType: "json",
			data:"longurl="+$("input[id='longurl']").val(),
			async:true,
			success: function(a) {
				console.log(a); 
  				var strJson = JSON.stringify(a) 
				var obj = $.parseJSON(strJson);
				$("#dwzdate").html('<center><font color="#FF0000">你的防毒地址：' +obj.sina_url+'</center></li>');
				$("#content").val(obj.sina_url);
            },
			error: function(a) { 
				alert("失败！！");
			}
		});
	});
});
</script>
<?php 
$arr = range(1, 19);
shuffle($arr);
foreach ($arr as $values) {
}
?>
<style type="text/css">
<!-- body { background-image: url(http://fucktencentcloud.duapp.com/img/<?php 
echo $values;
?>.jpg); background-size:cover-repeat;background-attachment:fixed; } -->
</style>  
<script src="qrcode.js"></script>
<script type="text/javascript">
window.onload = function(){

    // 二维码对象
    var qrcode;

    // 默认设置
    var content;
    var size;

    // 设置点击事件
    document.getElementById("send").onclick =function(){
        
        // 获取内容
        content = document.getElementById("content").value;
        content = content.replace(/(^\s*)|(\s*$)/g, "");

        // 获取尺寸
        size = document.getElementById("size").value;

        // 检查内容
        if(content==''){
            alert('请输入内容！');
            return false;
        }

        // 检查尺寸
        if(!/^[0-9]*[1-9][0-9]*$/.test(size)){
            alert('请输入正整数');
            return false;
        }

        if(size<100 || size>500){
            alert('尺寸范围在100～500');
            return false;
        }

        // 清除上一次的二维码
        if(qrcode){
            qrcode.clear();
        }

        // 创建二维码
        qrcode = new QRCode(document.getElementById("qrcode"), {
            width : size,//设置宽高
            height : size
        });

        qrcode.makeCode(document.getElementById("content").value);
    }

}
</script>  
</head>
<div class="panel panel-default">
<div class="panel-heading">
<h2 class="panel-title">防毒二维码生成器</h2>
</div>
<span class="input-group-addon" style="background: rgba(255, 251, 251, 0.7)"><p>网址：<input type="text" id="content" value="http://" /></p>
    <p>尺寸：<input type="text" id="size" value="150"></p>
      <button type="button" class="btn btn-info form-control" id="send">生成二维码</button>
<p><p><center>  
    <div id="qrcode"></div>
</center>
            </div>
        
</body>
</html>