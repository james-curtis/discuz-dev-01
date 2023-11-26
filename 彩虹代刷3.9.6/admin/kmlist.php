<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "卡密列表";
include "./head.php";
if ($islogin != 1) {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-sm-12 col-md-10 center-block\" style=\"float: none;\">\r\n<div class=\"modal fade\" align=\"left\" id=\"search\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-header\">\r\n        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>\r\n        <h4 class=\"modal-title\" id=\"myModalLabel\">搜索卡密</h4>\r\n      </div>\r\n      <div class=\"modal-body\">\r\n      <form action=\"kmlist.php\" method=\"GET\">\r\n<input type=\"text\" class=\"form-control\" name=\"kw\" placeholder=\"请输入卡密或QQ\"><br/>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"搜索\"></form>\r\n</div>\r\n      <div class=\"modal-footer\">\r\n        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n";
$my = isset($_GET['my']) ? $_GET["my"] : NULL;
if ($my == "add") {
	$tid = intval($_POST["tid"]);
	$num = intval($_POST["num"]);
	$value = $_POST["value"] ? intval($_POST["value"]) : "1000";
	echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
	$i = 0;
	while ($i < $num) {
		$km = getkm(18);
		$sql = $DB->query("insert into `shua_kms` (`tid`,`km`,`value`,`addtime`) values ('" . $tid . "','" . $km . "','" . $value . "','" . $date . "')");
		if ($sql) {
			echo "<li class='list-group-item'>" . $km . "</li>";
		}
		$i = $i + 1;
	}
	echo "<a href=\"./kmlist.php\" class=\"btn btn-default btn-block\">>>返回卡密列表</a>";
} else {
	if ($my == "del") {
		echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading w h\"><h3 class=\"panel-title\">删除卡密</h3></div>\r\n<div class=\"panel-body box\">";
		$id = $_GET["id"];
		$sql = $DB->query("DELETE FROM shua_kms WHERE kid='" . $id . "'");
		if ($sql) {
			echo "删除成功！";
		} else {
			echo "删除失败！";
		}
		echo "<hr/><a href=\"./kmlist.php\">>>返回卡密列表</a></div></div>";
	} else {
		if ($my == "qk") {
			echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading w h\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"panel-body box\">\r\n您确认要清空所有卡密吗？清空后无法恢复！<br><a href=\"./kmlist.php?my=qk2\">确认</a> | <a href=\"javascript:history.back();\">返回</a></div></div>";
		} else {
			if ($my == "qk2") {
				echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading w h\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"panel-body box\">";
				if ($DB->query("DELETE FROM shua_kms WHERE 1") == true) {
					echo "<div class=\"box\">清空成功.</div>";
				} else {
					echo "<div class=\"box\">清空失败.</div>";
				}
				echo "<hr/><a href=\"./kmlist.php\">>>返回卡密列表</a></div></div>";
			} else {
				if ($my == "qkuse") {
					echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading w h\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"panel-body box\">\r\n您确认要清空所有卡密吗？清空后无法恢复！<br><a href=\"./kmlist.php?my=qkuse2\">确认</a> | <a href=\"javascript:history.back();\">返回</a></div></div>";
				} else {
					if ($my == "qkuse2") {
						echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading w h\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"panel-body box\">";
						if ($DB->query("DELETE FROM shua_kms WHERE user!=0") == true) {
							echo "<div class=\"box\">清空成功.</div>";
						} else {
							echo "<div class=\"box\">清空失败.</div>";
						}
						echo "<hr/><a href=\"./kmlist.php\">>>返回卡密列表</a></div></div>";
					} else {
						$rs = $DB->query("SELECT * FROM shua_tools WHERE active=1 order by sort asc");
						$select = "";
						while ($res = $DB->fetch($rs)) {
							$shua_func[$res["tid"]] = $res["name"];
							$select .= "<option value=\"" . $res["tid"] . "\">" . $res["name"] . "</option>";
						}
						echo "<form action=\"kmlist.php?my=add\" method=\"POST\" class=\"form-inline\">\r\n  <div class=\"form-group\">\r\n    <label>卡密生成</label>\r\n    <select name=\"tid\" class=\"form-control\">" . $select . "</select>\r\n  </div>\r\n  <div class=\"form-group\">\r\n    <input type=\"text\" class=\"form-control\" name=\"num\" placeholder=\"生成的个数\">\r\n  </div>\r\n  <button type=\"submit\" class=\"btn btn-primary\">生成</button>\r\n  <a href=\"kmlist.php?my=qk\" class=\"btn btn-danger\">清空</a>\r\n  <a href=\"kmlist.php?my=qkuse\" class=\"btn btn-danger\">清空已使用</a>\r\n  <a href=\"#\" data-toggle=\"modal\" data-target=\"#search\" id=\"search\" class=\"btn btn-success\">搜索</a>\r\n</form>";
						if (isset($_GET['kw'])) {
							$sql = " `km`='" . $_GET["kw"] . "' or `user`='" . $_GET["kw"] . "'";
							$numrows = $DB->count("SELECT count(*) from shua_kms WHERE" . $sql);
							$con = "包含 " . $_GET["kw"] . " 的共有 <b>" . $numrows . "</b> 个卡密";
						} else {
							$numrows = $DB->count("SELECT count(*) from shua_kms WHERE 1");
							$sql = " 1";
							$con = "代刷平台共有 <b>" . $numrows . "</b> 个卡密";
						}
						echo $con;
						echo "      <div class=\"table-responsive\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>卡密</th><th>状态</th><th>添加时间</th><th>使用时间</th><th>操作</th></tr></thead>\r\n          <tbody>\r\n";
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
						$rs = $DB->query("SELECT * FROM shua_kms WHERE" . $sql . " order by kid desc limit " . $offset . "," . $pagesize);
						while ($res = $DB->fetch($rs)) {
							if ($res["usetime"] == NULL) {
								$isuse = "<font color=\"green\">未使用</font>";
							} else {
								$isuse = "<font color=\"red\">已使用</font><br/>使用者:" . $res["user"];
							}
							echo "<tr><td><b>" . $res["km"] . "</b><br/>" . $shua_func[$res["tid"]] . "</td><td>" . $isuse . "</td><td>" . $res["addtime"] . ($res["zid"] > 1 ? "<br/>站点ID:" . $res["zid"] : NULL) . "</td><td>" . $res["usetime"] . "</td><td><a href=\"./kmlist.php?my=del&id=" . $res["kid"] . "\" class=\"btn btn-xs btn-danger\" onclick=\"return confirm('你确实要删除此卡密吗？');\">删除</a></td></tr>";
						}
						echo "          </tbody>\r\n        </table>\r\n      </div>\r\n";
						echo "<ul class=\"pagination\">";
						$first = 1;
						$prev = $page - 1;
						$next = $page + 1;
						$last = $pages;
						if ($page > 1) {
							echo "<li><a href=\"kmlist.php?page=" . $first . $link . "\">首页</a></li>";
							echo "<li><a href=\"kmlist.php?page=" . $prev . $link . "\">&laquo;</a></li>";
						} else {
							echo "<li class=\"disabled\"><a>首页</a></li>";
							echo "<li class=\"disabled\"><a>&laquo;</a></li>";
						}
						$i = 1;
						while ($i < $page) {
							echo "<li><a href=\"kmlist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
							$i = $i + 1;
						}
						echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
						$i = $page + 1;
						while ($i <= $pages) {
							echo "<li><a href=\"kmlist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
							$i = $i + 1;
						}
						echo "";
						if ($page < $pages) {
							echo "<li><a href=\"kmlist.php?page=" . $next . $link . "\">&raquo;</a></li>";
							echo "<li><a href=\"kmlist.php?page=" . $last . $link . "\">尾页</a></li>";
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
function getkm($len = 18)
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$strlen = strlen($str);
	$randstr = "";
	$i = 0;
	while ($i < $len) {
		$randstr .= $str[mt_rand(0, $strlen - 1)];
		$i = $i + 1;
	}
	return $randstr;
}