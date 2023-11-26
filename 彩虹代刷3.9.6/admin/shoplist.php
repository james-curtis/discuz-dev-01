<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "商品管理";
include "./head.php";
if ($islogin != 1) {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if (!isset($_SESSION['authcode555'])) {
	$string = authcode($_SERVER["HTTP_HOST"] . "||||" . authcode, "ENCODE", "daishuaba_cloudkey1");
	$query = curl_get("http://auth2.cccyun.cc/bin/check.php?string=" . urlencode($string));
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
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-sm-12 col-md-10 center-block\" style=\"float: none;\">\r\n";
$rs = $DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");
$select = "<option value=\"0\">未分类</option>";
$shua_class[0] = "未分类";
while ($res = $DB->fetch($rs)) {
	$shua_class[$res["cid"]] = $res["name"];
	$select .= "<option value=\"" . $res["cid"] . "\">" . $res["name"] . "</option>";
}
$rs = $DB->query("SELECT * FROM shua_shequ order by id asc");
$shequselect = "";
while ($res = $DB->fetch($rs)) {
	$shequselect .= "<option value=\"" . $res["id"] . "\" type=\"" . $res["type"] . "\">" . $res["url"] . "</option>";
}
$my = isset($_GET['my']) ? $_GET["my"] : NULL;
if ($my == "add") {
	echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">添加一个商品</h3></div>";
	echo "<div class=\"panel-body\">";
	echo "<form action=\"./shoplist.php?my=add_submit\" method=\"POST\">\r\n<input type=\"hidden\" name=\"backurl\" value=\"" . $_SERVER["HTTP_REFERER"] . "\"/>\r\n<div class=\"form-group\">\r\n<label>*商品分类:</label><br>\r\n<select name=\"cid\" class=\"form-control\" default=\"" . $_GET["cid"] . "\">" . $select . "</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*商品名称:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"name\" value=\"\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*销售价格:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"price\" value=\"\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>代理价格:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"cost\" value=\"\" placeholder=\"留空则无法代理\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>第一个输入框标题:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"input\" value=\"\" placeholder=\"留空默认为“下单ＱＱ”\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>更多输入框标题:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"inputs\" value=\"\" placeholder=\"留空则不显示更多输入框\">\r\n<pre><font color=\"green\">多个输入框请用|隔开(不能超过4个)</font></pre>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>提示内容:</label>(没有请留空)<br>\r\n<input type=\"text\" class=\"form-control\" name=\"alert\" value=\"\" placeholder=\"当选择该商品时自动弹出提示\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>默认数量信息:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"value\" value=\"\" placeholder=\"用于对接社区使用或导出时显示\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*显示数量选择框:</label><br>\r\n<select class=\"form-control\" name=\"multi\"><option value=\"0\">0_否</option><option value=\"1\">1_是</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*允许重复下单:</label><br>\r\n<select class=\"form-control\" name=\"repeat\"><option value=\"0\">0_否</option><option value=\"1\">1_是</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*验证操作:</label><br>\r\n<select class=\"form-control\" name=\"validate\"><option value=\"0\">不开启验证</option><option value=\"1\">验证QQ空间是否有访问权限</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*排序(数字越小越靠前):</label><br>\r\n<input type=\"number\" class=\"form-control\" name=\"sort\" value=\"10\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*是否上架:</label><br>\r\n<select class=\"form-control\" name=\"active\"><option value=\"1\">1_是</option><option value=\"0\">0_否</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>购买成功后的动作:</label><br>\r\n<select class=\"form-control\" name=\"is_curl\"><option value=\"0\">0_无</option><option value=\"1\">1_自动访问URL</option><option value=\"2\">2_自动提交到社区/卡盟</option><option value=\"3\">3_自动发送提醒邮件</option></select>\r\n</div>\r\n<div id=\"curl_display1\" style=\"display:none;\">\r\n<div class=\"form-group\">\r\n<label>自动访问的URL:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"curl\" id=\"curl\" value=\"\">\r\n</div>\r\n<font color=\"green\">URL里面可以加变量：<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input]');return false\">[input]</a>&nbsp;第一个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input2]');return false\">[input2]</a>&nbsp;第二个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input3]');return false\">[input3]</a>&nbsp;第三个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input4]');return false\">[input4]</a>&nbsp;第四个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[name]');return false\">[name]</a>&nbsp;商品名称<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[price]');return false\">[price]</a>&nbsp;商品价格<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[time]');return false\">[time]</a>&nbsp;当前时间戳<br/></font>\r\n<br/>\r\n</div>\r\n<div id=\"curl_display2\" style=\"display:none;\">\r\n<div class=\"form-group\">\r\n<label>选择对接网站:</label>&nbsp;(<a href=\"shequlist.php\">添加</a>)<br>\r\n<select class=\"form-control\" name=\"shequ\">" . $shequselect . "</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>商品ID（goods_id）:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_id\" value=\"\">\r\n</div>\r\n<div class=\"form-group\" id=\"goods_type\">\r\n<label>类型ID（goods_type）:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_type\" value=\"\">\r\n</div>\r\n<div class=\"form-group\" id=\"goods_param\">\r\n<label>参数名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_param\" value=\"qq\">\r\n<pre><font color=\"green\">对应输入框标题，多个参数请用|隔开；如果是对接卡盟，请直接填写下单页面地址</font></pre>\r\n</div>\r\n</div>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确定添加\"></form>";
	echo "<br/><a href=\"./shoplist.php\">>>返回商品列表</a>";
	echo "</div></div>\r\n<script>\r\n\$(\"select[name='is_curl']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"none\");\r\n\t}else if(\$(this).val() == 2){\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"none\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"none\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name='shequ']\").change(function(){\r\n\tif(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 1){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t\t\$(\"input[name='goods_param']\").val(\"uin\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 3 || \$(\"select[name='shequ'] option:selected\").attr(\"type\") == 5){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"none\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 4){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t\t\$(\"input[name='goods_param']\").val(\"qq\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") >= 6){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t\t\$(\"input[name='goods_param']\").val(\"\");\r\n\t}else{\r\n\t\t\$(\"#goods_type\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t\t\$(\"input[name='goods_param']\").val(\"qq\");\r\n\t}\r\n});\r\nfunction Addstr(id, str) {\r\n\t\$(\"#\"+id).val(\$(\"#\"+id).val()+str);\r\n}\r\nvar items = \$(\"select[default]\");\r\nfor (i = 0; i < items.length; i++) {\r\n\t\$(items[i]).val(\$(items[i]).attr(\"default\")||0);\r\n}\r\nwindow.onload=\$(\"select[name='shequ']\").change();\r\n</script>";
} else {
	if ($my == "edit") {
		$tid = $_GET["tid"];
		$row = $DB->get_row("select * from shua_tools where tid='" . $tid . "' limit 1");
		echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">修改商品信息</h3></div>";
		echo "<div class=\"panel-body\">";
		echo "<form action=\"./shoplist.php?my=edit_submit&tid=" . $tid . "\" method=\"POST\">\r\n<input type=\"hidden\" name=\"backurl\" value=\"" . $_SERVER["HTTP_REFERER"] . "\"/>\r\n<div class=\"form-group\">\r\n<label>*商品分类:</label><br>\r\n<select name=\"cid\" class=\"form-control\" default=\"" . $row["cid"] . "\">" . $select . "</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>商品名称:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"name\" value=\"" . $row["name"] . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>销售价格:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"price\" value=\"" . $row["price"] . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>代理价格:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"cost\" value=\"" . $row["cost"] . "\" placeholder=\"留空则无法代理\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>第一个输入框标题:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"input\" value=\"" . $row["input"] . "\" placeholder=\"留空默认为“下单ＱＱ”\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>更多输入框标题:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"inputs\" value=\"" . $row["inputs"] . "\" placeholder=\"留空则不显示更多输入框\">\r\n<pre><font color=\"green\">多个输入框请用|隔开(不能超过4个)</font></pre>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>提示内容:</label>(没有请留空)<br>\r\n<input type=\"text\" class=\"form-control\" name=\"alert\" value=\"" . htmlspecialchars($row["alert"]) . "\" placeholder=\"当选择该商品时自动弹出提示\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>默认数量信息:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"value\" value=\"" . $row["value"] . "\" placeholder=\"用于对接社区使用或导出时显示\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*显示数量选择框:</label><br>\r\n<select class=\"form-control\" name=\"multi\" default=\"" . $row["multi"] . "\"><option value=\"0\">0_否</option><option value=\"1\">1_是</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*允许重复下单:</label><br>\r\n<select class=\"form-control\" name=\"repeat\" default=\"" . $row["repeat"] . "\"><option value=\"0\">0_否</option><option value=\"1\">1_是</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>*验证操作:</label><br>\r\n<select class=\"form-control\" name=\"validate\" default=\"" . $row["validate"] . "\"><option value=\"0\">不开启验证</option><option value=\"1\">验证QQ空间是否有访问权限</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>排序(数字越小越靠前):</label><br>\r\n<input type=\"number\" class=\"form-control\" name=\"sort\" value=\"" . $row["sort"] . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>是否上架:</label><br>\r\n<select class=\"form-control\" name=\"active\" default=\"" . $row["active"] . "\"><option value=\"1\">1_是</option><option value=\"0\">0_否</option></select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>购买成功后的动作:</label><br>\r\n<select class=\"form-control\" name=\"is_curl\" default=\"" . $row["is_curl"] . "\"><option value=\"0\">0_无</option><option value=\"1\">1_自动访问URL</option><option value=\"2\">2_自动提交到社区/卡盟</option><option value=\"3\">3_自动发送提醒邮件</option></select>\r\n</div>\r\n<div id=\"curl_display1\" style=\"" . (!($row["is_curl"] == 1) ? "display:none;" : NULL) . "\">\r\n<div class=\"form-group\">\r\n<label>自动访问的URL:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"curl\" id=\"curl\" value=\"" . $row["curl"] . "\">\r\n</div>\r\n<font color=\"green\">URL里面可以加变量：<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input]');return false\">[input]</a>&nbsp;第一个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input2]');return false\">[input2]</a>&nbsp;第二个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input3]');return false\">[input3]</a>&nbsp;第三个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[input4]');return false\">[input4]</a>&nbsp;第四个输入框内容<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[name]');return false\">[name]</a>&nbsp;商品名称<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[price]');return false\">[price]</a>&nbsp;商品价格<br/>\r\n<a href=\"#\" onclick=\"Addstr('curl','[time]');return false\">[time]</a>&nbsp;当前时间戳<br/></font>\r\n<br/>\r\n</div>\r\n<div id=\"curl_display2\" style=\"" . (!($row["is_curl"] == 2) ? "display:none;" : NULL) . "\">\r\n<div class=\"form-group\">\r\n<label>选择对接网站:</label><br>\r\n<select class=\"form-control\" name=\"shequ\" default=\"" . $row["shequ"] . "\">" . $shequselect . "</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>商品ID（goods_id）:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_id\" value=\"" . $row["goods_id"] . "\">\r\n</div>\r\n<div class=\"form-group\" id=\"goods_type\">\r\n<label>类型ID（goods_type）:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_type\" value=\"" . $row["goods_type"] . "\">\r\n</div>\r\n<div class=\"form-group\" id=\"goods_param\">\r\n<label>参数名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"goods_param\" value=\"" . $row["goods_param"] . "\">\r\n<pre><font color=\"green\">对应输入框标题，多个参数请用|隔开；如果是对接卡盟，请直接填写下单页面地址</font></pre>\r\n</div>\r\n</div>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确定修改\"></form>\r\n";
		echo "<br/><a href=\"./shoplist.php\">>>返回商品列表</a>";
		echo "</div></div>\r\n<script>\r\n\$(\"select[name='is_curl']\").change(function(){\r\n\tif(\$(this).val() == 1){\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"none\");\r\n\t}else if(\$(this).val() == 2){\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"none\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#curl_display1\").css(\"display\",\"none\");\r\n\t\t\$(\"#curl_display2\").css(\"display\",\"none\");\r\n\t}\r\n});\r\n\$(\"select[name='shequ']\").change(function(){\r\n\tif(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 1){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 3 || \$(\"select[name='shequ'] option:selected\").attr(\"type\") == 5){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"none\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") == 4){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t}else if(\$(\"select[name='shequ'] option:selected\").attr(\"type\") >= 6){\r\n\t\t\$(\"#goods_type\").css(\"display\",\"none\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t}else{\r\n\t\t\$(\"#goods_type\").css(\"display\",\"inherit\");\r\n\t\t\$(\"#goods_param\").css(\"display\",\"inherit\");\r\n\t}\r\n});\r\nfunction Addstr(id, str) {\r\n\t\$(\"#\"+id).val(\$(\"#\"+id).val()+str);\r\n}\r\nvar items = \$(\"select[default]\");\r\nfor (i = 0; i < items.length; i++) {\r\n\t\$(items[i]).val(\$(items[i]).attr(\"default\")||0);\r\n}\r\nwindow.onload=\$(\"select[name='shequ']\").change();\r\n</script>";
	} else {
		if ($my == "add_submit") {
			$cid = $_POST["cid"];
			$name = $_POST["name"];
			$price = $_POST["price"];
			$cost = $_POST["cost"];
			$input = $_POST["input"];
			$inputs = $_POST["inputs"];
			$alert = $_POST["alert"];
			$value = $_POST["value"];
			$multi = $_POST["multi"];
			$validate = $_POST["validate"];
			$is_curl = $_POST["is_curl"];
			$curl = $_POST["curl"];
			$shequ = $_POST["shequ"];
			$goods_id = $_POST["goods_id"];
			$goods_type = $_POST["goods_type"];
			$goods_param = $_POST["goods_param"];
			$repeat = $_POST["repeat"];
			$sort = $_POST["sort"];
			$active = $_POST["active"];
			if ($name == NULL || $price == NULL) {
				showmsg("保存错误，商品名称和价格不能为空！", 3);
			} else {
				if ($is_curl == 2 && $value == 0) {
					showmsg("请填写默认数量信息！", 3);
				} else {
					if ($is_curl == 2 && !$shequ) {
						showmsg("请选择对接社区！", 3);
					} else {
						$sql = "insert into `shua_tools` (`cid`,`name`,`price`,`cost`,`input`,`inputs`,`alert`,`value`,`is_curl`,`curl`,`shequ`,`goods_id`,`goods_type`,`goods_param`,`repeat`,`multi`,`validate`,`sort`,`active`) values ('" . $cid . "','" . $name . "','" . $price . "','" . $cost . "','" . $input . "','" . $inputs . "','" . $alert . "','" . $value . "','" . $is_curl . "','" . $curl . "','" . $shequ . "','" . $goods_id . "','" . $goods_type . "','" . $goods_param . "','" . $repeat . "','" . $multi . "','" . $validate . "','" . $sort . "','" . $active . "')";
						if ($DB->query($sql)) {
							showmsg("添加商品成功！<br/><br/><a href=\"" . $_POST["backurl"] . "\">>>返回商品列表</a>", 1);
						} else {
							showmsg("添加商品失败！" . $DB->error(), 4);
						}
					}
				}
			}
		} else {
			if ($my == "edit_submit") {
				$tid = $_GET["tid"];
				$rows = $DB->get_row("select * from shua_tools where tid='" . $tid . "' limit 1");
				if (!$rows) {
					showmsg("当前记录不存在！", 3);
				}
				$cid = $_POST["cid"];
				$name = $_POST["name"];
				$price = $_POST["price"];
				$cost = $_POST["cost"];
				$input = $_POST["input"];
				$inputs = $_POST["inputs"];
				$alert = $_POST["alert"];
				$value = $_POST["value"];
				$multi = $_POST["multi"];
				$validate = $_POST["validate"];
				$is_curl = $_POST["is_curl"];
				$curl = $_POST["curl"];
				$shequ = $_POST["shequ"];
				$goods_id = $_POST["goods_id"];
				$goods_type = $_POST["goods_type"];
				$goods_param = $_POST["goods_param"];
				$repeat = $_POST["repeat"];
				$sort = $_POST["sort"];
				$active = $_POST["active"];
				if ($name == NULL || $price == NULL) {
					showmsg("保存错误，商品名称和价格不能为空！", 3);
				} else {
					if ($is_curl == 2 && $value == 0) {
						showmsg("请填写默认数量信息！", 3);
					} else {
						if ($is_curl == 2 && !$shequ) {
							showmsg("请选择对接社区！", 3);
						} else {
							if ($DB->query("update shua_tools set cid='" . $cid . "',name='" . $name . "',price='" . $price . "',cost='" . $cost . "',input='" . $input . "',inputs='" . $inputs . "',alert='" . $alert . "',value='" . $value . "',is_curl='" . $is_curl . "',curl='" . $curl . "',shequ='" . $shequ . "',goods_id='" . $goods_id . "',goods_type='" . $goods_type . "',goods_param='" . $goods_param . "',sort='" . $sort . "',`repeat`='" . $repeat . "',`multi`='" . $multi . "',`validate`='" . $validate . "',active='" . $active . "' where tid='" . $tid . "'")) {
								showmsg("修改商品成功！<br/><br/><a href=\"" . $_POST["backurl"] . "\">>>返回商品列表</a>", 1);
							} else {
								showmsg("修改商品失败！" . $DB->error(), 4);
							}
						}
					}
				}
			} else {
				if ($my == "delete") {
					$tid = $_GET["tid"];
					$sql = "DELETE FROM shua_tools WHERE tid='" . $tid . "' limit 1";
					if ($DB->query($sql)) {
						showmsg("删除成功！<br/><br/><a href=\"" . $_SERVER["HTTP_REFERER"] . "\">>>返回商品列表</a>", 1);
					} else {
						showmsg("删除失败！" . $DB->error(), 4);
					}
				} else {
					if ($my == "move") {
						$cid = $_POST["cid"];
						if (!$cid) {
							exit("<script language='javascript'>alert('请选择分类');history.go(-1);</script>");
						}
						$checkbox = $_POST["checkbox"];
						$i = 0;
						foreach ($checkbox as $tid) {
							if ($cid == 0 - 1) {
								$DB->query("update shua_tools set active=1 where tid='" . $tid . "' limit 1");
							} else {
								if ($cid == 0 - 2) {
									$DB->query("update shua_tools set active=0 where tid='" . $tid . "' limit 1");
								} else {
									if ($cid == 0 - 3) {
										$DB->query("DELETE FROM shua_tools WHERE tid='" . $tid . "' limit 1");
									} else {
										$DB->query("update shua_tools set cid='" . $cid . "' where tid='" . $tid . "' limit 1");
									}
								}
							}
							$i = $i + 1;
						}
						exit("<script language='javascript'>alert('成功移动" . $i . "个商品');history.go(-1);</script>");
					} else {
						if (isset($_GET['cid'])) {
							$cid = intval($_GET["cid"]);
							$numrows = $DB->count("SELECT count(*) from shua_tools where cid='" . $cid . "'");
							$sql = " cid='" . $cid . "'";
							$con = "分类 <a href=\"../?cid=" . $cid . "\" target=\"_blank\">" . $shua_class[$cid] . "</a> 共有 <b>" . $numrows . "</b> 个商品<br/><a href=\"./shoplist.php?my=add&cid=" . $cid . "\" class=\"btn btn-primary\">添加商品</a>";
						} else {
							$numrows = $DB->count("SELECT count(*) from shua_tools");
							$sql = " 1";
							$con = "系统共有 <b>" . $numrows . "</b> 个商品<br/><a href=\"./shoplist.php?my=add\" class=\"btn btn-primary\">添加商品</a>";
						}
						echo "<div class=\"alert alert-info\">";
						echo $con;
						echo "</div>";
						echo "      <div class=\"table-responsive\">\r\n\t  <form name=\"form1\" method=\"post\" action=\"shoplist.php?my=move\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>ID</th><th>名称</th><th>价格</th><th>状态</th><th>操作</th></tr></thead>\r\n          <tbody>\r\n";
						$pagesize = 30;
						$pages = intval($numrows / $pagesize);
						if ($numrows % $pagesize) {
							$pages = $pages + 1;
						}
						if (isset($_GET['page'])) {
							$page = intval($_GET["page"]);
						} else {
							$page = 1;
						}
						$offset = $pagesize * ($page - 1);
						$rs = $DB->query("SELECT * FROM shua_tools WHERE" . $sql . " order by sort asc limit " . $offset . "," . $pagesize);
						while ($res = $DB->fetch($rs)) {
							echo "<tr><td><input type=\"checkbox\" name=\"checkbox[]\" id=\"list1\" value=\"" . $res["tid"] . "\" onClick=\"unselectall1()\"><b>" . $res["tid"] . "</b></td><td>" . $res["name"] . "</td><td>" . $res["price"] . (!($res["cost"] == 0) ? "&nbsp;(" . $res["cost"] . ")" : NULL) . "</td><td>" . ($res["active"] == 1 ? "<font color=green>上架中</font>" : "<font color=red>已下架</font>") . "</td><td><a href=\"./shoplist.php?my=edit&tid=" . $res["tid"] . "\" class=\"btn btn-info btn-xs\">编辑</a>&nbsp;<a href=\"./list.php?tid=" . $res["tid"] . "\" class=\"btn btn-warning btn-xs\">订单</a>&nbsp;<a href=\"./shoplist.php?my=delete&tid=" . $res["tid"] . "\" class=\"btn btn-xs btn-danger\" onclick=\"return confirm('你确实要删除此商品吗？');\">删除</a></td></tr>";
						}
						echo "          </tbody>\r\n        </table>\r\n<input name=\"chkAll1\" type=\"checkbox\" id=\"chkAll1\" onClick=\"this.value=check1(this.form.list1)\" value=\"checkbox\">&nbsp;全选&nbsp;\r\n<select name=\"cid\"><option selected>将选定商品移动到分类</option>";
						echo $select;
						echo "<option value=\"-1\">&gt;改为上架中</option><option value=\"-2\">&gt;改为已下架</option><option value=\"-3\">&gt;删除选中</option></select>\r\n<input type=\"submit\" name=\"Submit\" value=\"确定移动\">\r\n</form>\r\n      </div>\r\n<script>\r\nvar checkflag1 = \"false\";\r\nfunction check1(field) {\r\nif (checkflag1 == \"false\") {\r\nfor (i = 0; i < field.length; i++) {\r\nfield[i].checked = true;}\r\ncheckflag1 = \"true\";\r\nreturn \"false\"; }\r\nelse {\r\nfor (i = 0; i < field.length; i++) {\r\nfield[i].checked = false; }\r\ncheckflag1 = \"false\";\r\nreturn \"true\"; }\r\n}\r\n\r\nfunction unselectall1()\r\n{\r\n    if(document.form1.chkAll1.checked){\r\n\tdocument.form1.chkAll1.checked = document.form1.chkAll1.checked&0;\r\n\tcheckflag1 = \"false\";\r\n    } \t\r\n}\r\n</script>\r\n\r\n";
						echo "<ul class=\"pagination\">";
						$first = 1;
						$prev = $page - 1;
						$next = $page + 1;
						$last = $pages;
						if ($page > 1) {
							echo "<li><a href=\"shoplist.php?page=" . $first . $link . "\">首页</a></li>";
							echo "<li><a href=\"shoplist.php?page=" . $prev . $link . "\">&laquo;</a></li>";
						} else {
							echo "<li class=\"disabled\"><a>首页</a></li>";
							echo "<li class=\"disabled\"><a>&laquo;</a></li>";
						}
						$i = 1;
						while ($i < $page) {
							echo "<li><a href=\"shoplist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
							$i = $i + 1;
						}
						echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
						$i = $page + 1;
						while ($i <= $pages) {
							echo "<li><a href=\"shoplist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
							$i = $i + 1;
						}
						echo "";
						if ($page < $pages) {
							echo "<li><a href=\"shoplist.php?page=" . $next . $link . "\">&raquo;</a></li>";
							echo "<li><a href=\"shoplist.php?page=" . $last . $link . "\">尾页</a></li>";
						} else {
							echo "<li class=\"disabled\"><a>&raquo;</a></li>";
							echo "<li class=\"disabled\"><a>尾页</a></li>";
						}
						echo "</ul>";
					}
				}
			}
		}
	}
}
echo "    </div>\r\n  </div>";