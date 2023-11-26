<?php
@header('Content-Type: text/html; charset=UTF-8');
if(file_exists('install.lock')){
    exit('已经安装完成！如需重新安装，请删除install目录下的install.lock!');
}
$step = isset($_GET['step'])?$_GET['step']:0x001;
$finish = 1;
if($_GET['do']=='install'){
    $host = isset($_POST['host'])?$_POST['host']:null;
    $port = isset($_POST['port'])?$_POST['port']:null;
    $user = isset($_POST['user'])?$_POST['user']:null;
    $pwd = isset($_POST['pwd'])?$_POST['pwd']:null;
	$hcjia = isset($_POST['hcjia'])?$_POST['hcjia']:null;
	$ymjia = isset($_POST['ymjia'])?$_POST['ymjia']:null;
    $dbname = isset($_POST['dbname'])?$_POST['dbname']:null;
    if(empty($host) || empty($port) || empty($user) || empty($pwd) || empty($dbname)){
        $errorMsg = '请填完所有数据库信息';
    }else{
        $mysql['host'] = $host;
        $mysql['port'] = $port;
        $mysql['dbname'] = $dbname;
        $mysql['username'] = $user;
        $mysql['password'] = $pwd;
        try{
            $db = new PDO('mysql:host=' . $mysql['host'] . ';dbname=' . $mysql['dbname'] . ';port=' . $mysql['port'], $mysql['username'], $mysql['password']);
        }
        catch(Exception$e){
            $errorMsg = '链接数据库失败:' . $e -> getMessage();
        }
        $domians = explode('.', $_SERVER['HTTP_HOST']);
        $domians = array_reverse($domians);
        if(empty($errorMsg)){
            $config['db'] = $mysql;
            $data = '
<?php
/*数据库信息配置*/
$host = "'.$host.'"; //数据库地址
$port = "'.$port.'"; //数据库端口
$user = "'.$user.'"; //数据库用户名
$pwd = "'.$pwd.'"; //数据库密码
$dbname = "'.$dbname.'"; //数据库名

/*目录配置*/
define("CACHE_DIR","'.$hcjia.'"); //下载缓存目录
define("PACKAGE_DIR","'.$ymjia.'"); //程序安装包目录
?>';
            @file_put_contents('../config.php', $data);
            $db -> exec('set names utf8');
            $sqls = file_get_contents('xingzai.sql');
            $sqls = explode(';', $sqls);
            $success = 0;
            $error = 0;
            $errorMsg = null;
            foreach($sqls as $value){
                $length = trim($value);
                if(!empty($length)){
                    if($db -> exec($value) === false){
                        $error++;
                        $dberror = $db -> errorInfo();
                        $errorMsg .= $dberror[2] . '<br>';
                    }else{
                        $success++;
                    }
                }
            }
            $step = 3;
            @file_put_contents('install.lock', '');
			@mkdir("../{$hcjia}");
			@mkdir("../{$ymjia}");
			@mkdir("../{$ymjia}/release");
			@mkdir("../{$ymjia}/update");
			@file_put_contents("../{$ymjia}/authcode.php", '<?php
$authcode="1000000001";?>');
        }
    }
	header("Location: /");
}
?>
<?php
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>白云&小杰授权系统-安装</title>
  <meta name="keywords" content=""/>
  <meta name="description" content=""/>
  <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <script src="http://libs.useso.com/js/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="./">白云&小杰授权系统</a>
      </div><!-- /.navbar-header -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->

  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">授权系统安装</h3></div>
        <div class="panel-body">
          <form action="?do=install" method="post" class="form-horizontal" role="form">
            <div class="input-group">
              <span class="input-group-addon">地址</span>
              <input type="text" name="host" value="127.0.0.1" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">端口</span>
              <input type="text" name="port" value="3306" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">用户名</span>
              <input type="text" name="user" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">密码</span>
              <input type="text" name="pwd" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">库名</span>
              <input type="text" name="dbname" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">源码文件夹</span>
              <input type="text" name="ymjia" value="download" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">缓存文件夹</span>
              <input type="text" name="hcjia" value="cache" class="form-control" autocomplete="off" required/>
            </div><br/>
            <div class="form-group">
              <div class="col-sm-12"><input type="submit" value="安装" class="btn btn-primary form-control"/></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>