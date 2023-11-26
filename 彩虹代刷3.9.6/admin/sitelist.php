<?php

//decode by http://www.yunlu99.com/
include "../includes/common.php";
$title = "分站管理";
include "./head.php";
if ($islogin != 1) {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
echo "  <div class=\"container\" style=\"padding-top:70px;\">\r\n    <div class=\"col-sm-12 col-md-10 center-block\" style=\"float: none;\">\r\n<div class=\"modal fade\" align=\"left\" id=\"search\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-header\">\r\n        <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>\r\n        <h4 class=\"modal-title\" id=\"myModalLabel\">搜索分站</h4>\r\n      </div>\r\n      <div class=\"modal-body\">\r\n      <form action=\"sitelist.php\" method=\"GET\">\r\n<input type=\"text\" class=\"form-control\" name=\"kw\" placeholder=\"请输入分站用户名或域名\"><br/>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"搜索\"></form>\r\n</div>\r\n      <div class=\"modal-footer\">\r\n        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n";
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
$my = isset($_GET['my']) ? $_GET["my"] : NULL;
if ($my == "add") {
	echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">添加一个分站</h3></div>";
	echo "<div class=\"panel-body\">";
	echo "<form action=\"./sitelist.php?my=add_submit\" method=\"POST\">\r\n<div class=\"form-group\">\r\n<label>管理员用户名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"user\" value=\"\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>管理员密码:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"pwd\" value=\"123456\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>绑定域名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"domain\" value=\"\" placeholder=\"分站要用的域名\" required>\r\n</div>\r\n<!--div class=\"form-group\">\r\n<label>额外域名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"domain2\" placeholder=\"不需要填写\" value=\"\">\r\n</div-->\r\n<div class=\"form-group\">\r\n<label>站点余额:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"rmb\" value=\"0\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>站长QQ:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"qq\" value=\"\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>到期时间:</label><br>\r\n<input type=\"date\" class=\"form-control\" name=\"endtime\" value=\"" . date("Y-m-d", strtotime("+1 years")) . "\" required>\r\n</div>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确定添加\"></form>";
	echo "<br/><a href=\"./sitelist.php\">>>返回分站列表</a>";
	echo "</div></div>";
} else {
	if ($my == "edit") {
		$zid = $_GET["zid"];
		$row = $DB->get_row("select * from shua_site where zid='" . $zid . "' limit 1");
		echo "<div class=\"panel panel-primary\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">修改分站信息</h3></div>";
		echo "<div class=\"panel-body\">";
		echo "<form action=\"./sitelist.php?my=edit_submit&zid=" . $zid . "\" method=\"POST\">\r\n<div class=\"form-group\">\r\n<label>绑定域名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"domain\" value=\"" . $row["domain"] . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>额外域名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"domain2\" value=\"" . $row["domain2"] . "\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>站点余额:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"rmb\" value=\"" . $row["rmb"] . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>站长QQ:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"qq\" value=\"" . $row["qq"] . "\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>站点名称:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"sitename\" value=\"" . $row["sitename"] . "\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>结算账号:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"pay_account\" value=\"" . $row["pay_account"] . "\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>结算姓名:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"pay_name\" value=\"" . $row["pay_name"] . "\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label>到期时间:</label><br>\r\n<input type=\"date\" class=\"form-control\" name=\"endtime\" value=\"" . date("Y-m-d", strtotime($row["endtime"])) . "\" required>\r\n</div>\r\n<div class=\"form-group\">\r\n<label>重置密码:</label><br>\r\n<input type=\"text\" class=\"form-control\" name=\"pwd\" value=\"\" placeholder=\"不重置请留空\">\r\n</div>\r\n<input type=\"submit\" class=\"btn btn-primary btn-block\" value=\"确定修改\"></form>";
		echo "<br/><a href=\"./sitelist.php\">>>返回分站列表</a>";
		echo "</div></div>";
	} else {
		if ($my == "add_submit") {
			$user = $_POST["user"];
			$pwd = $_POST["pwd"];
			$domain = $_POST["domain"];
			$domain2 = $_POST["domain2"];
			$rmb = $_POST["rmb"];
			$qq = $_POST["qq"];
			$endtime = $_POST["endtime"];
			$sitename = $conf["sitename"];
			$keywords = $conf["keywords"];
			$description = $conf["description"];
			if ($user == NULL || $pwd == NULL || $domain == NULL || $endtime == NULL) {
				showmsg("保存错误,请确保每项都不为空!", 3);
			} else {
				$rows = $DB->get_row("select * from shua_site where user='" . $user . "' limit 1");
				if ($rows) {
					showmsg("用户名已存在！", 3);
				}
				$rows = $DB->get_row("select * from shua_site where domain='" . $domain . "' limit 1");
				if ($rows) {
					showmsg("域名已存在！", 3);
				}
				$sql = "insert into `shua_site` (`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`anounce`,`bottom`,`modal`,`addtime`,`endtime`,`status`) values ('" . $domain . "','" . $domain2 . "','" . $user . "','" . $pwd . "','" . $rmb . "','" . $qq . "','" . $sitename . "','" . $keywords . "','" . $description . "','" . addslashes($anounce) . "','" . addslashes($bottom) . "','" . addslashes($modal) . "','" . $date . "','" . $endtime . "','1')";
				if ($DB->query($sql)) {
					showmsg("添加分站成功！<br/><br/><a href=\"./sitelist.php\">>>返回分站列表</a>", 1);
				} else {
					showmsg("添加分站失败！" . $DB->error(), 4);
				}
			}
		} else {
			if ($my == "edit_submit") {
				$zid = $_GET["zid"];
				$rows = $DB->get_row("select * from shua_site where zid='" . $zid . "' limit 1");
				if (!$rows) {
					showmsg("当前记录不存在！", 3);
				}
				$domain = $_POST["domain"];
				$domain2 = $_POST["domain2"];
				$rmb = $_POST["rmb"];
				$qq = $_POST["qq"];
				$endtime = $_POST["endtime"];
				$sitename = $_POST["sitename"];
				$pay_account = $_POST["pay_account"];
				$pay_name = $_POST["pay_name"];
				if (!empty($_POST['pwd'])) {
					$sql = ",pwd='" . $_POST["pwd"] . "'";
				}
				if ($sitename == NULL || $rmb == NULL || $domain == NULL || $endtime == NULL) {
					showmsg("保存错误,请确保每项都不为空!", 3);
				} else {
					if ($DB->query("update shua_site set domain='" . $domain . "',domain2='" . $domain2 . "',rmb='" . $rmb . "',qq='" . $qq . "',sitename='" . $sitename . "',pay_account='" . $pay_account . "',pay_name='" . $pay_name . "',endtime='" . $endtime . "'" . $sql . " where zid='" . $zid . "'")) {
						showmsg("修改分站成功！<br/><br/><a href=\"./sitelist.php\">>>返回分站列表</a>", 1);
					} else {
						showmsg("修改分站失败！" . $DB->error(), 4);
					}
				}
			} else {
				if ($my == "delete") {
					$zid = $_GET["zid"];
					$sql = "DELETE FROM shua_site WHERE zid='" . $zid . "'";
					if ($DB->query($sql)) {
						showmsg("删除成功！<br/><br/><a href=\"./sitelist.php\">>>返回分站列表</a>", 1);
					} else {
						showmsg("删除失败！" . $DB->error(), 4);
					}
				} else {
					$numrows = $DB->count("SELECT count(*) from shua_site");
					if (isset($_GET['zid'])) {
						$sql = " zid=" . $_GET["zid"];
					} else {
						if (isset($_GET['kw'])) {
							$sql = " user='" . $_GET["kw"] . "' or domain='" . $_GET["kw"] . "' or domain2='" . $_GET["kw"] . "' or qq='" . $_GET["kw"] . "'";
						} else {
							$sql = " 1";
						}
					}
					$con = "系统共有 <b>" . $numrows . "</b> 个分站<br/><a href=\"./sitelist.php?my=add\" class=\"btn btn-primary\">添加分站</a>&nbsp;<a href=\"#\" data-toggle=\"modal\" data-target=\"#search\" id=\"search\" class=\"btn btn-success\">搜索</a>";
					echo "<div class=\"alert alert-info\">";
					echo $con;
					echo "</div>";
					echo "      <div class=\"table-responsive\">\r\n        <table class=\"table table-striped\">\r\n          <thead><tr><th>ZID</th><th>用户名</th><th>站点名称/站长QQ</th><th>余额</th><th>开通/到期时间</th><th>绑定域名</th><th>操作</th></tr></thead>\r\n          <tbody>\r\n";
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
					$rs = $DB->query("SELECT * FROM shua_site WHERE" . $sql . " order by zid desc limit " . $offset . "," . $pagesize);
					while ($res = $DB->fetch($rs)) {
						echo "<tr><td><b>" . $res["zid"] . "</b></td><td>" . $res["user"] . "</td><td>" . $res["sitename"] . "<br/>" . $res["qq"] . "</td><td>" . $res["rmb"] . "</td><td>" . $res["addtime"] . "<br/>" . $res["endtime"] . "</td><td>" . $res["domain"] . "<br/>" . $res["domain2"] . "</td><td><a href=\"./sitelist.php?my=edit&zid=" . $res["zid"] . "\" class=\"btn btn-info btn-xs\">编辑</a>&nbsp;<a href=\"./list.php?zid=" . $res["zid"] . "\" class=\"btn btn-warning btn-xs\">订单</a>&nbsp;<a href=\"./record.php?zid=" . $res["zid"] . "\" class=\"btn btn-success btn-xs\">明细</a>&nbsp;<a href=\"./sitelist.php?my=delete&zid=" . $res["zid"] . "\" class=\"btn btn-xs btn-danger\" onclick=\"return confirm('你确实要删除此站点吗？');\">删除</a></td></tr>";
					}
					echo "          </tbody>\r\n        </table>\r\n      </div>\r\n";
					echo "<ul class=\"pagination\">";
					$first = 1;
					$prev = $page - 1;
					$next = $page + 1;
					$last = $pages;
					if ($page > 1) {
						echo "<li><a href=\"sitelist.php?page=" . $first . $link . "\">首页</a></li>";
						echo "<li><a href=\"sitelist.php?page=" . $prev . $link . "\">&laquo;</a></li>";
					} else {
						echo "<li class=\"disabled\"><a>首页</a></li>";
						echo "<li class=\"disabled\"><a>&laquo;</a></li>";
					}
					$i = 1;
					while ($i < $page) {
						echo "<li><a href=\"sitelist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
						$i = $i + 1;
					}
					echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
					if ($pages >= 10) {
						$pages = 10;
					}
					$i = $page + 1;
					while ($i <= $pages) {
						echo "<li><a href=\"sitelist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
						$i = $i + 1;
					}
					echo "";
					if ($page < $pages) {
						echo "<li><a href=\"sitelist.php?page=" . $next . $link . "\">&raquo;</a></li>";
						echo "<li><a href=\"sitelist.php?page=" . $last . $link . "\">尾页</a></li>";
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
echo "    </div>\r\n  </div>";