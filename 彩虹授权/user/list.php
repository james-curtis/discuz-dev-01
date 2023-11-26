<?php
/**
 * 授权列表
**/
$mod='blank';
include("../api.inc.php");
$title='授权列表';
include './head.php';
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<?php
$gls=$DB->count("SELECT count(*) from auth_site WHERE 1");
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">授权列表</h2>
				</div>
				<div class="panel-body">
                    <table class="table table-bordered table-condensed table-hover table-striped table-vertical-center">
                        <thead>
                        <tr>
							<th>ID</th>
							<th>QQ</th>
                            <th>域名</th>
                            <th>状态</th>
                            <th>操作</th>
						</tr>
<?php
$rs=$DB->query("SELECT * FROM auth_site WHERE uid='{$user['uid']}' order by id desc limit 10");
while($res = $DB->fetch($rs))
{
echo '<tr><td>'.$res['id'].'</td><td><a href="list.php?qq='.$res['uid'].'">'.$res['uid'].'</a>&nbsp;<a href="tencent://message/?uin='.$res['uid'].'&Site=%E6%8E%88%E6%9D%83%E5%B9%B3%E5%8F%B0&Menu=yes">[?]</a></td><td><a href="/jump.php?url='.urlencode('http://'.$res['url'].'/').'" target="_blank">'.$res['url'].'</a></td><td onclick="alert(\'授权码：'.$res['authcode'].'\n\r特征码：'.$res['sign'].'\')">'.$res['active'].'</td><td><a href="./uset.php?mod=user" class="btn btn-xs btn-info">编辑</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<div class="input-group-addon">';
echo'<ul class="pagination">';
$s = ceil($gls / $pagesize);
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$s;
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
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="list.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="list.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="list.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>