<?php
/**
 * 修改信息
**/
$mod='blank';
include("../api.inc.php");
$title='修改信息';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
if(isset($_POST['submit'])) {
$user=daddslashes($_POST['user']);
$qq=daddslashes($_POST['qq']);
$pass=daddslashes($_POST['pass']);
$sql="update `auth_user` set `user` ='{$user}',`qq` ='{$qq}' where `uid`='{$udata['uid']}'";
if(!empty($pass))$DB->query("update auth_user set pass='$pass' where uid='{$udata['uid']}'");
if($DB->query($sql)){
	setcookie("admin_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('修改成功，请重新登陆！');window.location.href='../login.php';</script>");
}else{
	showmsg('修改失败！<br/>'.$DB->error(),4,$_POST['backurl']);
	exit();
	}
}
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">修改信息</h3></div>
        <div class="panel-body">
          <form action="./pass.php" method="post" class="form-horizontal" role="form">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="user" value="<?=$udata['user']?>" class="form-control" placeholder="新账号">
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="qq" value="<?=$udata['qq']?>" class="form-control" placeholder="新QQ">
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
              <input type="text" name="pass" value="" class="form-control" placeholder="不修改请留空">
            </div><br/>
            <div class="form-group">
              <div class="col-xs-12"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>