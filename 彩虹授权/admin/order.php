<?php
/**
 * 订单记录
**/
$mod='blank';
include("../api.inc.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='订单记录';
include './head.php';
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
if(isset($_GET['kw'])) {
	if($_GET['type']==1)
		$sql=($_GET['method']==1)?" `id` LIKE '%{$_GET['kw']}%'":" `id`='{$_GET['kw']}'";
	elseif($_GET['type']==2)
		$sql=($_GET['method']==1)?" `name` LIKE '%{$_GET['kw']}%'":" `name`='{$_GET['kw']}'";
	elseif($_GET['type']==3)
		$sql=($_GET['method']==1)?" `money` LIKE '%{$_GET['kw']}%'":" `money`='{$_GET['kw']}'";
	elseif($_GET['type']==4)
		$sql=($_GET['method']==1)?" `type` LIKE '%{$_GET['kw']}%'":" `type`='{$_GET['kw']}'";
	elseif($_GET['type']==5)
		$sql=($_GET['method']==1)?" `status` LIKE '%{$_GET['kw']}%'":" `status`='{$_GET['kw']}'";
	$gls=$DB->count("SELECT count(*) from auth_order WHERE{$sql}");
	$con='包含 '.$_GET['kw'].' 的共有 <b>'.$gls.'</b> 个订单';
}elseif(isset($_GET['id'])) {
	$sql=" `id`='{$_GET['id']}'";
	$gls=$DB->count("SELECT count(*) from auth_order WHERE{$sql}");
	$con='QQ '.$_GET['id'].' 共有 <b>'.$gls.'</b> 个订单';
}else{

	$gls=$DB->count("SELECT count(*) from auth_order WHERE 1");
	$sql=" 1";
}
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
        <div class="panel-heading"><h3 class="panel-title">订单记录 (<?=$gls?>)</b> 个订单</h3></div>
          <ul class="list-group">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>交易号</th><th>商品名称</th><th>订单号</th><th>商品价格</th><th>支付方式</th><th>状态</th></tr></thead>
          <tbody>
<?php
$rs=$DB->query("SELECT * FROM auth_order WHERE{$sql} order by id desc limit $pageu,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td>'.$res['id'].'</td><td>'.$res['name'].'</td><td>'.$res['trade_no'].'</td><td>'.$res['money'].'</td><td>'.$res['type'].'</td><td>'.($res['status']==1?'<font color=green>已完成</font>':'<font color=red>未完成</font>').'</a></td></tr>';
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
echo '<li><a href="order.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="order.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="order.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="order.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="order.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="order.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>