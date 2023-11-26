<?php
/**
 * 设置补领状态
**/
include("../includes/common.php");
@header('Content-Type: application/json; charset=UTF-8');

if($islogin==1){}else exit('{"code":301,"msg":"未登录"}');

$id=intval($_GET['name']);
$status=intval($_GET['status']);
if($status==4){
	if($DB->query("DELETE FROM shua_orders WHERE id='$id'"))
		exit('{"code":200}');
	else
		exit('{"code":400,"msg":"删除订单失败！'.$DB->error().'"}');
}elseif($status==5){
	$result = do_goods($id);
	if(strpos($result,'成功')!==false){
		$status=$conf['shequ_status']?$conf['shequ_status']:1;
		$DB->query("update shua_orders set status='$status',result=NULL where id='{$id}'");
	}
	exit('{"code":100,"msg":"'.$result.'"}');
}elseif($status==6){
	$row=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if($row['zid']<1)exit('{"code":100,"msg":"退款失败，该订单属于主站"}');
	if($row['status']==4)exit('{"code":100,"msg":"该订单已退款请勿重复提交"}');
	if($row['status']!=0&&$row['status']!=3)exit('{"code":100,"msg":"只有未处理和异常的订单才支持退款"}');
	$tool=$DB->get_row("select * from shua_tools where tid='{$row['tid']}' limit 1");
	$money=$tool['cost']>0?$tool['cost']:$tool['price'];
	$money=$row['value']*$money;
	$DB->query("update `shua_site` set `rmb`=`rmb`+{$money} where `zid`='{$row['zid']}'");
	addPointRecord($row['zid'], $money, '退款', '订单(ID'.$id.')已退款到分站余额');
	$DB->query("update shua_orders set status='4',result=NULL where id='{$id}'");
	exit('{"code":100,"msg":"该订单已成功退款给分站ID'.$row['zid'].'"}');
}else{
	if($DB->query("update shua_orders set status='$status',result=NULL where id='{$id}'"))
		exit('{"code":200}');
	else
		exit('{"code":400,"msg":"修改订单失败！'.$DB->error().'"}');
}