<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "克隆站点";
include "./head.php";
if ($islogin != 1) {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-sm-12 col-md-10 col-lg-8 center-block\" style=\"float: none;\">\r\n";
if (isset($_POST['submit'])) {
	$url = $_POST["url"];
	$key = $_POST["key"];
	$url_arr = parse_url($url);
	if ($url_arr["host"] == $_SERVER["HTTP_HOST"]) {
		showmsg("无法自己克隆自己", 3);
	}
	$data = get_curl($url . "api.php?act=clone&key=" . $key);
	$arr = json_decode($data, true);
	if (array_key_exists("code", $arr) && $arr["code"] == 1) {
		$success = 0;
		if (count($arr["class"]) < 2 || count($arr["tools"]) < 5) {
			showmsg("对方网站数据量过少。", 3);
		}
		$DB->query("TRUNCATE TABLE `shua_class`");
		$success = $success + 1;
		foreach ($arr["class"] as $row) {
			$DB->query("INSERT INTO `shua_class` (`cid`,`name`,`sort`,`active`) VALUES ('" . $row["cid"] . "','" . $row["name"] . "','" . $row["sort"] . "','1')");
			$success = $success + 1;
		}
		$DB->query("TRUNCATE TABLE `shua_tools`");
		$success = $success + 1;
		foreach ($arr["tools"] as $row) {
			$DB->query("INSERT INTO `shua_tools` (`tid`,`cid`,`name`,`price`,`cost`,`input`,`inputs`,`alert`,`value`,`is_curl`,`curl`,`shequ`,`goods_id`,`goods_type`,`goods_param`,`repeat`,`multi`,`sort`,`active`) VALUES ('" . $row["tid"] . "','" . $row["cid"] . "','" . $row["name"] . "','" . $row["price"] . "','" . $row["cost"] . "','" . $row["input"] . "','" . $row["inputs"] . "','" . $row["alert"] . "','" . $row["value"] . "','" . $row["is_curl"] . "','" . $row["curl"] . "','" . $row["shequ"] . "','" . $row["goods_id"] . "','" . $row["goods_type"] . "','" . $row["goods_param"] . "','" . $row["repeat"] . "','" . $row["multi"] . "','" . $row["sort"] . "','" . $row["active"] . "')");
			$success = $success + 1;
		}
		$DB->query("TRUNCATE TABLE `shua_shequ`");
		$success = $success + 1;
		foreach ($arr["shequ"] as $row) {
			$DB->query("INSERT INTO `shua_shequ` (`id`,`url`,`username`,`password`,`type`) VALUES ('" . $row["id"] . "','" . $row["url"] . "',NULL,NULL,'" . $row["type"] . "')");
			$success = $success + 1;
		}
		showmsg("克隆完成，SQL成功执行" . $success . "句。", 1);
	} else {
		if (array_key_exists("code", $arr)) {
			showmsg("克隆失败，原因：" . $arr["msg"], 4);
		} else {
			showmsg("克隆失败，返回数据解析错误。", 4);
		}
	}
}
echo "\r\n\t  <div class=\"panel panel-primary\">\r\n        <div class=\"panel-heading\"><h3 class=\"panel-title\">克隆站点</h3></div>\r\n        <div class=\"panel-body\">\r\n\t\t<div class=\"alert alert-info\">\r\n            使用此功能可一键克隆目标站点的分类、商品及社区对接数据（除社区账号密码外），方便站长快速丰富网站内容。\r\n        </div>\r\n\t\t<div class=\"alert alert-danger\">\r\n            克隆后将会清空本站所有商品和分类数据，请谨慎操作！\r\n        </div>\r\n          <form action=\"?\" method=\"POST\" role=\"form\">\r\n\t\t    <div class=\"form-group\">\r\n\t\t\t\t<div class=\"input-group\"><div class=\"input-group-addon\">站点URL</div>\r\n\t\t\t\t<input type=\"text\" name=\"url\" value=\"\" class=\"form-control\" placeholder=\"http://www.qq.com/\" required/>\r\n\t\t\t\t<div class=\"input-group-addon\" onclick=\"checkurl()\"><small>检测连通性</small></div>\r\n\t\t\t</div></div>\r\n\t\t\t<div class=\"form-group\">\r\n\t\t\t\t<div class=\"input-group\"><div class=\"input-group-addon\">克隆密钥</div>\r\n\t\t\t\t<input type=\"text\" name=\"key\" value=\"\" class=\"form-control\" placeholder=\"联系目标站点取得\" required/>\r\n\t\t\t</div></div>\r\n            <p><input type=\"submit\" name=\"submit\" value=\"确定克隆\" class=\"btn btn-primary form-control\"/></p>\r\n          </form>\r\n        </div>\r\n\t\t<div class=\"panel-footer\">\r\n          <span class=\"glyphicon glyphicon-info-sign\"></span> 本站克隆密钥<a href=\"./set.php?mod=cloneset\">点此获取</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n<script src=\"//cdn.bootcss.com/layer/3.0.1/layer.min.js\"></script>\r\n<script>\r\nfunction checkurl(){\r\n\tvar url = \$(\"input[name='url']\").val();\r\n\tif(url.indexOf('http')>=0 && url.substr(-1) == '/'){\r\n\t\tvar ii = layer.load(2, {shade:[0.1,'#fff']});\r\n\t\t\$.ajax({\r\n\t\t\ttype : \"POST\",\r\n\t\t\turl : \"ajax.php?act=checkclone\",\r\n\t\t\tdata : {url:url},\r\n\t\t\tdataType : 'json',\r\n\t\t\tsuccess : function(data) {\r\n\t\t\t\tlayer.close(ii);\r\n\t\t\t\tif(data.code == 1){\r\n\t\t\t\t\tlayer.msg('连通性良好，可以克隆');\r\n\t\t\t\t}else if(data.code == 2){\r\n\t\t\t\t\tlayer.alert('无法自己克隆自己');\r\n\t\t\t\t}else{\r\n\t\t\t\t\tlayer.alert('对方网站无法连接或非彩虹代刷系统');\r\n\t\t\t\t}\r\n\t\t\t} ,\r\n\t\t\terror:function(data){\r\n\t\t\t\tlayer.close(ii);\r\n\t\t\t\tlayer.msg('目标URL连接超时');\r\n\t\t\t\treturn false;\r\n\t\t\t}\r\n\t\t});\r\n\t}else{\r\n\t\tlayer.alert('URL必须以 http:// 开头，以 / 结尾');\r\n\t}\r\n}\r\n</script>";