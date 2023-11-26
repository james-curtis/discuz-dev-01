<?php
/**
 * 网站设置
**/
$mod='blank';
include("../api.inc.php");
$title='网站设置';
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
$gls=$DB->count("SELECT count(*) from auth_tongji WHERE 1");
$pagesize=30;
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

if(isset($_POST['qq']) && isset($_POST['url'])){

} ?>
<?php
$mod=isset($_GET['mod'])?$_GET['mod']:null;
if($mod=='set'){
	$switch=$_POST['switch'];
	saveSetting('template_index',$_POST['template_index']);//前台模板
	saveSetting('content',$_POST['content']);//违规站点信息
	saveSetting('update',$_POST['update']);//最新版本信息
	saveSetting('uplog',$_POST['uplog']);//更新版本信息
	saveSetting('ggao',$_POST['ggao']);//后台公告信息
	$ad=$CACHE->clear();
	if($ad){showmsg('修改成功！',1);
		$city=get_ip_city($clientip);
		$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$udata['user']."','修改设置','".$date."','".$city."','无')");

	}else showmsg('修改失败！<br/>'.$DB->error(),4);
}elseif($mod==''){
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">网站返回信息设置</h3></div>
          <div class="panel-body">
          <form action="./set.php?mod=set" method="post" class="form-horizontal" role="form">
			<div class="input-group">
              <span class="input-group-addon">前台模板</span>
			<select class="form-control" name="template_index" default="<?php echo $confs['template_index']?>"><option value="default">旧版前台模板</option><option value="static">新版前台模板</option></select>
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">违规站点信息</span>
			<textarea name="content" class="form-control" style="height:200px;"><?php echo $confs['content']?></textarea>
			</div><br/>
			<div class="input-group">
              <span class="input-group-addon">最新版本信息</span>
			<input type="text" name="update" value="<?php echo $confs['update']?>" class="form-control" autocomplete="off" required/>
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">更新版本信息</span>
			  <textarea name="uplog" class="form-control" style="height:200px;"><?php echo $confs['uplog']?></textarea>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">后台公告信息</span>
			<textarea name="ggao" class="form-control" style="height:200px;"><?php echo $confs['ggao']?></textarea>
            </div><br/>
            <div class="list-group-item">
              <button class="btn btn-primary form-control" type="submit">确认保存</button>
			</div>
	</div>
  </form>
</div>
</div>
    </div>
<script>
$("select[name=\'switch\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_01").css("display","inherit");
	}else{
		$("#payapi_01").css("display","none");
	}
});
</script>
    <?php }?>
  </div>
  </div>