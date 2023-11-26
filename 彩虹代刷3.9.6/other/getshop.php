<?php
require './inc.php';

$type=isset($_GET['type'])?daddslashes($_GET['type']):exit('No type!');
$trade_no=isset($_GET['trade_no'])?daddslashes($_GET['trade_no']):exit('No trade_no!');

@header('Content-Type: text/html; charset=UTF-8');

$row=$DB->get_row("SELECT * FROM shua_pay WHERE trade_no='{$trade_no}' limit 1");
if($row['tid']==-1)$link = '../user/';
elseif($row['tid']==-2)$link = '../user/regok.php?orderid='.$trade_no;
else $link = '../index.php';

if($row['status']>=1){
	exit('{"code":1,"msg":"付款成功","backurl":"'.$link.'"}');
}else{
	exit('{"code":-1,"msg":"未付款"}');
}
?>