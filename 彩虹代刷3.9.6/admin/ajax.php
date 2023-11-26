<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

switch($act){
case 'order':
	$id=intval($_GET['id']);
	$rows=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$tool=$DB->get_row("select * from shua_tools where tid='{$rows['tid']}' limit 1");
	if(strpos($rows['tradeno'],'kid')!==false){
		$kid=explode(':',$rows['tradeno']);
		$kid=$kid[1];
		$trade=$DB->get_row("select * from shua_kms where kid='$kid' limit 1");
		$trade['type']='卡密';
		$addstr='<li class="list-group-item"><b>使用卡密：</b>'.$trade['km'].'</li>';
	}elseif(!empty($rows['tradeno'])){
		$trade=$DB->get_row("select * from shua_pay where trade_no='{$rows['tradeno']}' limit 1");
		$addstr='<li class="list-group-item"><b>订单号：</b>'.$trade['trade_no'].'</li><li class="list-group-item"><b>支付金额：</b>'.$trade['money'].'</li><li class="list-group-item"><b>支付IP：</b><a href="http://m.ip138.com/ip.asp?ip='.$trade['ip'].'" target="_blank">'.$trade['ip'].'</a></li>';
	}else{
		$trade['type']='默认';
	}
	$input=$tool['input']?$tool['input']:'下单QQ';
	$inputs=explode('|',$tool['inputs']);
	$value=$tool['value']>0?$tool['value']:1;
	$data = '<li class="list-group-item"><b>商品名称：</b>'.$tool['name'].'</li><li class="list-group-item"><b>下单数据：</b><br/>'.$input.'：'.$rows['input'].($rows['input2']?'<br/>'.$inputs[0].'：'.$rows['input2']:null).($rows['input3']?'<br/>'.$inputs[1].'：'.$rows['input3']:null).($rows['input4']?'<br/>'.$inputs[2].'：'.$rows['input4']:null).($rows['input5']?'<br/>'.$inputs[3].'：'.$rows['input5']:null).'</li><li class="list-group-item"><b>下单数量：</b>'.($rows['value']*$value).'</li><li class="list-group-item"><b>站点ID：</b>'.$rows['zid'].'</li><li class="list-group-item"><b>下单时间：</b>'.$rows['addtime'].'</li><li class="list-group-item"><b>购买方式：</b>'.$trade['type'].'</li>'.$addstr;
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'order2':
	$id=intval($_GET['id']);
	$rows=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$tool=$DB->get_row("select * from shua_tools where tid='{$rows['tid']}' limit 1");
	$input=$tool['input']?$tool['input']:'下单ＱＱ';
	$inputs=explode('|',$tool['inputs']);
	$data = '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname">'.$input.'</div><input type="text" id="inputvalue" value="'.$rows['input'].'" class="form-control" required/></div></div>';
	if($inputs[0])$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname2">'.$inputs[0].'</div><input type="text" id="inputvalue2" value="'.$rows['input2'].'" class="form-control" required/></div></div>';
	if($inputs[1])$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname3">'.$inputs[1].'</div><input type="text" id="inputvalue3" value="'.$rows['input3'].'" class="form-control" required/></div></div>';
	if($inputs[2])$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname4">'.$inputs[2].'</div><input type="text" id="inputvalue4" value="'.$rows['input4'].'" class="form-control" required/></div></div>';
	if($inputs[3])$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname5">'.$inputs[3].'</div><input type="text" id="inputvalue5" value="'.$rows['input5'].'" class="form-control" required/></div></div>';
	$data .= '<input type="submit" id="save" onclick="saveOrder('.$id.')" class="btn btn-primary btn-block" value="保存">';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'order3':
	$id=intval($_GET['id']);
	$rows=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$data = '<div class="form-group"><div class="input-group"><div class="input-group-addon">份数</div><input type="text" id="num" value="'.$rows['value'].'" class="form-control" required/></div></div>';
	$data .= '<input type="submit" id="save" onclick="saveOrderNum('.$id.')" class="btn btn-primary btn-block" value="保存">';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'editOrder':
	$id=intval($_POST['id']);
	$inputvalue=trim(daddslashes($_POST['inputvalue']));
	$inputvalue2=trim(daddslashes($_POST['inputvalue2']));
	$inputvalue3=trim(daddslashes($_POST['inputvalue3']));
	$inputvalue4=trim(daddslashes($_POST['inputvalue4']));
	$inputvalue5=trim(daddslashes($_POST['inputvalue5']));
	$sds=$DB->query("update `shua_orders` set `input`='$inputvalue',`input2`='$inputvalue2',`input3`='$inputvalue3',`input4`='$inputvalue4',`input5`='$inputvalue5' where `id`='$id'");
	if($sds)
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'editOrderNum':
	$id=intval($_POST['id']);
	$num=intval($_POST['num']);
	$sds=$DB->query("update `shua_orders` set `value`='$num' where `id`='$id'");
	if($sds)
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'result':
	$id=intval($_POST['id']);
	$rows=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	exit('{"code":0,"result":"'.$rows['result'].'"}');
break;
case 'setresult':
	$id=intval($_POST['id']);
	$rows=$DB->get_row("select * from shua_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$result=$_POST['result'];
	if($DB->query("update shua_orders set result='$result' where id='{$id}'"))
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'checkshequ':
	$url = $_POST['url'];
	if(gethostbyname($url)=='127.0.0.1'){
		exit('{"code":0}');
	}else{
		exit('{"code":1}');
	}
break;
case 'checkclone':
	$url = $_POST['url'];
	$url_arr = parse_url($url);
	if($url_arr['host']==$_SERVER['HTTP_HOST'])exit('{"code":2}');
	$data = get_curl($url.'api.php?act=clone');
	$arr = json_decode($data,true);
	if(array_key_exists('code',$arr) && array_key_exists('msg',$arr)){
		exit('{"code":1}');
	}else{
		exit('{"code":0}');
	}
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}