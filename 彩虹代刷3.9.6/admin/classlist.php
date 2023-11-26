<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "分类管理";
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
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-sm-12 col-md-10 center-block\" style=\"float: none;\">\r\n";
$my = isset($_GET['my']) ? $_GET["my"] : NULL;
if ($my == "add_submit") {
	$name = $_POST["name"];
	$sort = $_POST["sort"];
	if ($name == NULL || $sort == NULL) {
		exit("<script language='javascript'>alert('保存错误,请确保每项都不为空!');history.go(-1);</script>");
	} else {
		$sql = "insert into `shua_class` (`name`,`sort`,`active`) values ('" . $name . "','" . $sort . "','1')";
		if ($DB->query($sql)) {
			exit("<script language='javascript'>alert('添加分类成功！');window.location.href='classlist.php';</script>");
		} else {
			exit("<script language='javascript'>alert('添加商品失败！" . $DB->error() . "');history.go(-1);</script>");
		}
	}
} else {
	if ($my == "edit_submit") {
		$cid = $_GET["cid"];
		$rows = $DB->get_row("select * from shua_class where cid='" . $cid . "' limit 1");
		if (!$rows) {
			exit("<script language='javascript'>alert('当前记录不存在！');history.go(-1);</script>");
		}
		$name = $_POST["name"];
		$sort = $_POST["sort"];
		if ($name == NULL || $sort == NULL) {
			exit("<script language='javascript'>alert('保存错误,请确保每项都不为空!');history.go(-1);</script>");
		} else {
			if ($DB->query("update shua_class set name='" . $name . "',sort='" . $sort . "' where cid='" . $cid . "'")) {
				exit("<script language='javascript'>alert('修改分类成功！');window.location.href='classlist.php';</script>");
			} else {
				exit("<script language='javascript'>alert('修改商品失败！" . $DB->error() . "');history.go(-1);</script>");
			}
		}
	} else {
		if ($my == "delete") {
			$cid = $_GET["cid"];
			$sql = "DELETE FROM shua_class WHERE cid='" . $cid . "'";
			if ($DB->query($sql)) {
				exit("<script language='javascript'>alert('删除成功！');window.location.href='classlist.php';</script>");
			} else {
				exit("<script language='javascript'>alert('删除失败！" . $DB->error() . "');history.go(-1);</script>");
			}
		} else {
			$numrows = $DB->count("SELECT count(*) from shua_class");
			$sql = " 1";
			echo $con;
			echo "      <div class=\"table-responsive\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>名称（";
			echo $numrows;
			echo "）</th><th>排序</th><th>操作</th></tr></thead>\r\n          <tbody>\r\n";
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
			$rs = $DB->query("SELECT * FROM shua_class WHERE" . $sql . " order by sort asc");
			while ($res = $DB->fetch($rs)) {
				echo "<form action=\"classlist.php?my=edit_submit&cid=" . $res["cid"] . "\" method=\"POST\" class=\"form-inline\"><tr><td><input type=\"text\" class=\"form-control input-sm\" name=\"name\" value=\"" . $res["name"] . "\" placeholder=\"分类名称\" required></td><td><input type=\"text\" class=\"form-control input-sm\" name=\"sort\" value=\"" . $res["sort"] . "\" placeholder=\"排序(数字越小越靠前)\" required></td><td><button type=\"submit\" class=\"btn btn-primary btn-sm\">修改</button>&nbsp;<a href=\"./shoplist.php?cid=" . $res["cid"] . "\" class=\"btn btn-warning btn-sm\">商品</a>&nbsp;<a href=\"./classlist.php?my=delete&cid=" . $res["cid"] . "\" class=\"btn btn-sm btn-danger\" onclick=\"return confirm('你确实要删除此商品吗？');\">删除</a></td></tr></form>";
			}
			echo "<form action=\"classlist.php?my=add_submit\" method=\"POST\" class=\"form-inline\" id=\"addclass\"><tr><td><input type=\"text\" class=\"form-control input-sm\" name=\"name\" placeholder=\"分类名称\" required></td><td><input type=\"text\" class=\"form-control input-sm\" name=\"sort\" value=\"10\" placeholder=\"排序(数字越小越靠前)\" required></td><td><button type=\"submit\" class=\"btn btn-success btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span> 添加分类</button></td></tr></form>";
			echo "          </tbody>\r\n        </table>\r\n      </div>\r\n";
		}
	}
}
echo "    </div>\r\n  </div>";