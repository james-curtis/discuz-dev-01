<?php
include("../api.inc.php");
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='网站设置';
include './head.php';
?>
<?php
$mod=isset($_GET['mod'])?$_GET['mod']:null;
if($mod=='user_n'){
	$url=daddslashes(strip_tags($_POST['url']));
	$qq=daddslashes(strip_tags($_POST['qq']));
	$pass=daddslashes(strip_tags($_POST['pass']));
	if(!empty($pass) && !preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$pwd)){
		exit("<script language='javascript'>alert('密码只能为英文、数字与汉字！');history.go(-1);</script>");
	}elseif(!preg_match('/^[0-9]{5,11}+$/', $qq)){
		exit("<script language='javascript'>alert('QQ格式不正确！');history.go(-1);</script>");
	}else{
		$DB->query("update auth_site set url='$url',uid='$qq' where id='{$user['id']}'");
		if(!empty($pass))$DB->query("update auth_site set pass='$pass' where id='{$user['id']}'");
		exit("<script language='javascript'>alert('修改保存成功！');history.go(-1);</script>");
	}
}elseif($mod=='user'){
?>
<div class="panel panel-default">
<div class="panel-heading">
<h2 class="panel-title">我的信息</h2>
</div>
<div class="panel-body">
  <form action="./uset.php?mod=user_n" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">授权域名</label>
	  <div class="col-sm-10"><input type="text" name="url" value="<?php echo $user['url']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">ＱＱ</label>
	  <div class="col-sm-10"><input type="text" name="qq" value="<?php echo $user['uid']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">重置密码</label>
	  <div class="col-sm-10"><input type="text" name="pass" value="" class="form-control" placeholder="不修改请留空"/></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>
<?php } ?>
	</div>
</div>