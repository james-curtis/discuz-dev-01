<?php
include("./includes/common.php");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if($is_fenzhan == true){
	$price_array = @unserialize($siterow['price']);
}
switch($act){
case 'getclass':
	$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");
	$data = array();
	while($res = $DB->fetch($rs)){
		$data[]=$res;
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'gettool':
	$cid=intval($_GET['cid']);
	$rs=$DB->query("SELECT * FROM shua_tools WHERE cid='$cid' and active=1 order by sort asc");
	$data = array();
	while($res = $DB->fetch($rs)){
		if(is_numeric($price_array[$res['tid']]['price']) && $price_array[$res['tid']]['price']>=$res['cost'] && $res['cost']>0)$price=$price_array[$res['tid']]['price'];
		else $price=$res['price'];
		if($price_array[$res['tid']]['del']==1)continue;
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
		if(is_numeric($price_array[$tid]['price']) && $price_array[$tid]['price']>=$tool['cost'] && $tool['cost']>0)$price=$price_array[$tid]['price'];
		else $price=$tool['price'];
		$need=$price*$num;
		$trade_no=date("YmdHis").rand(111,999);
		$input=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
		$sql="insert into `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`status`) values ('".$trade_no."','".$tid."','".($siterow['zid']?$siterow['zid']:1)."','".$input."','".$num."','".$tool['name']."','".$need."','".$clientip."','".$cookiesid."','".$date."','0')";
		if($DB->query($sql)){
			exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","need":"'.$need.'"}');
		}else{
			exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
		}
	}else{
		exit('{"code":-2,"msg":"该商品不存在"}');
	}
break;
case 'checkkm':
	$km=daddslashes($_POST['km']);
	$myrow=$DB->get_row("select * from shua_kms where km='$km' limit 1");
	if(!$myrow)
	{
		exit('{"code":-1,"msg":"此卡密不存在！"}');
	}
	elseif($myrow['usetime']!=null){
		exit('{"code":-1,"msg":"此卡密已被使用！"}');
	}
	$tool=$DB->get_row("select * from shua_tools where tid='{$myrow['tid']}' limit 1");
	exit('{"code":0,"tid":"'.$tool['tid'].'","cid":"'.$tool['cid'].'","name":"'.$tool['name'].'","alert":"'.$tool['alert'].'","inputname":"'.$tool['input'].'","inputsname":"'.$tool['inputs'].'"}');
break;
case 'card':
	if($conf['iskami']==0)exit('{"code":-1,"msg":"当前站点未开启卡密下单"}');
	$km=daddslashes($_POST['km']);
	$inputvalue=trim(strip_tags(daddslashes($_POST['inputvalue'])));
	$inputvalue2=trim(strip_tags(daddslashes($_POST['inputvalue2'])));
	$inputvalue3=trim(strip_tags(daddslashes($_POST['inputvalue3'])));
	$inputvalue4=trim(strip_tags(daddslashes($_POST['inputvalue4'])));
	$inputvalue5=trim(strip_tags(daddslashes($_POST['inputvalue5'])));
	$myrow=$DB->get_row("select * from shua_kms where km='$km' limit 1");
	if(!$myrow)
	{
		exit('{"code":-1,"msg":"此卡密不存在！"}');
	}
	elseif($myrow['usetime']!=null){
		exit('{"code":-1,"msg":"此卡密已被使用！"}');
	}
	else
	{
		$tid=$myrow['tid'];
		$tool=$DB->get_row("select * from shua_tools where tid='$tid' limit 1");
		if($tool && $tool['active']==1){
			if(in_array($inputvalue,explode("|",$conf['blacklist'])))exit('{"code":-1,"msg":"你的下单账号已被拉黑，无法下单！"}');
			if($tool['repeat']==0){
				$row=$DB->get_row("select * from shua_orders where tid='$tid' and input='$inputvalue' order by id desc limit 1");
				$thtime=date("Y-m-d").' 00:00:00';
				if($row['input'] && $row['status']==0)
					exit('{"code":-1,"msg":"您今天添加的'.$tool['name'].'正在排队中，请勿重复提交！"}');
				elseif($row['addtime']>$thtime)
					exit('{"code":-1,"msg":"您今天已添加过'.$tool['name'].'，请勿重复提交！"}');
			}
			if($tool['validate'] && is_numeric($inputvalue)){
				if(validate_qzone($inputvalue)==false)
					exit('{"code":-1,"msg":"你的QQ空间设置了访问权限，无法下单！"}');
			}
			$srow['tid']=$tid;
			$srow['input']=$inputvalue.($inputvalue2?'|'.$inputvalue2:null).($inputvalue3?'|'.$inputvalue3:null).($inputvalue4?'|'.$inputvalue4:null).($inputvalue5?'|'.$inputvalue5:null);
			$srow['num']=1;
			$srow['zid']=$siterow['zid'];
			$srow['userid']=$cookiesid;
			$srow['trade_no']='kid:'.$myrow['kid'];
			if(processOrder($srow)){
				$row=$DB->get_row("select * from shua_orders where tid='$tid' and input='$inputvalue' order by id desc limit 1");
				$DB->query("update `shua_kms` set `user` ='$inputvalue',`usetime` ='".$date."' where `kid`='{$myrow['kid']}'");
				exit('{"code":0,"msg":"'.$tool['name'].' 下单成功！你可以在进度查询中查看代刷进度","orderid":"'.$row['id'].'"}');
			}else{
				exit('{"code":-1,"msg":"'.$tool['name'].' 下单失败！'.$DB->error().'"}');
			}
		}else{
			exit('{"code":-2,"msg":"该商品不存在"}');
		}
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
		$data[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'name'=>$shua_func[$res['tid']],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status']);
	}
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'fill':
	$orderid=daddslashes($_POST['orderid']);
	$row=$DB->get_row("select * from shua_orders where id='$orderid' limit 1");
	if($row){
		if($row['status']==3){
			$DB->query("update `shua_orders` set `status` ='0',result=NULL where `id`='{$orderid}'");
			$result=array("code"=>0,"msg"=>"已成功补交订单");
		}else{
			$result=array("code"=>0,"msg"=>"该订单不符合补交条件");
		}
	}else{
		$result=array("code"=>-1,"msg"=>"订单不存在");
	}
	exit(json_encode($result));
break;
case 'lqq':
	$qq=daddslashes($_POST['qq']);
	if(empty($qq) || empty($_SESSION['addsalt']) || $_POST['salt']!=$_SESSION['addsalt'])exit('{"code":-5,"msg":"非法请求"}');
	get_curl($conf['lqqapi'].$qq);
	$result=array("code"=>0,"msg"=>"succ");
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
case 'background':
	if(date("Ymd")==$conf['ui_bing_date']){
		$data['code']=1;
		$data['url']=$conf['ui_backgroundurl'];
		if(checkmobile()==true)$data['url']=str_replace('1920x1080','768x1366',$data['url']);
	}else{
		$url = 'http://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1';
		$bing_data = get_curl($url);
		$bing_arr=json_decode($bing_data,true);
		if (!empty($bing_arr['images'][0]['url'])) {
			$data['code']=1;
			$data['url']='https://cn.bing.com'.$bing_arr['images'][0]['url'];
			saveSetting('ui_backgroundurl', $data['url']);
			saveSetting('ui_bing_date', date("Ymd"));
			$CACHE->clear();
			if(checkmobile()==true)$data['url']=str_replace('1920x1080','768x1366',$data['url']);
		}else{
			$data['code']=-1;
		}
	}
	exit(json_encode($data));
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}