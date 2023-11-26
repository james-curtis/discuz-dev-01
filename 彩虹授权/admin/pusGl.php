<?php
include("../api.inc.php");
$title='后台管理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");


if(!empty($_GET['act'])){
    $act = $_GET['act'];
}else{
    $act = "no";
}
if($act == "no"){
?>

 
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
     <div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">发布公告</h3></div>
     <div class="panel-body">
     <form action="./pusGl.php?act=add" method="post" class="form-horizontal" role="form">
    
    <div class="form-group">
	  <label class="col-sm-2 control-label">标题</label>
	  <div class="col-sm-10"><input type="text" name="title" value="" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">内容</label>
	  <div class="col-sm-10">
	  <textarea class="form-control" rows="10" name="con"></textarea>
	  * 在这里你可以发布一些网站的公告，使用教程
	  </div>
	</div><br/>
    <div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="添加" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
    </form>
    <br>
    <br>
     <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>id</th><th>标题</th><th>内容</th><th>添加时间</th><th>操作</th></tr></thead>
          <tbody>
    <?php 
    $rs=$DB->query("SELECT * FROM auth_gl WHERE 1 order by id desc ");
    while($res = $DB->fetch($rs))
    {
        echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['title'].'</td><td>'.substr($res['con'],0,30).'</td><td>'.$res['fbTime'].'</td><td><a href="./pusGl.php?act=delete&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此任务吗？\');">删除</a></td></tr>';
       
    }
    
    ?>
    </tbody></table></div>
    </div>
    </div></div>
    </div>
<?php 
}elseif($act == "add"){
    $title = $_POST['title'];
    $con = $_POST['con'];
    if($title == null || $title == ""){
        exit("<script language='javascript'>alert('重要信息不能为空！');history.go(-1);</script>");
    }
    $DB->query("insert into auth_gl values(null,'{$title}','{$con}',now(),0)");
    exit("<script language='javascript'>alert('添加信息成功！');window.location.href='./pusGl.php';</script>");
}elseif($act == "delete"){
    $id = $_GET['id'];
    $DB->query("delete from auth_gl where id = $id");
    exit("<script language='javascript'>alert('删除信息成功！');window.location.href='./pusGl.php';</script>");
}
    

    
    
    ?>