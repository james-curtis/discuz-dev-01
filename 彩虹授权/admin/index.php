<?php
/**
 * 授权平台
**/
$mod='blank';
include("../api.inc.php");
$title='白云&小杰授权平台';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='/login.php';</script>");
?>
<?php
$sites=$DB->count("SELECT count(*) from auth_site WHERE 1");
$blocks=$DB->count("SELECT count(*) from auth_block WHERE 1");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">白云&小杰授权平台首页</h3></div>
          <ul class="list-group">
            <li class="list-group-item"><span class="glyphicon glyphicon-stats"></span> <b>站点统计：</b> 正版:<?=$sites?>，盗版:<?=$blocks?></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-time"></span> <b>登录时间：</b> <?=$udata['last']?></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-heart"></span> <b>用户权限：</b><?=$udata['per_sq']==1?'超级管理员':'代理商'?></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-user"></span> <b>代理商QQ：</b> <?=$udata['qq']?></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-tint"></span> <b>代理商UID：</b> <?=$udata['uid']?></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-list"></span> <b>功能菜单：</b> 
              <a href="./add.php" class="btn btn-xs btn-success">添加授权</a>
              <a href="./addsite.php" class="btn btn-xs btn-success">添加站点</a>
              <a href="./userinfo.php" class="btn btn-xs btn-primary">个人资料</a>
              <a href="./pass.php" class="btn btn-xs btn-danger">修改密码</a>
            </li>
          </ul>
      </div>
    </div>
  </div>