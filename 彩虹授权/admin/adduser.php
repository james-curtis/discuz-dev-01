<?php
/**
 * 添加用户
**/
$mod='blank';
include("../api.inc.php");
$title='添加用户';
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
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
if(isset($_POST['user']) && isset($_POST['pass'])&& isset($_POST['qq'])){
$user=daddslashes($_POST['user']);
$qq=daddslashes($_POST['qq']);
$row=$DB->get_row("SELECT * FROM auth_user WHERE user='{$user}' limit 1");
if($row) {
	showmsg('用户名已存在',3);
	exit;
}
$pass=daddslashes($_POST['pass']);
$per=daddslashes($_POST['per']);
if($per=="1"){
	$per_sq=1;
	$per_db=1;
	$active=1;
}else if($per=="2"){
	$per_sq=0;
	$per_db=1;
	$active=1;
}else if($per=="3"){
	$per_sq=1;
	$per_db=0;
	$active=1;
}else if($per=="4"){
	$per_sq=0;
	$per_db=0;
	$active=0;
	}
	$sql="insert into `auth_user` (`user`,`pass`,`per_sq`,`per_db`,`active`,`qq`) values ('".$user."','".$pass."','".$per_sq."','".$per_db."','".$active."','".$qq."')";
	$DB->query($sql);
	$city=get_ip_city($clientip);
		$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$udata['user']."','添加用户','".$date."','".$city."','新用户名".$user."|授权".$per_sq."|获取".$per_db."|状态".$active."')");

exit("<script language='javascript'>alert('添加用户成功！');window.location.href='userlist.php';</script>");
} 
				if(($udata['uid'])=="1"){$all='	<option value="1">超级管理员</option>';}
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">添加用户</h3></div>
          <div class="panel-body">
          <form action="./adduser.php" method="post" class="form-horizontal" role="form">
		  <input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"/>
            <div class="input-group">
              <span class="input-group-addon">用户名:</span>
              <input type="text" name="user" value="<?=@$_POST['user']?>" class="form-control" placeholder="" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">密码:</span>
              <input type="password" name="pass" value="<?=@$_POST['pass']?>" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">QQ:</span>
              <input type="text" name="qq" value="<?=@$_POST['qq']?>" class="form-control" autocomplete="off" required/>
            </div><br/>
			<div class="input-group">
			  <span class="input-group-addon">权限:</span>
			  <select name="per" class="form-control">
				<?php echo $all;?>
					<option value="2">代理商权限</option>
					<option value="3">获取信息权限</option>
					<option value="4">封禁该用户</option>
              </select>
            </div><br/>
            <div class="form-group">
              <div class="col-sm-12"><input type="submit" value="添加" class="btn btn-primary form-control"/></div>
            </div>
          </form>
        <div class="panel-footer">
          <span class="glyphicon glyphicon-info-sign"></span> 高级管理员权限才可以管理其他用户
        </div>
      </div>
    </div>
  </div>