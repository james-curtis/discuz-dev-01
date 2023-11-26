<?php
/**
 * 搜索授权
**/
$mod='blank';
include("../api.inc.php");
$title='搜索授权';
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
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">搜索授权</h3></div>
        <div class="panel-body">
          <form action="./list.php" method="get" class="form-inline" role="form">
            <div class="form-group">
              <label>类别</label>
              <select name="type" class="form-control">
                <option value="1">ＱＱ</option>
                <option value="2">域名</option>
                <option value="3">授权码</option>
                <option value="4">特征码</option>
              </select>
            </div>
            <div class="form-group">
              <label>内容</label>
              <input type="text" name="kw" value="" class="form-control" autocomplete="off" required/>
            </div>
			<div class="form-group">
              <select name="method" class="form-control">
                <option value="0">精确搜索</option>
                <option value="1">模糊搜索</option>
              </select>
            </div>
            <input type="submit" value="查询" class="btn btn-primary form-control"/>
          </form>
        </div>
      </div>
    </div>
  </div>