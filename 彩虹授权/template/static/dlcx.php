<?php
$mod='blank';
include("../../api.inc.php");
?>
<!DOCTYPE html>
<!--[if IE 9 ]>
<html class="ie9">
<![endif]-->
<head>
<meta name="baidu-site-verification" content="hE6VuDTDDZ" />
<meta name='zyiis_check_verify' content='8522ccc657813a5aa350dbc60e15ac37'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
<meta name="format-detection" content="telephone=no">
<meta charset="UTF-8">
<meta name="description" content="Violate Responsive Admin Template">
<meta name="keywords" content="Super Admin, Admin, Template, Bootstrap">
<title>授权商查询</title>
<meta name="keywords" content="授权商验证系统"/>
<meta name="description" content="授权商验证系统"/>
 <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link href="static/css/music.css" rel="stylesheet">
<link href="static/css/bootstrap.min.css" rel="stylesheet">
<link href="static/css/animate.min.css" rel="stylesheet">
<link href="static/css/font-awesome.min.css" rel="stylesheet">
<link href="static/css/form.css" rel="stylesheet">
<link href="static/css/calendar.css" rel="stylesheet">
<link href="static/css/file-manager.css" rel="stylesheet">
<link href="static/css/style.css" rel="stylesheet">
<link href="static/css/icons.css" rel="stylesheet">
<link href="static/css/generics.css" rel="stylesheet">
<STYLE>.loginscreen.middle-box{width:300px;}.middle-box{max-width:400px;z-index:100;margin:0 auto;padding-top:100px;}</STYLE>
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "https://hm.baidu.com/hm.js?0de8a795db3f2bcc6c9c374ab7468d22";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body id="skin-blur-violate">
<section class="middle-box " align="center">
<div class="tile p-15">
<div class="container">
<img id="btnAudio" class="profile-pic animated" src="https://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=760611885&fid=blog&spec=100" alt="">
<form action="?" class="form-sign" method="get">
<div class="form-group">
<div class="panel-heading">请输入QQ进行查询</div>
<div class="panel-body">
比如：小杰1503816935、白云760611885
<input type="text" class="form-control" name="qq" placeholder="请输入QQ进行查询" value=""><br>
</div>
<div class="form-group">
<label for="exampleInputPassword1">
</label>
</div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<button type="Submit" href="#" class="btn btn-block btn-alt">查询</button>
<?php
if($qq=$_GET['qq']) {
	$qq=$_GET['qq'];
	$row=$DB->get_row("SELECT * FROM auth_user WHERE qq='$qq' limit 1");
	echo '<label>查询QQ：</label>'.$qq.'�<a href="tencent://message/?uin='.$qq.'&Site=授权平台&Menu=yes"><img src="https://wpa.qq.com/pa?p=2:'.$qq.':41" border=0></a><br>';
	if($row) {
		echo '<div class="alert alert-success"><img src="static/ico_success.png">查询结果：正版授权商，请放心交易！</div>';
	}else{
		echo '<div class="alert alert-danger"><img src="static/ico_tip.png">查询结果：盗版授权商，请谨慎交易！</div>';
	}
}
$DB->close();
?>
</div>
<a data-toggle="modal" href="javascript:history.back(-1)" class="btn btn-sm m-t-10">返回上一页</a>
<a data-toggle="modal" href="/kmsq.php" class="btn btn-sm m-t-10">卡密授权</a>
<a href="./dlcx.php" class="btn btn-sm m-t-10">授权商查询</a>
<a href="./pay.php" target="_blank" class="btn btn-sm m-t-10">购买授权</a>
</form>
</div>
<script src="static/js/jquery.min.js"></script>  
<script src="static/js/jquery-ui.min.js"></script>  
<script src="static/js/bootstrap.min.js"></script>
<script src="static/js/scroll.min.js"></script>  
<script src="static/js/calendar.min.js"></script>  
<script src="static/js/feeds.min.js"></script>  
<script src="static/js/elfinder.min.js"></script>  
<script src="static/js/music.js"></script>
</body>
</html>