<?php
/**
 * 充值余额
**/
$mod='blank';
include("../api.inc.php");
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='充值余额';
include './head.php';
if($_GET['do']=="cz"){
$km = daddslashes($_POST['km']);
	$kmrow=$DB->get_row("SELECT * FROM auth_kms WHERE km='$km' limit 1");
	if(!$kmrow['id']){
	exit("<script>alert('卡密不存在');history.go(-1);</script>");
	}elseif($kmrow['zt']>0){
		exit("<script>alert('卡密已被使用');history.go(-1);</script>");
	}else{
		$ms = $kmrow['value'];
		$rmb = $rmb + $value;
		$DB->query("UPDATE auth_site SET rmb=rmb+$ms WHERE id='{$user['id']}'");
		$DB->query("UPDATE auth_kms SET zt='1',user='{$user['phone']}',usetime='$date' where id='{$kmrow['id']}'");
		exit("<script>alert('成功充值".$ms."元');history.go(-1);</script>");
	}
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">充值余额</h2>
	</div>
        <li class="list-group-item">
        <form action="?do=cz" method="post" class="form-horizontal" >
        	<input type="hidden" name="type" value="cz" />
			<input type="text" name="km" class="form-control" placeholder="请输入充值卡密" required/>
        </li>
        <li class="list-group-item">
            <input type="submit" name="submit" value="充值" class="btn btn-primary form-control"/>
         </form>
        </li>
	</ul>
</div>
    </div>
  </div>
  