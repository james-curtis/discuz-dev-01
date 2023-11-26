<?php
$mod='blank';
include("../../api.inc.php");
?>
<!DOCTYPE html>
<head>
<meta name="baidu-site-verification" content="hE6VuDTDDZ" />
<meta name='zyiis_check_verify' content='8522ccc657813a5aa350dbc60e15ac37'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
<meta name="format-detection" content="telephone=no">
<meta charset="UTF-8">
<meta name="description" content="Violate Responsive Admin Template">
<meta name="keywords" content="Super Admin, Admin, Template, Bootstrap">
<title>正版查询</title>
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
<form action="?" method="get">
<div class="form-group">
<label for="exampleInputEmail1">请输入查询域名 不带https://</label>
<input type="text" class="form-control input-sm" name="url"
 placeholder='请输入需要查询的域名'>
</div>
<div class="form-group">
<label for="exampleInputPassword1">
</label>
</div>
<p>www.idcsm.com</p>
<div class="fileupload fileupload-new" data-provides="fileupload">
<button type="Submit" href="#" class="btn btn-block btn-alt">查询</button>
<?php
if($url=$_GET['url']) {
	echo '<label>查询域名：</label>'.$url.'<br>';
	if(checkauth2($url)) {
		echo '<div class="alert alert-success"><img src="static/ico_success.png">查询结果：正版授权！</div>';
	}else{
		echo '<div class="alert alert-danger"><img src="static/ico_tip.png">查询结果：该网站未授权！</div>';
	}
}
$DB->close();
?>
</div>
<a data-toggle="modal" href="./user/login.php" class="btn btn-sm m-t-10" target="_blank">用户登录</a>
<a data-toggle="modal" href="./kmsq.php" class="btn btn-sm m-t-10">卡密授权</a>
<a href="./dlcx.php" class="btn btn-sm m-t-10">授权商查询</a>
<a data-toggle="modal" href="http://wpa.qq.com/msgrd?v=3&uin=1503816935&site=qq&menu=yes" class="btn btn-sm m-t-10" target="_blank">小杰</a>
<a data-toggle="modal" href="http://wpa.qq.com/msgrd?v=3&uin=760611885&site=qq&menu=yes" class="btn btn-sm m-t-10" target="_blank">白云</a>
<iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=298 height=52 src="//music.163.com/outchain/player?type=0&id=982689732&auto=0&height=32"></iframe></form>
<form><a data-toggle="modal" href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-sm m-t-10" target="_blank">加入Q群</a></form>
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