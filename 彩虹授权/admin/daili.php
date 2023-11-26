<?php
/**
 * 代理管理
**/
$mod='blank';
include("../api.inc.php");
$title='代理管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php

$my=$_GET['my'];
if($my=='black1'){
$user=$_GET['user'];
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">';
$id=$_GET['id'];
$sql=$DB->query("update `auth_site` set `active`='0' where `user`='{$user}'");
if($sql){echo '设置成功，状态为未激活！';}
else{echo '设置失败！';}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
exit;
}elseif($my=='black2'){
$user=$_GET['user'];
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">
<form action="./daili.php?" method="get" class="form-inline" role="form">
            <div class="form-group">
              <label>为</label>'.$user.' 账号>>><label>充值</label>
            </div>
            <div class="form-group">
            <input type="text" name="user" value='.$user.' hidden />
            <input type="text" name="my" value="black2" hidden/>
              <input type="text" name="s" value="" class="form-control" required/>
            </div>
            <input type="submit" value="充值" class="btn btn-primary form-control"/>
          </form>
<div class="panel-body box">';
if($_GET['s'] && $_GET['user']){
	$s=$_GET['s'];
		if($s>999999){
		exit("<script language='javascript'>alert('数据非法！');history.go(-1);</script>");
	}
	//$user=$_GET['user'];
	echo $user;
	$rs=$DB->get_row("SELECT * FROM auth_site WHERE user='$user' limit 1");
	$rmb=$rs['rmb']+$s;
	$sql=$DB->query("update `auth_site` set `rmb`='$rmb' where `user`='{$user}'");
	if($sql){echo '充值成功，'.$s.'元！';}
else{echo '充值失败！';}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
}else{echo '请填写充值金额！';}
exit;
}elseif($my=='black3'){
$user=$_GET['user'];
$rs=$DB->get_row("SELECT * FROM auth_site WHERE user='$user' limit 1");
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">
<form action="./daili.php?" method="get" class="form-inline" role="form">
            <div class="form-group">
              <label>为</label>'.$user.' 账号>>><label>更改密码</label>
            </div>
            <div class="form-group">
            <input type="text" name="user" value='.$user.' hidden />
            <input type="text" name="my" value="black3" hidden/>
          <input type="text" name="mima" value='.$rs['pass'].' />
            </div>
            <input type="submit" value="重置新密码" class="btn btn-primary form-control"/>
          </form>
<div class="panel-body box">';
$mima=$_GET['mima'];
if($mima<>$rs['pass'] && $mima<>""){
$sql=$DB->query("update `auth_site` set `pass`='$mima' where `user`='{$user}'");
if($sql){echo '设置成功！';}
else{echo '设置失败！';}
}else{echo "新密码不能为空，也不可以和之前密码相同";}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
exit;
}elseif($my=='black0'){
$user=$_GET['user'];
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">';
$id=$_GET['id'];
$sql=$DB->query("update `auth_site` set `active`='1' where `user`='{$user}'");
if($sql){echo '设置成功，状态为已激活！';}
else{echo '设置失败！';}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
exit;
}elseif($my=="vip"){
	$user=$_GET['user'];
	$rs=$DB->get_row("SELECT * FROM auth_site WHERE user='$user' limit 1");
	$vip=$rs['vip'];
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">
<form action="./daili.php?" method="get" class="form-inline" role="form">
            <div class="form-group">
              <label>为</label>'.$user.' 账号>>><label>升级</label>
            </div>
            <div class="form-group">
            <input type="text" name="user" value='.$user.' hidden />
            <input type="text" name="my" value="vip" hidden/>
             <select name="vip" class="form-control">
                <option value="1">钻石代理</option>
                <option value="2">至尊代理</option>
              </select>
            </div>
            <input type="submit" value="升级" class="btn btn-primary form-control"/>
          </form>
<div class="panel-body box">';
if($_GET['vip'] && $_GET['user']){
	$vip=$_GET['vip'];
	//$user=$_GET['user'];
	if($vip>2){
		exit("<script language='javascript'>alert('数据非法！');history.go(-1);</script>");
	}
	echo $user;
	$rs=$DB->get_row("SELECT * FROM auth_site WHERE user='$user' limit 1");
	$sql=$DB->query("update `auth_site` set `vip`='$vip' where `user`='{$user}'");
	if($sql){echo '升级成功，当前为VIP'.$vip.'！';}
else{echo '升级失败！';}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
}else{echo '请填写！';}
exit;
}elseif($my=="del"){
	$user=$_GET['user'];
	echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">代理设置</h3></div>
<div class="panel-body box">';
	$sql=$DB->query("DELETE FROM auth_site WHERE user='$user'");
if($sql){echo '删除代理'.$user.'成功！';}
else{echo '删除失败！';}
echo '<hr/><a href="./daili.php">>>返回代理列表</a></div></div>';
exit;
}

 ?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">代理管理</h3></div>
        <div class="panel-body">
          <form action="./daili.php" method="get" class="form-inline" role="form">
            <div class="form-group">
              <label>类别</label>
              <select name="type" class="form-control">
                <option value="1">代理账号</option>
              </select>
            </div>
            <div class="form-group">
              <label>内容</label>
              <input type="text" name="sdl" value="" class="form-control" required/>
            </div>
            <input type="submit" value="查询" class="btn btn-primary form-control"/>
          </form>
           <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>账号</th><th>状态</th><th>余额</th><th>密码</th><th>操作</th></tr></thead>
          <tbody>
<?php
if(isset($_GET['sdl'])) {
	$sdl=$_GET['sdl'];
	$dl="1";
	if($_GET['type']==1) {
		$sql=" `user`='{$_GET['sdl']}'";
		$numrows=$DB->count("SELECT count(*) from auth_site WHERE{$sql}");
		$con='包含 '.$_GET['sdl'].' 的共有 <b>'.$numrows.'</b> 个信息';
	}elseif($_GET['type']==2) {
		$sql=" `user`='{$_GET['kw']}'";
		$numrows=$DB->count("SELECT count(*) from auth_qqlevel3 WHERE{$sql}");
		$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个卡密';
	}
}else{
	$numrows=$DB->count("SELECT count(*) from auth_site WHERE 1");
	$sql=" 1";
	$con='代挂平台共有 <b>'.$numrows.'</b> 个代理信息';
}
echo $con;

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
if($dl=="1"){
$rs=$DB->query("SELECT * FROM auth_site  where user='$sdl' limit $offset,$pagesize");
}else{
$rs=$DB->query("SELECT * FROM auth_site   limit $offset,$pagesize");
}

while($res = $DB->fetch($rs))
{
if($res['active']==0){
	$isactive='<font color="red">未激活</font>';
}elseif($res['active']==1){
	$isactive='<font color="green">已激活</font>';
}
echo '<tr><td><b>'.$res['user'].'</b></td><td>'.$isactive.'</td><td>'.$res['rmb'].'</td><td>'.$res['pass'].'</td><td>'.($res['active']==1?'<a href="./daili.php?my=black1&user='.$res['user'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要更改状态吗？\');">设为未激活</a>':'<a href="./daili.php?my=black0&user='.$res['user'].'" class="btn btn-xs btn-success" onclick="return confirm(\'你确实要将此代理激活吗？\');">设置为激活</a>').'<a href="./daili.php?my=black2&user='.$res['user'].'" class="btn btn-xs btn-primary">为其充值</a><a href="./daili.php?my=black3&user='.$res['user'].'" class="btn btn-xs btn-info">改密码</a><a href="./daili.php?my=vip&user='.$res['user'].'" class="btn btn-xs btn-success">设置VIP等级</a><a href="./daili.php?my=del&user='.$res['user'].'" class="btn btn-xs btn-warning" onclick="return confirm(\'你确实要进行操作吗？\');">删除代理</a></td></tr>';
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
echo '<li><a href="daili.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="daili.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="daili.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="daili.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="daili.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="daili.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页

?>
        </div>
		<div class="panel-footer">
          <span class="glyphicon glyphicon-info-sign"></span> 管理员在这里可以管理已注册的代理用户。
        </div>
      </div>
    </div>
  </div>