<?php
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

switch($act){
case 'gettool':
	$cid=intval($_GET['cid']);
	$rs=$DB->query("SELECT * FROM shua_tools WHERE cid='$cid' and active=1 order by sort asc");
	$data = array();
	while($res = $DB->fetch($rs)){
		$price = $res['cost']>0?$res['cost']:$res['price'];
		$data[]=array('tid'=>$res['tid'],'sort'=>$res['sort'],'name'=>$res['name'],'value'=>$res['value'],'price'=>$price,'input'=>$res['input'],'inputs'=>$res['inputs'],'alert'=>$res['alert'],'repeat'=>$res['repeat'],'multi'=>$res['multi']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'pay':
	$tid=intval($_POST['tid']);
	$inputvalue=trim(strip_tags(daddslashes($_POST['inputvalue'])));
	$inputvalue2=trim(strip_tags(daddslashes($_POST['inputvalue2'])));
	$inputvalue3=trim(strip_tags(daddslashes($_POST['inputvalue3'])));
	$inputvalue4=trim(strip_tags(daddslashes($_POST['inputvalue4'])));
	$inputvalue5=trim(strip_tags(daddslashes($_POST['inputvalue5'])));
	$num=isset($_POST['num'])?intval($_POST['num']):1;
	$tool=$DB->get_row("select * from shua_tools where tid='$tid' limit 1");
	if($tool && $tool['active']==1){
		if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"你的下单账号已被拉黑，无法下单！"}');
		if($tool['repeat']==0){
			$thtime=date("Y-m-d").' 00:00:00';
			$row=$DB->get_row("select * from shua_orders where tid='$tid' and input='$inputvalue' order by id desc limit 1");
			if($row['input'] && $row['status']==0)
				exit('{"code":-1,"msg":"您今天添加的'.$tool['name'].'正在排队中，请勿重复提交！"}');
			elseif($row['addtime']>$thtime)
				exit('{"code":-1,"msg":"您今天已添加过'.$tool['name'].'，请勿重复提交！"}');
		}
		if($tool['validate']==1 && is_numeric($inputvalue)){
			if(validate_qzone($inputvalue)==false)
				exit('{"code":-1,"msg":"你的QQ空间设置了访问权限，无法下单！"}');
		}
		if($tool['multi']==0 || $num<1)$num = 1;
		$need=$tool['price']*$num;
		if($tool['cost']!=0)$need=$tool['cost']*$num;
		if($need>$userrow['rmb'])exit('{"code":-1,"msg":"你的余额不足，请充值！"}');
		$trade_no=date("YmdHis").rand(111,999);
		$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
		$sql="insert into `shua_pay` (`trade_no`,`type`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`status`) values ('".$trade_no."','rmb','".$tid."','".($userrow['zid']?$userrow['zid']:1)."','".$input."','".$num."','".$tool['name']."','".$need."','".$clientip."','".$cookiesid."','".$date."','0')";
		if($DB->query($sql)){
			exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","need":"'.$need.'"}');
		}else{
			exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
		}
	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
break;
case 'query':
	$qq=daddslashes($_POST['qq']);
	$limit=isset($_POST['limit'])?intval($_POST['limit']):10;
	$rs=$DB->query("SELECT * FROM shua_tools WHERE 1 order by sort asc");
	while($res = $DB->fetch($rs)){
		$shua_func[$res['tid']]=$res['name'];
	}
	if(empty($qq))$sql=" userid='{$cookiesid}'";
	else $sql=" input='{$qq}'";
	$rs=$DB->query("SELECT * FROM shua_orders WHERE{$sql} order by id desc limit $limit");
	$data=array();
	while($res = $DB->fetch($rs)){
		$data[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'name'=>$shua_func[$res['tid']],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'status'=>$res['status']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'getshuoshuo':
	$uin=daddslashes($_GET['uin']);
	if(empty($uin))exit('{"code":-5,"msg":"QQ号不能为空"}');
	$url='http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin='.$uin.'&ftype=0&sort=0&pos=0&num=20&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk=5381';
	$data = get_curl($url);
	$arr=json_decode($data,true);
	if(@array_key_exists('code',$arr) && $arr['code']==0){
		$result=array("code"=>0,"msg"=>"获取说说列表成功！","data"=>$arr['msglist']);
	}else{
		$result=array("code"=>-1,"msg"=>'获取最新说说失败！原因:'.$arr['message']);
	}
	exit(json_encode($result));
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}