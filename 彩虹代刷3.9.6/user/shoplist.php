<?php
/**
 * 商品管理
**/
include("../includes/common.php");
$title='商品管理';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

?>
  <div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
<?php

$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");

$my=isset($_GET['my'])?$_GET['my']:null;

$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$shua_class[0]='未分类';
while($res = $DB->fetch($rs)){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
?>
<div class="modal fade" align="left" id="search2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">商品分类</h4>
      </div>
      <div class="modal-body">
      <form action="shoplist.php" method="GET">
<select name="cid" class="form-control"><?php echo $select?></select><br/>
<input type="submit" class="btn btn-primary btn-block" value="查看"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
$price_array = @unserialize($userrow['price']);

if($my=='edit')
{
$tid=intval($_GET['tid']);
$row=$DB->get_row("select * from shua_tools where tid='$tid' limit 1");
if($price_array[$tid]['price'] && $price_array[$tid]['price']>=$row['cost'] && $row['cost']>0)$price=$price_array[$tid]['price'];
else $price=$row['price'];
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改商品价格</h3></div>';
echo '<div class="panel-body">';
echo '<form action="./shoplist.php?my=edit_submit&tid='.$tid.'" method="POST">
<div class="form-group">
<label>商品名称:</label><br>
<input type="text" class="form-control" name="name" value="'.$row['name'].'" disabled>
</div>
<div class="form-group">
<label>成本价格:</label><br>
<input type="text" class="form-control" name="cost" value="'.$row['cost'].'" disabled>
</div>
<div class="form-group">
<label>销售价格:</label><br>
<input type="text" class="form-control" name="price" value="'.$price.'" '.($row['cost']==0?'disabled':'required').'>
</div>
<div class="form-group">
<label>是否上架:</label><br>
<select class="form-control" name="del" default="'.$price_array[$tid]['del'].'"><option value="0">1_是</option><option value="1">0_否</option></select>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>
';
echo '<br/><a href="./shoplist.php">>>返回商品列表</a>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>';
}
elseif($my=='edit_submit')
{
$tid=intval($_GET['tid']);
$rows=$DB->get_row("select * from shua_tools where tid='$tid' limit 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
if($rows['cost']==0)$price=$rows['price'];
else $price=round(daddslashes($_POST['price']),2);
$del=intval($_POST['del']);
if(!is_numeric($price) || !preg_match('/^[0-9.]+$/', $price))showmsg('价格输入不规范',3);
if($price<$rows['cost']){
	showmsg('销售价格不能低于成本价格！',3);
} else {
$price_array[$tid] = array();
if($price != $rows['price'] || $del != $price_array[$tid]['del']){
$price_array[$tid]['price'] = $price;
$price_array[$tid]['del'] = $del;
}
$price_data = serialize($price_array);
if($DB->query("update shua_site set price='$price_data' where zid='{$userrow['zid']}'"))
	showmsg('修改商品成功！<br/><br/><a href="./shoplist.php">>>返回商品列表</a>',1);
else
	showmsg('修改商品失败！'.$DB->error(),4);
}
}
elseif($my=='reset')
{
if($DB->query("update shua_site set price=NULL where zid='{$userrow['zid']}'"))
	showmsg('重置成功！<br/><br/><a href="./shoplist.php">>>返回商品列表</a>',1);
else
	showmsg('重置失败！'.$DB->error(),4);
}
else
{
if(isset($_GET['cid'])){
	$cid = intval($_GET['cid']);
	$numrows=$DB->count("SELECT count(*) from shua_tools where cid='$cid' and active=1");
	$sql=" cid='$cid' and active=1";
	$con='分类 '.$shua_class[$cid].' 共有 <b>'.$numrows.'</b> 个商品&nbsp;[<a href="shoplist.php">查看全部</a>]';
}else{
	$numrows=$DB->count("SELECT count(*) from shua_tools where active=1");
	$sql=" active=1";
	$con='系统共有 <b>'.$numrows.'</b> 个商品&nbsp;[<a href="#" data-toggle="modal" data-target="#search2" id="search2">分类查看</a>]&nbsp;[<a href="shoplist.php?my=reset" onclick="return confirm(\'是否要重置所有商品价格设定，恢复到最初状态？\');">重置价格设定</a>]';
}

echo $con;

?>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>名称</th><th>成本价格</th><th>销售价格</th><th>状态</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM shua_tools WHERE{$sql} order by sort asc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
	if($price_array[$res['tid']]['price'] && $price_array[$res['tid']]['price']>=$res['cost'] && $res['cost']>0)$price=$price_array[$res['tid']]['price'];
	else $price=$res['price'];
echo '<tr><td><b>'.$res['tid'].'</b></td><td>'.$res['name'].'</td><td>'.($res['cost']==0?$res['price']:$res['cost']).'</td><td>'.$price.'</td><td>'.($price_array[$res['tid']]['del']==1?'<font color=red>已下架</font>':'<font color=green>上架中</font>').'</td><td><a href="./shoplist.php?my=edit&tid='.$res['tid'].'" class="btn btn-info btn-xs">编辑</a></td></tr>';
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
echo '<li><a href="shoplist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="shoplist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="shoplist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="shoplist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="shoplist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="shoplist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}?>
    </div>
  </div>