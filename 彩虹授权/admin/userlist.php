<?php

/**
 * 管理员列表
**/
$mod='blank';
include("../api.inc.php");
$title='管理员列表';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($_GET['my']=='edit') {
$uid=intval($_GET['uid']);
$row=$DB->get_row("SELECT * FROM auth_user WHERE uid='{$uid}' limit 1");
$classid=$row['class'];
if($row=='')exit("<script language='javascript'>alert('授权管理平台不存在该用户！');history.go(-1);</script>");
if($uid==1){
	showmsg('您无法修改系统账号！',3);
	exit;
}else if($uid==$udata['uid']){
	showmsg('您无法修改自己账号！',3);
	exit;
}
$uid=$_GET['uid'];
$row=$DB->get_row("select * from auth_user where uid='$uid' limit 1");
echo '<div class="panel panel-primary">';
echo '<div class="panel-heading"><h3 class="panel-title">修改授权商信息</h3></div>';
echo '<div class="panel-body">';
echo '<form action="./userlist.php?my=edit_submit&uid='.$uid.'" method="POST">
<div class="form-group">
<label>用户名:</label><br>
<input type="text" class="form-control" name="user" value="'.$row['user'].'" required>
</div>
<div class="form-group">
<label>密码:</label><br>
<input type="text" class="form-control" name="pass" value="'.$row['pass'].'" required>
</div>
<div class="form-group">
<label>QQ:</label><br>
<input type="text" class="form-control" name="qq" value="'.$row['qq'].'" required>
</div>
<div class="form-group">
<label>允许登陆的IP:</label><br>
<input type="text" class="form-control" name="ips" value="'.$row['ips'].'" >
<code>多个请用,隔开。0则是不限制</code>
</div>
<div class="input-group">
			  <span class="input-group-addon">权限:</span>
			  <select name="per" class="form-control">
				<option value="1">超级管理员</option>
				<option value="2">代理商权限</option>
				<option value="3">获取信息权限</option>
				<option value="4">封禁该用户</option>
              </select>
            </div><br/>
<input type="submit" class="btn btn-primary btn-block"
value="确定修改"></form>
';
echo '<br/><a href="./userlist.php">>>返回授权商列表</a>';
echo '</div></div>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default"));
}
</script>';
include './foot.php';
}
elseif($my=='edit_submit')
{
$uid=$_GET['uid'];
$rows=$DB->get_row("select * from auth_user where uid='$uid' limit 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
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
$user=$_POST['user'];
$qq=$_POST['qq'];
$pass=$_POST['pass'];
$ips = $_POST['ips'];
if($user==NULL or $qq==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
if($DB->query("update auth_user set user='$user',qq='$qq',pass='$pass',per_sq='$per_sq',per_db='$per_db',ips='$ips',active='$active' where uid='{$uid}'"))
	showmsg('修改授权商信息成功！<br/><br/><a href="./userlist.php">>>返回授权商列表</a>',1);
else
	showmsg('修改授权商信息失败！'.$DB->error(),4);
}
}
elseif($my=='delete')
{
$uid=$_GET['uid'];
$sql="DELETE FROM auth_user WHERE uid='$uid'";
if($DB->query($sql))
	showmsg('删除成功！<br/><br/><a href="./userlist.php">>>返回授权商列表</a>',1);
else
	showmsg('删除失败！'.$DB->error(),4);
}
else
{
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
	$gls=$DB->count("SELECT count(*) from auth_user WHERE 1");
	$sql=" 1";


$pagesize=30;
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

echo $con;
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">用户列表 (<?=$gls?>)</b> 个用户</h3></div>
          <ul class="list-group">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>用户名</th><th>QQ</th><th>管理员类型</th><th>授权权限</th><th>操作</th></tr></thead>
          <tbody>
<?php
$rs=$DB->query("SELECT * FROM auth_user order by uid desc limit $pageu,$pagesize");
while($res = $DB->fetch($rs))
{
echo '
<tr>
<td>'.$res['uid'].'</td>
<td>'.$res['user'].'</td>
<td>'.$res['qq'].'&nbsp;<a href="tencent://message/?uin='.$res['qq'].'&amp;Site=授权平台&amp;Menu=yes"><img src="http://wpa.qq.com/pa?p=2:'.$res['qq'].':41" border=0></a></td>
<td>'.($res['per_sq']==1?'超级管理员':'代理商权限').'</td>
<td>'.($res['per_sq']==1?'有权限':'没有权限').'</td>
<td><a href="./userlist.php?my=edit&uid='.$res['uid'].'" class="btn btn-xs btn-info">编辑</a> <a href="./userlist.php?my=delete&uid='.$res['uid'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此用户吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$s = ceil($gls / $pagesize);
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$s;
if ($page>1)
{
echo '<li><a href="pirate.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="pirate.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="pirate.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="pirate.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="pirate.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="pirate.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>
<?php
}
?>