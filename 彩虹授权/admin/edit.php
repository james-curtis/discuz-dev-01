<?php
/**
 * 编辑授权
**/
$mod='blank';
include("../api.inc.php");
$title='编辑授权';
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
if($_GET['my']=='edit') {
$id=intval($_GET['id']);
$row=$DB->get_row("SELECT * FROM auth_site WHERE id='{$id}' limit 1");
if($row=='')exit("<script language='javascript'>alert('授权平台不存在该记录！');history.go(-1);</script>");
if(isset($_POST['submit'])) {
	$uid=daddslashes($_POST['uid']);
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$rmb=daddslashes($_POST['rmb']);
	$vip=daddslashes($_POST['vip']);
	$url=daddslashes($_POST['url']);
	$authcode=daddslashes($_POST['authcode']);
	$sign=daddslashes($_POST['sign']);
	$active=intval($_POST['active']);
	if(strlen($authcode)!=32)showmsg('授权码格式错误！');
	else{
		$sql="update `auth_site` set `uid` ='{$uid}',`user` ='{$user}',`pass` ='{$pass}',`rmb` ='{$rmb}',`vip` ='{$vip}',`url` ='{$url}',`authcode` ='{$authcode}',`sign` ='{$sign}',`active` ='{$active}' where `id`='{$id}'";
		if($DB->query($sql))showmsg('修改成功！',1,$_POST['backurl']);
		else showmsg('修改失败！<br/>'.$DB->error(),4,$_POST['backurl']);
	}
}else{
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">编辑授权</h3></div>
          <div class="panel-body">
          <form action="./edit.php?my=edit&id=<?php echo $id; ?>" method="post" class="form-horizontal" role="form">
		  <input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"/>
            <div class="input-group">
              <span class="input-group-addon">授权ＱＱ</span>
              <input type="text" name="uid" value="<?php echo $row['uid']; ?>" class="form-control" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">账号</span>
              <input type="text" name="user" value="<?php echo $row['user']; ?>" class="form-control" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">密码</span>
              <input type="text" name="pass" value="<?php echo $row['pass']; ?>" class="form-control" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">授权余额</span>
              <input type="text" name="rmb" value="<?php echo $row['rmb']; ?>" class="form-control" required/>
            </div><br/>
			<div class="input-group">
			  <span class="input-group-addon">权限:</span>
			  <select class="form-control" name="vip" default="<?php echo $row['vip']; ?>">
			  <?php if($row['vip']==0){?>
			  <option value="0">普通代理</option>
			  <option value="1">钻石代理</option>
			  <option value="2">至尊代理</option>
			  <?php }elseif($row['vip']==1){?>
			  <option value="1">钻石代理</option>
			  <option value="2">至尊代理</option>
			  <?php }elseif($row['vip']==2){?>
			  <option value="2">至尊代理</option>
			  <?php }?>
              </select>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">授权域名</span>
              <input type="text" name="url" value="<?php echo $row['url']; ?>" class="form-control" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">授权码</span>
              <input type="text" name="authcode" value="<?php echo $row['authcode']; ?>" class="form-control"/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">特征码</span>
              <input type="text" name="sign" value="<?php echo $row['sign']; ?>" class="form-control"/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">授权状态</span>
              <select name="active" class="form-control">
				<?php if($row['active']==1){?>
                <option value="1">1_激活</option>
                <option value="0">0_封禁</option>
				<?php }else{?>
				<option value="0">0_封禁</option>
				<option value="1">1_激活</option>
				<?php }?>
              </select></div>
            </div><br/>
             <input type="submit" name="submit" value="修改" class="btn btn-primary btn-block"/><br/>
             <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">>>返回授权列表</a></div></div>
<?php
}
}elseif($_GET['my']=='del'){
	$id=intval($_GET['id']);
	$row=$DB->get_row("SELECT * FROM auth_site WHERE id='{$id}' limit 1");
	$sql="DELETE FROM auth_site WHERE id='$id' limit 1";
	if($DB->query($sql)){showmsg('删除成功！',1,$_SERVER['HTTP_REFERER']);
		$city=get_ip_city($clientip);
		$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$user."','删除站点','".$date."','".$city."','".$row['uid']."|".$row['url']."|".$row['authcode']."|".$row['sign']."')");
	}
	else showmsg('删除失败！<br/>'.$DB->error(),4,$_SERVER['HTTP_REFERER']);
}elseif($_GET['my']=='delpirate'){
	$url=daddslashes($_GET['url']);
	$sql="DELETE FROM auth_block WHERE url='$url' limit 1";
	if($DB->query($sql))showmsg('删除成功！',1,$_SERVER['HTTP_REFERER']);
	else showmsg('删除失败！<br/>'.$DB->error(),4,$_SERVER['HTTP_REFERER']);
}?>

    </div>
  </div>