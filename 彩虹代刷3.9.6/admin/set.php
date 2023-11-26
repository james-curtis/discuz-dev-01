<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "后台管理";
include "./head.php";
if ($islogin != 1) {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if (!isset($_SESSION['authcode555'])) {
	$string = authcode($_SERVER["HTTP_HOST"] . "||||" . authcode, "ENCODE", "daishuaba_cloudkey1");
	$query = get_curl("http://auth2.cccyun.cc/bin/check.php?string=" . urlencode($string));
	$query = authcode($query, "DECODE", "daishuaba_cloudkey2");
	if ($query = json_decode($query, true)) {
		if ($query["code"] == 1) {
			$_SESSION["authcode555"] = true;
		} else {
			sysmsg("<h3>" . $query["msg"] . "</h3>", true);
		}
	} else {
		$authip = gethostbyname("auth2.cccyun.cc");
		if (substr($authip, 0, 4) == "127." || substr($authip, 0, 8) == "192.168." || substr($authip, 0, 3) == "10.") {
			sysmsg("<h3>您可能是盗版软件的受害者，购买正版请联系QQ65850510</h3>", true);
		}
	}
}
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-xs-12 col-sm-10 col-lg-8 center-block\" style=\"float: none;\">\r\n";
$mod = isset($_GET['mod']) ? $_GET["mod"] : NULL;
if ($mod == "site_n" && $_POST["do"] == "submit") {
	$sitename = $_POST["sitename"];
	$title = $_POST["title"];
	$keywords = $_POST["keywords"];
	$description = $_POST["description"];
	$kfqq = $_POST["kfqq"];
	$iskami = $_POST["iskami"];
	$kaurl = $_POST["kaurl"];
	$blacklist = $_POST["blacklist"];
	$lqqapi = $_POST["lqqapi"];
	$build = $_POST["build"];
	$qqjump = $_POST["qqjump"];
	$apikey = $_POST["apikey"];
	$ui_bing = $_POST["ui_bing"];
	$user = $_POST["user"];
	$pwd = $_POST["pwd"];
	if ($sitename == NULL) {
		showmsg("必填项不能为空！", 3);
	}
	saveSetting("sitename", $sitename);
	saveSetting("title", $title);
	saveSetting("keywords", $keywords);
	saveSetting("description", $description);
	saveSetting("kfqq", $kfqq);
	saveSetting("iskami", $iskami);
	saveSetting("kaurl", $kaurl);
	saveSetting("blacklist", $blacklist);
	saveSetting("lqqapi", $lqqapi);
	saveSetting("build", $build);
	saveSetting("qqjump", $qqjump);
	saveSetting("apikey", $apikey);
	saveSetting("ui_bing", $ui_bing);
	saveSetting("admin_user", $user);
	if (!empty($pwd)) {
		saveSetting("admin_pwd", $pwd);
	}
	$ad = $CACHE->clear();
	if ($ad) {
		showmsg("修改成功！", 1);
	} else {
		showmsg("修改失败！<br/>" . $DB->error(), 4);
	}
} else {
	if ($mod == "site") {
		echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">网站信息配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=site_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">网站名称</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"sitename\" value=\"";
		echo $conf["sitename"];
		echo "\" class=\"form-control\" required/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">标题栏后缀</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"title\" value=\"";
		echo $conf["title"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">关键字</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"keywords\" value=\"";
		echo $conf["keywords"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">网站描述</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"description\" value=\"";
		echo $conf["description"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">客服ＱＱ</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"kfqq\" value=\"";
		echo $conf["kfqq"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">是否开启卡密下单</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"iskami\" default=\"";
		echo $conf["iskami"];
		echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">卡密购买地址</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"kaurl\" value=\"";
		echo $conf["kaurl"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">下单黑名单</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"blacklist\" value=\"";
		echo $conf["blacklist"];
		echo "\" class=\"form-control\" placeholder=\"多个账号之间用|隔开\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">Bing动态壁纸</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"ui_bing\" default=\"";
		echo $conf["ui_bing"];
		echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">网站创建时间</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"date\" name=\"build\" value=\"";
		echo $conf["build"];
		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">拉圈圈赞API</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"lqqapi\" value=\"";
		echo $conf["lqqapi"];
		echo "\" class=\"form-control\" placeholder=\"填写后将在首页显示免费拉圈圈，没有请留空\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">手机QQ打开网站跳转其他浏览器</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"qqjump\" default=\"";
		echo $conf["qqjump"];
		echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">API对接密钥</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"apikey\" value=\"";
		echo $conf["apikey"];
		echo "\" class=\"form-control\" placeholder=\"用于下单软件，随便填写即可\"/></div>\r\n\t</div><br/>\r\n\t<h4>管理员账号设置</h4>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">用户名</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"user\" value=\"";
		echo $conf["admin_user"];
		echo "\" class=\"form-control\" required/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">密码重置</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"pwd\" value=\"\" class=\"form-control\" placeholder=\"不修改请留空\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n\t高级功能：<a href=\"./clone.php\">克隆站点</a>｜<a href=\"./set.php?mod=cleancache\">清理设置缓存</a>｜<a href=\"./set.php?mod=cleanlog\" onclick=\"return confirm('你确实要清空所有社区对接日志吗？');\">清空社区对接日志</a>｜<a href=\"./set.php?mod=cleanpay\" onclick=\"return confirm('你确实要清空1天前的支付记录吗？');\">清空支付记录</a>\r\n\r\n  </form>\r\n</div>\r\n</div>\r\n";
	} else {
		if ($mod == "fenzhan_n" && $_POST["do"] == "submit") {
			$fenzhan_tixian = $_POST["fenzhan_tixian"];
			$fenzhan_skimg = $_POST["fenzhan_skimg"];
			$tixian_rate = $_POST["tixian_rate"];
			$tixian_min = $_POST["tixian_min"];
			$fenzhan_kami = $_POST["fenzhan_kami"];
			$fenzhan_buy = $_POST["fenzhan_buy"];
			$fenzhan_price2 = $_POST["fenzhan_price2"];
			$fenzhan_price = $_POST["fenzhan_price"];
			$fenzhan_free = $_POST["fenzhan_free"];
			$fenzhan_domain = $_POST["fenzhan_domain"];
			$fenzhan_cost2 = $_POST["fenzhan_cost2"];
			$fenzhan_cost = $_POST["fenzhan_cost"];
			$fenzhan_remain = $_POST["fenzhan_remain"];
			saveSetting("fenzhan_tixian", $fenzhan_tixian);
			saveSetting("fenzhan_skimg", $fenzhan_skimg);
			saveSetting("tixian_rate", $tixian_rate);
			saveSetting("tixian_min", $tixian_min);
			saveSetting("fenzhan_kami", $fenzhan_kami);
			saveSetting("fenzhan_buy", $fenzhan_buy);
			saveSetting("fenzhan_price2", $fenzhan_price2);
			saveSetting("fenzhan_price", $fenzhan_price);
			saveSetting("fenzhan_free", $fenzhan_free);
			saveSetting("fenzhan_domain", $fenzhan_domain);
			saveSetting("fenzhan_cost2", $fenzhan_cost2);
			saveSetting("fenzhan_cost", $fenzhan_cost);
			saveSetting("fenzhan_remain", $fenzhan_remain);
			$ad = $CACHE->clear();
			if ($ad) {
				showmsg("修改成功！", 1);
			} else {
				showmsg("修改失败！<br/>" . $DB->error(), 4);
			}
		} else {
			if ($mod == "fenzhan") {
				echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">分站相关配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=fenzhan_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n    <div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">开启提现</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"fenzhan_tixian\" default=\"";
				echo $conf["fenzhan_tixian"];
				echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">是否启用收款图</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"fenzhan_skimg\" default=\"";
				echo $conf["fenzhan_skimg"];
				echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">提现余额比例</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"tixian_rate\" value=\"";
				echo $conf["tixian_rate"];
				echo "\" class=\"form-control\" placeholder=\"填写百分数\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">提现最低余额</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"tixian_min\" value=\"";
				echo $conf["tixian_min"];
				echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">生成卡密功能</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"fenzhan_kami\" default=\"";
				echo $conf["fenzhan_kami"];
				echo "\"><option value=\"1\">开启</option><option value=\"0\">关闭</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">自助开通分站</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"fenzhan_buy\" default=\"";
				echo $conf["fenzhan_buy"];
				echo "\"><option value=\"1\">开启</option><option value=\"0\">关闭</option></select></div>\r\n\t</div><br/>\r\n\t<div id=\"frame_set1\" style=\"";
				echo $conf["fenzhan_buy"] == 0 ? "display:none;" : NULL;
				echo "\">\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">分站开通价格</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"fenzhan_price\" value=\"";
				echo $conf["fenzhan_price"];
				echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">分站成本价格</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"fenzhan_cost\" value=\"";
				echo $conf["fenzhan_cost"];
				echo "\" class=\"form-control\"/><pre>注意：分站成本价格请勿低于初始赠送余额</pre></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">初始赠送余额</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"fenzhan_free\" value=\"";
				echo $conf["fenzhan_free"];
				echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">分站可选择域名</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"fenzhan_domain\" value=\"";
				echo $conf["fenzhan_domain"];
				echo "\" class=\"form-control\"/><pre>多个域名用,隔开！</pre></div>\r\n\t</div><br/>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">主站预留域名</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"fenzhan_remain\" value=\"";
				echo $conf["fenzhan_remain"];
				echo "\" class=\"form-control\"/><pre>主站域名无法被分站绑定，多个域名用,隔开！</pre></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n</div>\r\n<script>\r\n\$(\"select[name='fenzhan_buy']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#frame_set1\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#frame_set1\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n</script>\r\n";
			} else {
				if ($mod == "shequ_n" && $_POST["do"] == "submit") {
					$shequ_status = $_POST["shequ_status"];
					$kameng_status = $_POST["kameng_status"];
					$shequ_tixing = $_POST["shequ_tixing"];
					saveSetting("shequ_status", $shequ_status);
					saveSetting("kameng_status", $kameng_status);
					saveSetting("shequ_tixing", $shequ_tixing);
					$ad = $CACHE->clear();
					if ($ad) {
						showmsg("修改成功！", 1);
					} else {
						showmsg("修改失败！<br/>" . $DB->error(), 4);
					}
				} else {
					if ($mod == "shequ") {
						echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">网站对接配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=shequ_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">社区下单成功后订单状态</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"shequ_status\" default=\"";
						echo $conf["shequ_status"];
						echo "\"><option value=\"1\">已完成（默认）</option><option value=\"2\">正在处理</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">卡盟下单成功后订单状态</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"kameng_status\" default=\"";
						echo $conf["kameng_status"];
						echo "\"><option value=\"1\">已完成（默认）</option><option value=\"2\">正在处理</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">下单失败发送提醒邮件</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"shequ_tixing\" default=\"";
						echo $conf["shequ_tixing"];
						echo "\"><option value=\"0\">关闭</option><option value=\"1\">开启</option></select></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n<div class=\"panel-footer\">\r\n<span class=\"glyphicon glyphicon-info-sign\"></span>\r\n使用此功能，用户下单后会自动提交到社区。<br/>\r\n如果对方网站开启了金盾等防火墙可能导致无法成功提交！\r\n</div>\r\n</div>\r\n";
					} else {
						if ($mod == "cloneset") {
							$key = md5($password_hash . md5(SYS_KEY) . $conf["apikey"]);
							echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">克隆站点配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=shequ_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">克隆密钥</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"key\" value=\"";
							echo $key;
							echo "\" class=\"form-control\" disabled/></div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n<div class=\"panel-footer\">\r\n<span class=\"glyphicon glyphicon-info-sign\"></span>\r\n此密钥是用于其他站点克隆本站商品<br/>\r\n提示：修改API对接密钥可同时重置克隆密钥。<br/>\r\n</div>\r\n</div>\r\n";
						} else {
							if ($mod == "mailtest") {
								if (!empty($conf['mail_name'])) {
									$result = send_mail($conf["mail_name"], "邮件发送测试。", "这是一封测试邮件！<br/><br/>来自：" . $siteurl);
									if ($result == 1) {
										showmsg("邮件发送成功！", 1);
									} else {
										showmsg("邮件发送失败！" . $result, 3);
									}
								} else {
									showmsg("您还未设置邮箱！", 3);
								}
							} else {
								if ($mod == "mail_n" && $_POST["do"] == "submit") {
									$mail_cloud = $_POST["mail_cloud"];
									$mail_smtp = $_POST["mail_smtp"];
									$mail_port = $_POST["mail_port"];
									$mail_name = $mail_cloud == 1 ? $_POST["mail_name2"] : $_POST["mail_name"];
									$mail_pwd = $_POST["mail_pwd"];
									$mail_apiuser = $_POST["mail_apiuser"];
									$mail_apikey = $_POST["mail_apikey"];
									$mail_recv = $_POST["mail_recv"];
									saveSetting("mail_cloud", $mail_cloud);
									saveSetting("mail_smtp", $mail_smtp);
									saveSetting("mail_port", $mail_port);
									saveSetting("mail_name", $mail_name);
									saveSetting("mail_pwd", $mail_pwd);
									saveSetting("mail_apiuser", $mail_apiuser);
									saveSetting("mail_apikey", $mail_apikey);
									saveSetting("mail_recv", $mail_recv);
									$ad = $CACHE->clear();
									if ($ad) {
										showmsg("修改成功！", 1);
									} else {
										showmsg("修改失败！<br/>" . $DB->error(), 4);
									}
								} else {
									if ($mod == "mail") {
										echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">发信邮箱配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=mail_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n    <div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">发信模式</label>\r\n\t  <div class=\"col-sm-10\"><select class=\"form-control\" name=\"mail_cloud\" default=\"";
										echo $conf["mail_cloud"];
										echo "\"><option value=\"0\">普通模式</option><option value=\"1\">搜狐Sendcloud</option></select></div>\r\n\t</div><br/>\r\n\t<div id=\"frame_set1\" style=\"";
										echo $conf["mail_cloud"] == 1 ? "display:none;" : NULL;
										echo "\">\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">SMTP服务器</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_smtp\" value=\"";
										echo $conf["mail_smtp"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">SMTP端口</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_port\" value=\"";
										echo $conf["mail_port"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">邮箱账号</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_name\" value=\"";
										echo $conf["mail_name"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">邮箱密码</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_pwd\" value=\"";
										echo $conf["mail_pwd"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t</div>\r\n\t<div id=\"frame_set2\" style=\"";
										echo $conf["mail_cloud"] == 0 ? "display:none;" : NULL;
										echo "\">\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">API_USER</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_apiuser\" value=\"";
										echo $conf["mail_apiuser"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">API_KEY</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_apikey\" value=\"";
										echo $conf["mail_apikey"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">发信邮箱</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_name2\" value=\"";
										echo $conf["mail_name"];
										echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">收信邮箱</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"mail_recv\" value=\"";
										echo $conf["mail_recv"];
										echo "\" class=\"form-control\" placeholder=\"不填默认为发信邮箱\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>";
										if ($conf["mail_name"]) {
											echo "[<a href=\"set.php?mod=mailtest\">给 ";
											echo $conf["mail_name"];
											echo " 发一封测试邮件</a>]";
										}
										echo "\t </div><br/>\r\n\t</div>\r\n  </form>\r\n</div>\r\n<div class=\"panel-footer\">\r\n<span class=\"glyphicon glyphicon-info-sign\"></span>\r\n此功能为用户下单时给自己发邮件提醒。<br/>使用普通模式发信时，建议使用QQ邮箱，SMTP服务器smtp.qq.com，端口465，密码不是QQ密码也不是邮箱独立密码，是QQ邮箱设置界面生成的<a href=\"http://service.mail.qq.com/cgi-bin/help?subtype=1&&no=1001256&&id=28\"  target=\"_blank\" rel=\"noreferrer\">授权码</a>。为确保发信成功率，发信邮箱和收信邮箱最好同一个\r\n</div>\r\n</div>\r\n<script>\r\n\$(\"select[name='mail_cloud']\").change(function(){\r\n\tif(\$(this).val() == 0){\r\n\t\t\$(\"#frame_set1\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#frame_set2\").css(\"display\",\"none\");\r\n\t}else{\r\n\t\t\$(\"#frame_set1\").css(\"display\",\"none\");\r\n\t\t\$(\"#frame_set2\").css(\"display\",\"inherit\");\r\n\t}\r\n});\r\n</script>\r\n";
									} else {
										if ($mod == "gonggao_n" && $_POST["do"] == "submit") {
											$anounce = $_POST["anounce"];
											$modal = $_POST["modal"];
											$bottom = $_POST["bottom"];
											$gg_search = $_POST["gg_search"];
											$gg_panel = $_POST["gg_panel"];
											$chatframe = $_POST["chatframe"];
											$appalert = $_POST["appalert"];
											saveSetting("anounce", $anounce);
											saveSetting("modal", $modal);
											saveSetting("bottom", $bottom);
											saveSetting("gg_search", $gg_search);
											saveSetting("chatframe", $chatframe);
											saveSetting("gg_panel", $gg_panel);
											saveSetting("appalert", $appalert);
											$ad = $CACHE->clear();
											if ($ad) {
												showmsg("修改成功！", 1);
											} else {
												showmsg("修改失败！<br/>" . $DB->error(), 4);
											}
										} else {
											if ($mod == "gonggao") {
												echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">网站公告配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=gonggao_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">首页公告</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"anounce\" rows=\"6\">";
												echo htmlspecialchars($conf["anounce"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">首页弹出公告</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"modal\" rows=\"5\">";
												echo htmlspecialchars($conf["modal"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">首页底部排版</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"bottom\" rows=\"5\">";
												echo htmlspecialchars($conf["bottom"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">订单查询页面公告</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"gg_search\" rows=\"5\">";
												echo htmlspecialchars($conf["gg_search"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">分站后台公告</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"gg_panel\" rows=\"5\">";
												echo htmlspecialchars($conf["gg_panel"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">首页聊天代码</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"chatframe\" rows=\"5\">";
												echo htmlspecialchars($conf["chatframe"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">APP启动弹出内容</label>\r\n\t  <div class=\"col-sm-10\"><textarea class=\"form-control\" name=\"appalert\" rows=\"3\">";
												echo htmlspecialchars($conf["appalert"]);
												echo "</textarea></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n<div class=\"panel-footer\">\r\n<span class=\"glyphicon glyphicon-info-sign\"></span>\r\n实用工具：<a href=\"set.php?mod=copygg\">一键复制其他站点排版</a>｜<a href=\"http://www.w3school.com.cn/tiy/t.asp?f=html_basic\" target=\"_blank\" rel=\"noreferrer\">HTML在线测试</a>｜<a href=\"http://pic.xiaojianjian.net/\" target=\"_blank\" rel=\"noreferrer\">图床</a>｜<a href=\"http://music.cccyun.cc/\" target=\"_blank\" rel=\"noreferrer\">音乐外链</a><br/>\r\n聊天代码获取地址：<a href=\"http://www.uyan.cc/getcode/mobile\" target=\"_blank\" rel=\"noreferrer\">友言</a>、<a href=\"http://changyan.kuaizhan.com/\" target=\"_blank\" rel=\"noreferrer\">搜狐畅言</a>\r\n</div>\r\n</div>\r\n";
											} else {
												if ($mod == "copygg_n" && $_POST["do"] == "submit") {
													$url = $_POST["url"];
													$content = $_POST["content"];
													$url_arr = parse_url($url);
													if ($url_arr["host"] == $_SERVER["HTTP_HOST"]) {
														showmsg("无法自己复制自己", 3);
													}
													$data = get_curl($url . "api.php?act=siteinfo");
													$arr = json_decode($data, true);
													if (array_key_exists("sitename", $arr)) {
														if (in_array("anounce", $content)) {
															saveSetting("anounce", str_replace($arr["kfqq"], $conf["kfqq"], $arr["anounce"]));
														}
														if (in_array("modal", $content)) {
															saveSetting("modal", str_replace($arr["kfqq"], $conf["kfqq"], $arr["modal"]));
														}
														if (in_array("bottom", $content)) {
															saveSetting("bottom", str_replace($arr["kfqq"], $conf["kfqq"], $arr["bottom"]));
														}
														$ad = $CACHE->clear();
														if ($ad) {
															showmsg("修改成功！", 1);
														} else {
															showmsg("修改失败！<br/>" . $DB->error(), 4);
														}
													} else {
														showmsg("获取数据失败，对方网站无法连接或非彩虹代刷系统。", 4);
													}
												} else {
													if ($mod == "copygg") {
														echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">一键复制其他站点排版</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=copygg_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">站点URL</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"url\" value=\"\" class=\"form-control\" placeholder=\"http://www.qq.com/\" required/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">复制内容</label>\r\n\t  <div class=\"col-sm-10\"><label><input name=\"content[]\" type=\"checkbox\" value=\"anounce\" checked/> 首页公告</label><br/><label><input name=\"content[]\" type=\"checkbox\" value=\"modal\" checked/> 弹出公告</label><br/><label><input name=\"content[]\" type=\"checkbox\" value=\"bottom\" checked/> 底部排版</label></div>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n</div>\r\n";
													} else {
														if ($mod == "pay_n" && $_POST["do"] == "submit") {
															if (file_exists("pay.lock") && !empty($conf['epay_pid']) && is_numeric($conf["epay_pid"]) && strlen($conf["epay_key"]) > 20 && ($_POST["epay_pid"] != $conf["epay_pid"] || $_POST["epay_key"] != $conf["epay_key"])) {
																showmsg("为保障你的资金安全，如需修改支付商户和密钥，请删除<font color=red> admin/pay.lock </font>文件后再修改！", 3);
															}
															saveSetting("alipay_api", $_POST["alipay_api"]);
															saveSetting("alipay_pid", $_POST["alipay_pid"]);
															saveSetting("alipay_key", $_POST["alipay_key"]);
															saveSetting("alipay2_api", $_POST["alipay2_api"]);
															saveSetting("tenpay_api", $_POST["tenpay_api"]);
															saveSetting("tenpay_pid", $_POST["tenpay_pid"]);
															saveSetting("tenpay_key", $_POST["tenpay_key"]);
															saveSetting("qqpay_api", $_POST["qqpay_api"]);
															saveSetting("wxpay_api", $_POST["wxpay_api"]);
															saveSetting("payapi", $_POST["payapi"]);
															saveSetting("epay_pid", $_POST["epay_pid"]);
															saveSetting("epay_key", $_POST["epay_key"]);
															$ad = $CACHE->clear();
															if ($ad) {
																showmsg("修改成功！", 1);
															} else {
																showmsg("修改失败！<br/>" . $DB->error(), 4);
															}
														} else {
															if ($mod == "pay") {
																echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">支付接口配置</h3></div>\r\n<div class=\"panel-body\">\r\n  <form action=\"./set.php?mod=pay_n\" method=\"post\" class=\"form-horizontal\" role=\"form\"><input type=\"hidden\" name=\"do\" value=\"submit\"/>\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">支付宝即时到账</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"alipay_api\" default=\"";
																echo $conf["alipay_api"];
																echo "\"><option value=\"0\">关闭</option><option value=\"1\">支付宝官方即时到账接口</option><option value=\"2\">彩虹易支付免签约接口</option></select>\r\n\t\t\t<pre id=\"payapi_06\"  style=\"";
																if ($conf["alipay_api"] != 3) {
																	echo "display:none;";
																}
																echo "\"><font color=\"green\">*支付宝当面付接口配置请修改other/f2fpay/config.php</font></pre>\r\n\t\t</div>\r\n\t</div>\r\n\t<div id=\"payapi_01\" style=\"";
																if ($conf["alipay_api"] != 1) {
																	echo "display:none;";
																}
																echo "\">\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">合作者身份(PID)</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"alipay_pid\" class=\"form-control\" value=\"";
																echo $conf["alipay_pid"];
																echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">安全校验码(Key)</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"alipay_key\" class=\"form-control\" value=\"";
																echo $conf["alipay_key"];
																echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">支付宝手机网站支付</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"alipay2_api\" default=\"";
																echo $conf["alipay2_api"];
																echo "\"><option value=\"0\">关闭</option><option value=\"1\">支付宝手机网站支付接口</option></select>\r\n\t\t\t<pre id=\"payapi_02\"  style=\"";
																if ($conf["alipay2_api"] != 1) {
																	echo "display:none;";
																}
																echo "\">相关信息与以上支付宝即时到账接口一致，开启前请确保已开通支付宝手机支付，否则会导致手机用户无法支付！</pre>\r\n\t\t</div>\r\n\t</div>\r\n\t</div>\r\n\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">财付通即时到账</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"tenpay_api\" default=\"";
																echo $conf["tenpay_api"];
																echo "\"><option value=\"0\">关闭</option><option value=\"1\">财付通官方支付接口</option><option value=\"2\">彩虹易支付免签约接口</option></select>\r\n\t\t</div>\r\n\t</div>\r\n\t<div id=\"payapi_03\" style=\"";
																if ($conf["tenpay_api"] != 1) {
																	echo "display:none;";
																}
																echo "\">\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">财付通商户号</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"tenpay_pid\" class=\"form-control\"\r\n\t\t\t\t   value=\"";
																echo $conf["tenpay_pid"];
																echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">财付通密钥</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"tenpay_key\" class=\"form-control\" value=\"";
																echo $conf["tenpay_key"];
																echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t</div>\r\n\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">QQ钱包支付接口</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"qqpay_api\" default=\"";
																echo $conf["qqpay_api"];
																echo "\"><option value=\"0\">关闭</option><option value=\"1\">QQ钱包官方支付接口</option><option value=\"2\">彩虹易支付免签约接口</option></select>\r\n\t\t\t<pre id=\"payapi_05\"  style=\"";
																if ($conf["qqpay_api"] != 1) {
																	echo "display:none;";
																}
																echo "\"><font color=\"green\">*QQ钱包支付接口配置请修改other/qqpay/qpayMch.config.php</font></pre>\r\n\t\t</div>\r\n\t</div>\r\n\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">微信支付接口</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"wxpay_api\" default=\"";
																echo $conf["wxpay_api"];
																echo "\"><option value=\"0\">关闭</option><option value=\"1\">微信官方扫码+公众号支付接口</option><option value=\"3\">微信官方扫码+H5支付接口</option><option value=\"2\">彩虹易支付免签约接口</option></select>\r\n\t\t\t<pre id=\"payapi_04\"  style=\"";
																if ($conf["wxpay_api"] != 1 && $conf["wxpay_api"] != 3) {
																	echo "display:none;";
																}
																echo "\"><font color=\"green\">*微信支付接口配置请修改other/wxpay/WxPay.Config.php</font></pre>\r\n\t\t</div>\r\n\t</div>\r\n\t";
																if ($conf["alipay_api"] == 2 || $conf["tenpay_api"] == 2 || $conf["tenpay_api"] == 2 || $conf["wxpay_api"] == 2) {
																	echo "\t";
																	if (isset($payapi_name)) {
																		echo "\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">彩虹易支付接入商</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"payapi\" default=\"";
																		echo $conf["payapi"];
																		echo "\"><option value=\"0\">彩虹易支付官方</option><option value=\"1\">";
																		echo $payapi_name;
																		echo "</option></select>\r\n\t\t</div>\r\n\t</div>\r\n\t";
																	} else {
																		echo "\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">彩虹易支付接入商</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<select class=\"form-control\" name=\"payapi\" default=\"";
																		echo $conf["payapi"];
																		echo "\"><option value=\"0\">彩虹易支付官方</option><option value=\"10\">数掘科技支付</option><option value=\"2\">BL云支付(普通)</option><option value=\"3\">BL云支付(全能)</option><option value=\"11\">酷客云付通</option><option value=\"4\">阿生易支付</option><option value=\"13\">ABC云支付</option><option value=\"14\">我爱云支付</option><option value=\"5\">残雪易支付</option><option value=\"6\">Hack易支付</option><option value=\"8\">就爱支付</option><option value=\"9\">QQL易支付</option><option value=\"1\">新趣易支付</option><option value=\"12\">发傲支付</option></select>\r\n\t\t</div>\r\n\t</div>\r\n\t";
																	}
																	echo "\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">彩虹易支付商户ID</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"epay_pid\" class=\"form-control\"\r\n\t\t\t\t   value=\"";
																	echo $conf["epay_pid"];
																	echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"form-group\">\r\n\t\t<label class=\"col-lg-3 control-label\">彩虹易支付商户密钥</label>\r\n\t\t<div class=\"col-lg-8\">\r\n\t\t\t<input type=\"text\" name=\"epay_key\" class=\"form-control\" value=\"";
																	echo $conf["epay_key"];
																	echo "\">\r\n\t\t</div>\r\n\t</div>\r\n\t<div class=\"col-lg-8\"><a href=\"set.php?mod=epay\">进入易支付结算设置及订单查询页面</a><br/><br/></div>\r\n\t";
																}
																echo "\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-3 col-sm-8\"><input type=\"submit\" name=\"submit\" value=\"修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t</div>\r\n  </form>\r\n</div>\r\n</div>\r\n<script>\r\n\$(\"select[name=\\'alipay_api\\']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#payapi_01\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#payapi_06\").css(\"display\",\"none\");\r\n\t}else if(\$(this).val() == 3){\r\n\t\t\$(\"#payapi_01\").css(\"display\",\"none\");\r\n\t\t\$(\"#payapi_06\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#payapi_01\").css(\"display\",\"none\");\r\n\t\t\$(\"#payapi_06\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name=\\'tenpay_api\\']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#payapi_03\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#payapi_03\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name=\\'wxpay_api\\']\").change(function(){\r\n\tif(\$(this).val() == 1 || \$(this).val() == 3){\r\n\t\t\$(\"#payapi_04\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#payapi_04\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name=\\'qqpay_api\\']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#payapi_05\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#payapi_05\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name=\\'alipay2_api\\']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#payapi_02\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#payapi_02\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n</script>\r\n";
															} else {
																if ($mod == "epay_n") {
																	$account = $_POST["account"];
																	$username = $_POST["username"];
																	if ($account == NULL || $username == NULL) {
																		showmsg("保存错误,请确保每项都不为空!", 3);
																	} else {
																		$data = get_curl($payapi . "api.php?act=change&pid=" . $conf["epay_pid"] . "&key=" . $conf["epay_key"] . "&account=" . $account . "&username=" . $username . "&url=" . $_SERVER["HTTP_HOST"]);
																		$arr = json_decode($data, true);
																		if ($arr["code"] == 1) {
																			showmsg("修改成功!", 1);
																		} else {
																			showmsg($arr["msg"]);
																		}
																	}
																} else {
																	if ($mod == "epay") {
																		if (!empty($conf['epay_pid']) && !empty($conf['epay_key'])) {
																			$data = get_curl($payapi . "api.php?act=query&pid=" . $conf["epay_pid"] . "&key=" . $conf["epay_key"] . "&url=" . $_SERVER["HTTP_HOST"]);
																			$arr = json_decode($data, true);
																			if ($arr["code"] == 0 - 2) {
																				showmsg("易支付KEY校验失败！");
																			} else {
																				if (!array_key_exists("code", $arr)) {
																					showmsg("获取失败，请刷新重试！");
																				}
																			}
																		} else {
																			showmsg("你还未填写彩虹易支付商户ID和密钥，请返回填写！");
																		}
																		if (array_key_exists("active", $arr) && $arr["active"] == 0) {
																			showmsg("该商户已被封禁");
																		}
																		$key = substr($arr["key"], 0, 8) . "****************" . substr($arr["key"], 24, 32);
																		if (!file_exists("pay.lock")) {
																			@file_put_contents("pay.lock", "pay.lock");
																		}
																		echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">彩虹易支付设置</h3></div>\r\n<div class=\"panel-body\">\r\n<ul class=\"nav nav-tabs\"><li class=\"active\"><a href=\"#\">彩虹易支付设置</a></li><li><a href=\"./set.php?mod=epay_order\">订单记录</a></li><li><a href=\"./set.php?mod=epay_settle\">结算记录</a></li></ul>\r\n  <form action=\"./set.php?mod=epay_n\" method=\"post\" class=\"form-horizontal\" role=\"form\">\r\n    <h4>商户信息查看：</h4>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">商户ID</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"pid\" value=\"";
																		echo $arr["pid"];
																		echo "\" class=\"form-control\" disabled/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">商户KEY</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"key\" value=\"";
																		echo $key;
																		echo "\" class=\"form-control\" disabled/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">商户余额</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"money\" value=\"";
																		echo $arr["money"];
																		echo "\" class=\"form-control\" disabled/></div>\r\n\t</div><br/>\r\n\t<h4>收款账号设置：</h4>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">支付宝账号</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"account\" value=\"";
																		echo $arr["account"];
																		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <label class=\"col-sm-2 control-label\">支付宝姓名</label>\r\n\t  <div class=\"col-sm-10\"><input type=\"text\" name=\"username\" value=\"";
																		echo $arr["username"];
																		echo "\" class=\"form-control\"/></div>\r\n\t</div><br/>\r\n\t<div class=\"form-group\">\r\n\t  <div class=\"col-sm-offset-2 col-sm-10\"><input type=\"submit\" name=\"submit\" value=\"确定修改\" class=\"btn btn-primary form-control\"/><br/>\r\n\t </div>\r\n\t </div>\r\n\t <h4><span class=\"glyphicon glyphicon-info-sign\"></span> 注意事项</h4>\r\n1.支付宝账户和支付宝真实姓名请仔细核对，一旦错误将无法结算到账！<br/>2.每笔交易会有";
																		echo 100 - $arr["money_rate"];
																		echo "%的手续费，这个手续费是支付宝、微信和财付通收取的，非本接口收取。<br/>3.结算是通过支付宝进行结算，每天满";
																		echo $arr["settle_money"];
																		echo "元自动结算，如需人工结算需要扣除";
																		echo $arr["settle_fee"];
																		echo "元手续费\r\n  </form>\r\n</div>\r\n</div>\r\n";
																	} else {
																		if ($mod == "epay_settle") {
																			$data = get_curl($payapi . "api.php?act=settle&pid=" . $conf["epay_pid"] . "&key=" . $conf["epay_key"] . "&limit=20&url=" . $_SERVER["HTTP_HOST"]);
																			$arr = json_decode($data, true);
																			if ($arr["code"] == 0 - 2) {
																				showmsg("易支付KEY校验失败！");
																			}
																			echo "<div class=\"panel panel-primary\"><div class=\"panel-heading w h\"><h3 class=\"panel-title\">彩虹易支付结算记录</h3></div>\r\n\t<div class=\"table-responsive\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>ID</th><th>结算账号</th><th>结算金额</th><th>手续费</th><th>结算时间</th></tr></thead>\r\n          <tbody>";
																			foreach ($arr["data"] as $res) {
																				echo "<tr><td><b>" . $res["id"] . "</b></td><td>" . $res["account"] . "</td><td><b>" . $res["money"] . "</b></td><td><b>" . $res["fee"] . "</b></td><td>" . $res["time"] . "</td></tr>";
																			}
																			echo "</tbody>\r\n        </table>\r\n      </div>\r\n\t  </div>";
																		} else {
																			if ($mod == "epay_order") {
																				$data = get_curl($payapi . "api.php?act=orders&pid=" . $conf["epay_pid"] . "&key=" . $conf["epay_key"] . "&limit=30&url=" . $_SERVER["HTTP_HOST"]);
																				$arr = json_decode($data, true);
																				if ($arr["code"] == 0 - 2) {
																					showmsg("易支付KEY校验失败！");
																				}
																				echo "<div class=\"panel panel-primary\"><div class=\"panel-heading\"><h3 class=\"panel-title\">彩虹易支付订单记录</h3></div>订单只展示前30条[<a href=\"set.php?mod=epay\">返回</a>]\r\n\t<div class=\"table-responsive\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>交易号/商户订单号</th><th>付款方式</th><th>商品名称/金额</th><th>创建时间/完成时间</th><th>状态</th></tr></thead>\r\n          <tbody>";
																				foreach ($arr["data"] as $res) {
																					echo "<tr><td>" . $res["trade_no"] . "<br/>" . $res["out_trade_no"] . "</td><td>" . $res["type"] . "</td><td>" . $res["name"] . "<br/>￥ <b>" . $res["money"] . "</b></td><td>" . $res["addtime"] . "<br/>" . $res["endtime"] . "</td><td>" . ($res["status"] == 1 ? "<font color=green>已完成</font>" : "<font color=red>未完成</font>") . "</td></tr>";
																				}
																				echo "</tbody>\r\n        </table>\r\n      </div>\r\n\t  </div>";
																			} else {
																				if ($mod == "upimg") {
																					echo "<div class=\"panel panel-primary\"><div class=\"panel-heading\"><h3 class=\"panel-title\">更改首页LOGO<a class=\"label label-info pull-right\" href=\"set.php?mod=upbgimg\">更改背景图</a></h3></div><div class=\"panel-body\">";
																					if ($_POST["s"] == 1) {
																						$extension = explode(".", $_FILES["file"]["name"]);
																						if (($length = count($extension)) > 1) {
																							$ext = strtolower($extension[$length - 1]);
																						}
																						if ($ext == "png" || $ext == "gif" || $ext == "jpg" || $ext == "jpeg" || $ext == "bmp") {
																							$ext = "png";
																						}
																						copy($_FILES["file"]["tmp_name"], ROOT . "assets/img/logo." . $ext);
																						echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
																					}
																					echo "<form action=\"set.php?mod=upimg\" method=\"POST\" enctype=\"multipart/form-data\"><label for=\"file\"></label><input type=\"file\" name=\"file\" id=\"file\" /><input type=\"hidden\" name=\"s\" value=\"1\" /><br><input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确认上传\" /></form><br>现在的图片：<br><img src=\"../assets/img/logo.png?r=" . rand(10000, 99999) . "\" style=\"max-width:100%\">";
																					echo "</div></div>";
																				} else {
																					if ($mod == "upbgimg") {
																						echo "<div class=\"panel panel-primary\"><div class=\"panel-heading\"><h3 class=\"panel-title\">更改首页背景图<a class=\"label label-info pull-right\" href=\"set.php?mod=upimg\">更改LOGO</a></h3></div><div class=\"panel-body\">";
																						if ($_POST["s"] == 1) {
																							$extension = explode(".", $_FILES["file"]["name"]);
																							if (($length = count($extension)) > 1) {
																								$ext = strtolower($extension[$length - 1]);
																							}
																							if ($ext == "png" || $ext == "gif" || $ext == "jpg" || $ext == "jpeg" || $ext == "bmp") {
																								$ext = "png";
																							}
																							copy($_FILES["file"]["tmp_name"], ROOT . "assets/img/bj." . $ext);
																							echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
																						}
																						echo "<form action=\"set.php?mod=upbgimg\" method=\"POST\" enctype=\"multipart/form-data\"><label for=\"file\"></label><input type=\"file\" name=\"file\" id=\"file\" /><input type=\"hidden\" name=\"s\" value=\"1\" /><br><input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确认上传\" /></form><br>现在的图片：<br><img src=\"../assets/img/bj.png?r=" . rand(10000, 99999) . "\" style=\"max-width:100%\">";
																						echo "</div></div>";
																					} else {
																						if ($mod == "cleancache") {
																							$CACHE->clear();
																							showmsg("清理系统设置缓存成功！", 1);
																						} else {
																							if ($mod == "cleanlog") {
																								$DB->query("TRUNCATE TABLE `shua_logs`");
																								showmsg("清空社区对接日志成功！", 1);
																							} else {
																								if ($mod == "cleanpay") {
																									$DB->query("DELETE FROM `shua_pay` WHERE addtime<'" . date("Y-m-d H:i:s", strtotime("-1 days")) . "'");
																									showmsg("清空支付记录成功！", 1);
																								}
																							}
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
echo "<script>\r\nvar items = \$(\"select[default]\");\r\nfor (i = 0; i < items.length; i++) {\r\n\t\$(items[i]).val(\$(items[i]).attr(\"default\")||0);\r\n}\r\n</script>\r\n    </div>\r\n  </div>";