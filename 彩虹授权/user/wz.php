<?php
/**
 * 使用教程
**/
$mod='blank';
include("../api.inc.php");
$title='使用教程';
include './head.php';
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if(isset($_SERVER['HTTP_REFERER'])){
    if(strpos($_SERVER['HTTP_REFERER'], "http://".$_SERVER['HTTP_HOST']."/")==0){
    }else{
        exit();
    }
}else{
    exit();
}
if(!empty($_GET['id'])){
    $id=daddslashes($_GET['id']);
    if(!is_numeric($id)){
        header("Location:./index.html");
    }
    $rs=$DB->query("SELECT * FROM auth_gl WHERE id =".$id);
    if($res = $DB->fetch($rs)){
        
    }else{
        header("Location:./index.html");
    }
}else{
    header("Location:./index.html");
}
?>
<h1 style="font-size: 24px;font-weight: bold;"><?php echo $res['title']?></h1>
<br>发布时间：<?php echo $res['fbTime'];?></br>
<hr>
<div style="min-height: 300px; font-size: 16px; line-height:2;max-width: 80%;margin: 0 auto;">
<?php echo $res['con'];?>
</div>