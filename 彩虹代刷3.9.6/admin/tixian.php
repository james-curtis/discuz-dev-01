<?php
/**
 * 余额提现处理
**/
include("../includes/common.php");
$title='余额提现处理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
<?php

if($conf['fenzhan_tixian']==0)showmsg('当前站点未开放提现功能！');

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	else
		return '<font color=blue>未完成</font>';
}
function display_type($type){
	if($type==1)
		return '微信';
	elseif($type==2)
		return 'QQ钱包';
	else
		return '支付宝';
}

$my=isset($_GET['my'])?$_GET['my']:null;


if($my=='delete')
{
$id=intval($_GET['id']);
$sql="DELETE FROM shua_tixian WHERE id='$id'";
$DB->query($sql);
exit("<script language='javascript'>alert('删除成功！');window.location.href='tixian.php';</script>");
}
elseif($my=='complete')
{
$id=intval($_GET['id']);
$DB->query("update shua_tixian set status=1,endtime=NOW() where id='$id'");
exit("<script language='javascript'>alert('已变更为已提现状态');window.location.href='tixian.php';</script>");
}
elseif($my=='reset')
{
$id=intval($_GET['id']);
$DB->query("update shua_tixian set status=0 where id='$id'");
exit("<script language='javascript'>alert('已变更为未提现状态');window.location.href='tixian.php';</script>");
}
elseif($my=='search')
{
$sql=" `pay_account`='{$_GET['kw']}' or `pay_name`='{$_GET['kw']}'";
}
else
{
$sql=" 1";
}
$numrows=$DB->count("SELECT count(*) from shua_tixian WHERE{$sql}");

?>
<form method="get">
		<input type="hidden" name="my" value="search">
		<div class="input-group xs-mb-15">
			<input type="text" placeholder="请输入要搜索的提现账号或者姓名！" name="kw"
				   class="form-control text-center"
				   required>
			<span class="input-group-btn">
			<button type="submit" class="btn btn-primary">立即搜索</button>
			</span>
		</div>
	</form>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>ZID</th><th>金额</th><th>实际到账</th><th>提现方式</th><th>提现账号</th><th>姓名</th><th>申请时间</th><th>完成时间</th><th>状态</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM shua_tixian WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['zid'].'</td><td>'.$res['money'].'</td><td>'.$res['realmoney'].'</td><td>'.display_type($res['pay_type']).'</td><td>'.$res['pay_account'].'</td><td>'.$res['pay_name'].'</td><td>'.$res['addtime'].'</td><td>'.($res['status']==1?$res['endtime']:null).'</td><td>'.display_zt($res['status']).'</td><td>'.($res['status']==0?'<a href="./tixian.php?my=complete&id='.$res['id'].'" class="btn btn-success btn-xs">完成</a>':'<a href="./tixian.php?my=reset&id='.$res['id'].'" class="btn btn-info btn-xs">撤销</a>').'&nbsp;<a href="./record.php?zid='.$res['zid'].'" class="btn btn-warning btn-xs">明细</a>&nbsp;<a href="./tixian.php?my=delete&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此记录吗？\');">删除</a></td></tr>';
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
echo '<li><a href="tixian.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="tixian.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="tixian.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="tixian.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="tixian.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="tixian.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>