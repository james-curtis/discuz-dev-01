<?php
/**
 * 收支明细
**/
include("../includes/common.php");
$title='收支明细';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
<?php
$thtime=date("Y-m-d").' 00:00:00';
$rs=$DB->query("SELECT point,action,addtime from shua_points where zid='{$userrow['zid']}' and action='提成'");

$count1=0;$count2=0;$count3=0;$count4=0;
while($row = $DB->fetch($rs))
{
	$count3+=$row['point'];
	if($row['addtime']>=$thtime)$count1+=$row['point'];
}

$rs=$DB->query("SELECT point,action,addtime from shua_points where zid='{$userrow['zid']}' and action='消费'");

while($row = $DB->fetch($rs))
{
	$count4+=$row['point'];
	if($row['addtime']>=$thtime)$count2+=$row['point'];
}
?>
<div class="panel panel-primary">
     <div class="panel-heading">收支明细</div>
		  <div class="panel-body">
<table class="table table-bordered">
<tbody>
<tr height="25">
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>今日收益</b></br><?php echo round($count1,2)?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>今日消费</b></br></span><?php echo round($count2,2)?>元</font></td>
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>累计收益</b></br><?php echo round($count3,2)?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>累计消费</b></br></span><?php echo round($count4,2)?>元</font></td>
</tr>
</tbody>
</table>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>类型</th><th>金额</th><th>详情</th><th>时间</th></tr></thead>
          <tbody>
<?php

$rs=$DB->query("SELECT * FROM shua_points WHERE zid='{$userrow['zid']}' order by id desc limit 20");
while($res = $DB->fetch($rs))
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['action'].'</td><td><font color="'.($res['action']=='提成'?'red':'green').'">'.$res['point'].'</font></td><td>'.$res['bz'].'</td><td>'.$res['addtime'].'</td></tr>';
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
 </div>
</div>