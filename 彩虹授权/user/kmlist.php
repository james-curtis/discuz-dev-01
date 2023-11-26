﻿<?php
/**
 * 卡密列表
**/
$mod='blank';
include("../api.inc.php");
$title='卡密列表';
include './head.php';
$id=$user['id'];
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$row = $DB->get_row("SELECT * FROM auth_site WHERE id='$id' limit 1");
$rs=$DB->get_row("SELECT * FROM auth_config WHERE 1");
$vip=$row['vip'];
if($vip==1){
	$dljg=$rs['vip1'];
}elseif($vip==0){
	$dljg=$rs['vip0'];
}elseif($vip==2){
	$dljg=$rs['vip2'];
}
?>
<?php
function getkm($len = 18)
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$strlen = strlen($str);
	$randstr = "";
	for ($i = 0; $i < $len; $i++) {
		$randstr .= $str[mt_rand(0, $strlen - 1)];
	}
	return $randstr;
}

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add'){
$num=intval($_POST['num']);
$value=intval($_POST['value']);
$rmb=$row['rmb']-$dljg*$num;
if($rmb>=0){
	
}else{
	exit("<script language='javascript'>alert('您的余额不足，请联系管理员充值！');history.go(-1);</script>");
}
$sql=$DB->query("update `auth_site` set `rmb`='$rmb' where `id`=$id;");
if($sql){
	
}else{
	exit("<script language='javascript'>alert('扣款失败，请联系管理员！');history.go(-1);</script>");
}

echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
for ($i = 0; $i < $num; $i++) {
	$km=getkm(18);
	$sql=$DB->query("insert into `auth_km` (`zid`, `km`, `zt`) values ('{$user['id']}', '$km', '1')");
	if($sql) {
		echo "<li class='list-group-item'>$km</li>";
	}
}

echo '<a href="./kmlist.php" class="btn btn-default btn-block">>>返回卡密列表</a>';
}

elseif($my=='del'){
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">删除卡密</h3></div>
<div class="panel-body box">';
$id=$_GET['id'];
$kmrow=$DB->get_row("SELECT * FROM auth_km WHERE id='$id' limit 1");
$sql=$DB->query("DELETE FROM auth_km WHERE id='$id'");
if($sql){
if($kmrow['zt']==1){
$vip=$row['vip'];
if($vip==1){
	$dljg=$rs['vip1'];
}elseif($vip==0){
	$dljg=$rs['vip0'];
}elseif($vip==2){
	$dljg=$rs['vip2'];
}
$DB->query("update auth_site set rmb=rmb+{$dljg} where id='{$user['id']}'");
		echo '删除成功！已退回[ '.$dljg.' 元]，余额到你的账号';
	}else{
		echo '删除成功！';
	}
}else{echo '删除失败！';}
echo '<hr/><a href="./kmlist.php">>>返回卡密列表</a></div></div>';
}
else
{
$row = $DB->get_row("SELECT * FROM auth_site WHERE id='$id' limit 1");
$dlrmb=$row['rmb'];
echo '<form action="kmlist.php?my=add" method="POST" class="form-inline">
  <div class="form-group">
    <label>卡密生成</label>
    <input type="text" class="form-control" name="num" placeholder="生成的个数" required>
  </div>
  <button type="submit" class="btn btn-primary">生成</button>
</form>'."<p>当前拿货价格 $dljg 元，当前余额 $dlrmb 元</p>";

if(isset($_GET['kw'])) {
	if($_GET['type']==1) {
		$sql=" `km`='{$_GET['kw']}'";
		$numrows=$DB->count("SELECT count(*) from auth_km WHEREE{$sql}");
		$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个卡密';
	}elseif($_GET['type']==2) {
		$sql=" `user`='{$_GET['kw']}'";
		$numrows=$DB->count("SELECT count(*) from auth_km WHEREE{$sql}");
		$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个卡密';
	}
}else{
	$numrows=$DB->count("SELECT count(*) from auth_km WHERE zid='{$user['id']}' limit 1");
	$sql=" 1";
	$con='你的账号共有 <b>'.$numrows.'</b> 个卡密';
}
echo $con;
?>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>卡密</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM auth_km WHERE zid='{$user['id']}' order by id desc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
if($res['zt']==0) {
	$zt='<font color="red">已使用</font>';
} elseif($res['zt']==1) {
	$zt='<font color="green">未使用</font>';
}
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['km'].'</td><td>'.$zt.'</td><td><a href="./kmlist.php?my=del&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此卡密吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="kmlist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="kmlist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="kmlist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="kmlist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="kmlist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="kmlist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}
?>
    </div>
  </div>