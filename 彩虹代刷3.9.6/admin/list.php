<?php
/**
 * 订单管理
**/
include("../includes/common.php");
$title='订单管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
<div class="modal fade" align="left" id="search2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">分类查看订单</h4>
      </div>
      <div class="modal-body">
      <form action="list.php" method="GET">
<select name="type" class="form-control"><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="3">异常</option><option value="4">删除订单</option></select><br/>
<input type="submit" class="btn btn-primary btn-block" value="搜索"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
function display_zt($zt,$id=0){
	if($zt==1)
		return '<a onclick="setResult('.$id.')" title="点此填写结果"><font color=green>已完成</font></a>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<a onclick="setResult('.$id.')" title="点此填写异常原因"><font color=red>异常</font></a>';
	elseif($zt==4)
		return '<font color=grey>已退款</font>';
	else
		return '<font color=blue>待处理</font>';
}

$rs=$DB->query("SELECT * FROM shua_tools WHERE 1 order by sort asc");
$select='';
while($res = $DB->fetch($rs)){
	$shua_func[$res['tid']]=$res['name'];
	$select.='<option value="'.$res['tid'].'">'.$res['name'].'</option>';
}

if($my=='op')
{
$status=$_POST['status'];
$checkbox=$_POST['checkbox'];
$i=0;
$statuss=$conf['shequ_status']?$conf['shequ_status']:1;
foreach($checkbox as $id){
	if($status==4)$DB->query("DELETE FROM shua_orders WHERE id='$id'");
	elseif($status==5){
		$result = do_goods($id);
		if(strpos($result,'成功')!==false){
			$DB->query("update shua_orders set status='$statuss',result=NULL where id='{$id}'");
		}
	}
	else $DB->query("update shua_orders set status='$status' where id='$id' limit 1");
	$i++;
}
exit("<script language='javascript'>alert('成功改变{$i}条订单状态');history.go(-1);</script>");
}
else
{

if(isset($_GET['kw'])) {
	$sql=" `input`='{$_GET['kw']}'";
	$numrows=$DB->count("SELECT count(*) from shua_orders WHERE{$sql}");
	$con='包含 '.$_GET['kw'].' 的共有 <b>'.$numrows.'</b> 个订单';
	$link='&kw='.$_GET['kw'];
}elseif(isset($_GET['tid'])) {
	$sql=" `tid`='{$_GET['tid']}'";
	$numrows=$DB->count("SELECT count(*) from shua_orders WHERE{$sql}");
	$con=$shua_func[$_GET['tid']].' 共有 <b>'.$numrows.'</b> 个订单';
	$link='&tid='.$_GET['tid'];
}elseif(isset($_GET['zid'])) {
	$sql=" `zid`='{$_GET['zid']}'";
	$numrows=$DB->count("SELECT count(*) from shua_orders WHERE{$sql}");
	$con='站点ID '.$_GET['zid'].' 的共有 <b>'.$numrows.'</b> 个订单';
	$link='&zid='.$_GET['zid'];
}elseif(isset($_GET['type'])) {
	$sql=" `status`='{$_GET['type']}'";
	$numrows=$DB->count("SELECT count(*) from shua_orders WHERE{$sql}");
	$con=''.display_zt($_GET['type']).' 状态的共有 <b>'.$numrows.'</b> 个订单';
	if($_GET['type']==3)$con.='&nbsp;[<a href="list.php?my=fillall" onclick="return confirm(\'你确定要将所有异常订单改为待处理状态吗？\');">将所有异常订单改为待处理状态</a>]';
	$link='&type='.$_GET['type'];
}else{
	$numrows=$DB->count("SELECT count(*) from shua_orders");
	$ondate=$DB->count("select count(*) from shua_orders where status=1");
	$ondate2=$DB->count("select count(*) from shua_orders where status=2");
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b> 个订单，其中已完成的有 <b>'.$ondate.'</b> 个，正在处理的有 <b>'.$ondate2.'</b> 个。';
}

echo '<form action="list.php" method="GET" class="form-inline">
  <div class="form-group">
    <label>搜索订单</label>
    <input type="text" class="form-control" name="kw" placeholder="请输入下单账号">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button>&nbsp;
  <a href="./export.php" class="btn btn-success">导出订单</a>
  <a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-info">分类查看</a>
</form>';
echo $con;
?>
      <div class="table-responsive">
	  <form name="form1" method="post" action="list.php?my=op">
        <table class="table table-striped">
          <thead><tr><th>商品名称</th><th>下单数据</th><th>份数</th><th>站点ID</th><th>添加时间</th><th>状态</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM shua_orders WHERE{$sql} order by id desc limit $offset,$pagesize");
while($res = $DB->fetch($rs))
{
echo '<tr><td><input type="checkbox" name="checkbox[]" id="list1" value="'.$res['id'].'" onClick="unselectall1()"><span onclick="showOrder('.$res['id'].')" title="点击查看详情">'.$shua_func[$res['tid']].'</span></td><td><span onclick="inputOrder('.$res['id'].')" title="点击修改数据">'.$res['input'].($res['input2']?'<br/>'.$res['input2']:null).($res['input3']?'<br/>'.$res['input3']:null).($res['input4']?'<br/>'.$res['input4']:null).($res['input5']?'<br/>'.$res['input5']:null).'</span></td><td><span onclick="inputNum('.$res['id'].')" title="点击修改份数">'.$res['value'].'</span></td><td>'.$res['zid'].'</span></td><td>'.$res['addtime'].'</td><td>'.display_zt($res['status'],$res['id']).'</td><td><select onChange="javascript:setStatus(\''.$res['id'].'\',this.value)" class="form-control"><option selected>操作订单</option><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="3">异常</option><option value="5">重新下单</option><option value="4">删除订单</option>'.($res['zid']>1?'<option value="6">退款</option>':null).'</select></td></tr>';
}
?>
          </tbody>
        </table>
<input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">&nbsp;全选&nbsp;
<select name="status"><option selected>操作订单</option><option value="0">待处理</option><option value="2">正在处理</option><option value="1">已完成</option><option value="3">异常</option><option value="5">重新下单</option><option value="4">删除订单</option></select>
<input type="submit" name="Submit" value="确定">
</form>
      </div>
<script>
var checkflag1 = "false";
function check1(field) {
if (checkflag1 == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag1 = "true";
return "false"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag1 = "false";
return "true"; }
}

function unselectall1()
{
    if(document.form1.chkAll1.checked){
	document.form1.chkAll1.checked = document.form1.chkAll1.checked&0;
	checkflag1 = "false";
    }
}
</script>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="list.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="list.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="list.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
if($pages>=10)$s=10;
else $s=$pages;
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="list.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="list.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="list.php?page='.$last.$link.'">尾页</a></li>';
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
<script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>
<script>
function showOrder(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=order&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '订单详情',
				  skin: 'layui-layer-rim',
				  content: data.data
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function inputOrder(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=order2&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改数据',
				  skin: 'layui-layer-rim',
				  content: data.data
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function inputNum(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'GET',
		url : 'ajax.php?act=order3&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.open({
				  type: 1,
				  title: '修改份数',
				  skin: 'layui-layer-rim',
				  content: data.data
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function setStatus(name, status) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'get',
		url : 'setStatus.php',
		data : 'name=' + name + '&status=' + status,
		dataType : 'json',
		success : function(ret) {
			layer.close(ii);
			if (ret['code'] != 200) {
				alert(ret['msg'] ? ret['msg'] : '操作失败');
			}
			window.location.reload();
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function setResult(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax.php?act=result',
		data : {id:id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.close(ii);
				layer.prompt({title: '填写异常原因', value: data.result, formType: 2}, function(text, index){
					var ii = layer.load(2, {shade:[0.1,'#fff']});
				$.ajax({
					type : 'POST',
					url : 'ajax.php?act=setresult',
					data : {id:id,result:text},
					dataType : 'json',
					success : function(data) {
						layer.close(ii);
						if(data.code == 0){
							layer.msg('填写异常原因成功');
						}else{
							layer.alert(data.msg);
						}
					},
					error:function(data){
						layer.msg('服务器错误');
						return false;
					}
				});
			});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function saveOrder(id) {
	var inputvalue=$("#inputvalue").val();
	if(inputvalue=='' || $("#inputvalue2").val()=='' || $("#inputvalue3").val()=='' || $("#inputvalue4").val()=='' || $("#inputvalue5").val()==''){layer.alert('请确保每项不能为空！');return false;}
	if($('#inputname').html()=='下单ＱＱ' && (inputvalue.length<5 || inputvalue.length>11)){layer.alert('请输入正确的QQ号！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=editOrder",
		data : {id:id,inputvalue:inputvalue,inputvalue2:$("#inputvalue2").val(),inputvalue3:$("#inputvalue3").val(),inputvalue4:$("#inputvalue4").val(),inputvalue5:$("#inputvalue5").val()},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存');
		} 
	});
}
function saveOrderNum(id) {
	var num=$("#num").val();
	if(num==''){layer.alert('请确保每项不能为空！');return false;}
	$('#save').val('Loading');
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=editOrderNum",
		data : {id:id,num:num},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('保存成功！');
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
			$('#save').val('保存');
		} 
	});
}
</script>