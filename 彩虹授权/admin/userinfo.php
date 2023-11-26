<?php
/**
 * 个人资料
**/
$mod='blank';
include("../api.inc.php");
$title='个人资料';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">个人资料</h3></div>
        <div class="panel-body">
		<div class="form-group">
		<div class="input-group"><div class="input-group-addon">UID</div>
			<input type="text" class="form-control" name="user" value="<?=$udata['uid']?>" disabled>
		</div></div>
		<div class="form-group">
		<div class="input-group"><div class="input-group-addon">账号</div>
			<input type="text" class="form-control" name="user" value="<?=$udata['user']?>" disabled>
			<div class="input-group-addon"><a href="./pass.php">修改</a></div>
		</div></div>
		<div class="form-group">
		<div class="input-group"><div class="input-group-addon">密码</div>
			<input type="password" class="form-control" name="user" value="<?=$udata['pass']?>" disabled>
			<div class="input-group-addon"><a href="./pass.php">修改</a></div>
		</div></div>
		<div class="form-group">
		<div class="input-group"><div class="input-group-addon">QQ</div>
			<input type="text" class="form-control" name="qq" value="<?=$udata['qq']?>" placeholder="用于显示头像" disabled>
			<div class="input-group-addon"><a href="./pass.php">修改</a></div>
		</div>
		</div>
</div></div>