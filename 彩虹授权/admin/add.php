﻿﻿<?php
/**
 * 添加授权
**/
$mod='blank';
include("../api.inc.php");
$title='添加授权';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
if(isset($_POST['qq']) && isset($_POST['url'])){
$qq=daddslashes($_POST['qq']);
$user=daddslashes($_POST['user']);
$pass=daddslashes($_POST['pass']);
$rmb=daddslashes($_POST['rmb']);
$vip=daddslashes($_POST['vip']);
$url=daddslashes($_POST['url']);
$row=$DB->get_row("SELECT * FROM auth_site WHERE uid='{$qq}' limit 1");
if($row!='')exit("<script language='javascript'>alert('授权平台已存在该ＱＱ，请使用“添加站点”！');history.go(-1);</script>");
$row=$DB->get_row("SELECT * FROM auth_site WHERE user='{$user}' limit 1");
if($row!='')exit("<script language='javascript'>alert('授权平台已存在该账号”！');history.go(-1);</script>");
$row1=$DB->get_row("SELECT * FROM auth_site WHERE 1 order by sign desc limit 1");
$sign=$row1['sign']+1;
$authcode=md5(random(32).$qq);
$row2=$DB->get_row("SELECT * FROM auth_site WHERE authcode='{$authcode}' limit 1");
if($row!='')exit("<script language='javascript'>alert('请返回重新操作！');history.go(-1);</script>");
$url_arr=explode(',',$url);
foreach($url_arr as $val) {
	$sql="insert into `auth_site` (`zid`,`uid`,`user`,`pass`,`rmb`,`vip`,`url`,`date`,`authcode`,`active`,`sign`) values ('".$udata['uid']."','".$qq."','".$user."','".$pass."','".$rmb."','".$vip."','".trim($val)."','".$date."','".$authcode."','1','".$sign."')";
	$DB->query($sql);
}
$city=get_ip_city($clientip);
$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$udata['user']."','新增授权','".$date."','".$city."','".$qq."|".$url."')");
exit("<script language='javascript'>alert('添加授权成功！');window.location.href='downfile.php?qq={$qq}';</script>");
} ?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">添加授权（新购买者）</h3></div>
        <div class="panel-body">
          <form action="./add.php" method="post" class="form-horizontal" role="form">
            <div class="input-group">
              <span class="input-group-addon">账号</span>
              <input type="text" name="user" value="<?=@$_POST['user']?>" class="form-control" placeholder="代理登陆账号" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">密码</span>
              <input type="text" name="pass" value="<?=@$_POST['pass']?>" class="form-control" placeholder="代理登陆密码" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">ＱＱ</span>
              <input type="text" name="qq" value="<?=@$_POST['qq']?>" class="form-control" placeholder="购买授权的QQ" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">代理余额</span>
              <input type="text" name="rmb" value="0" class="form-control" placeholder="代理余额" autocomplete="off" required/>
            </div><br/>
			<div class="input-group">
			  <span class="input-group-addon">代理级别:</span>
			  <select name="vip" class="form-control">
					<option value="0">铜牌代理</option>
					<option value="1">钻石代理</option>
					<option value="2">至尊代理</option>
              </select>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">域名</span>
              <input type="text" name="url" value="<?=@$_POST['url']?>" class="form-control" placeholder="" autocomplete="off" required/>
            </div><br/>
            <div class="form-group">
              <div class="col-sm-12"><input type="submit" value="添加" class="btn btn-primary form-control"/></div>
            </div>
          </form>
        </div>
        <div class="panel-footer">
          <span class="glyphicon glyphicon-info-sign"></span> 添多个域名请用英文逗号 , 隔开！
        </div>
      </div>
    </div>
  </div>