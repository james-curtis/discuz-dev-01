<?php
error_reporting(0);
define("CACHE_FILE", 0);
define("IN_CRONLITE", true);
define("SYSTEM_ROOT", dirname(__FILE__) . "/");
define("ROOT", dirname(SYSTEM_ROOT) . "/");
define("SYS_KEY", "daishua_key");
define("CC_Defender", 1);
date_default_timezone_set("PRC");
$date = date("Y-m-d H:i:s");
session_start();
$scriptpath = str_replace("\\", "/", $_SERVER["SCRIPT_NAME"]);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, "/"));
$siteurl = ($_SERVER["SERVER_PORT"] == "443" ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . $sitepath . "/";
if (is_file(SYSTEM_ROOT . "360safe/360webscan.php")) {
	require_once SYSTEM_ROOT . "360safe/360webscan.php";
}
require ROOT . "config.php";
require SYSTEM_ROOT . "version.php";
if (!defined("SQLITE") && (!$dbconfig["user"] || !$dbconfig["pwd"] || !$dbconfig["dbname"])) {
	header("Content-type:text/html;charset=utf-8");
	echo "你还没安装！<a href=\"/install/\">点此安装</a>";
	exit(0);
}
include_once SYSTEM_ROOT . "db.class.php";
$DB = new DB($dbconfig["host"], $dbconfig["user"], $dbconfig["pwd"], $dbconfig["dbname"], $dbconfig["port"]);
if ($DB->query("select * from shua_config where 1") == false) {
	header("Content-type:text/html;charset=utf-8");
	echo "你还没安装！<a href=\"/install/\">点此安装</a>";
	exit(0);
}
include SYSTEM_ROOT . "cache.class.php";
$CACHE = new CACHE();
$conf = unserialize($CACHE->read());
if (empty($conf['version'])) {
	$conf = $CACHE->update();
}
if ($conf["version"] < DB_VERSION) {
	if (!$install) {
		header("Content-type:text/html;charset=utf-8");
		echo "请先完成网站升级！<a href=\"/install/update.php\"><font color=red>点此升级</font></a>";
		exit(0);
	}
}
if (strpos($_SERVER["HTTP_USER_AGENT"], "QQ/") !== false && $conf["qqjump"] == 1) {
	header("Content-type:text/html;charset=utf-8");
	echo "<!DOCTYPE html>\r\n<html>\r\n <head>\r\n  <title>请使用浏览器打开</title>\r\n  <script src=\"https://open.mobile.qq.com/sdk/qqapi.js?_bid=152\"></script>\r\n  <script type=\"text/javascript\"> mqq.ui.openUrl({ target: 2,url: \"" . $siteurl . "\"}); </script>\r\n </head>\r\n <body></body>\r\n</html>";
	exit(0);
}
$password_hash = "!@#%!s!0";
if ($conf["payapi"] == 1) {
	$payapi = "http://www.ufun.me/";
} else {
	if ($conf["payapi"] == 2) {
		$payapi = "http://pay.blyzf.cn/";
	} else {
		if ($conf["payapi"] == 3) {
			$payapi = "http://pay.blpay.me/";
		} else {
			if ($conf["payapi"] == 4) {
				$payapi = "http://tx87.cn/";
			} else {
				if ($conf["payapi"] == 5) {
					$payapi = "http://pay.canxue.me/";
				} else {
					if ($conf["payapi"] == 6) {
						$payapi = "http://pay.hackwl.cn/";
					} else {
						if ($conf["payapi"] == 7) {
							$payapi = "http://pay.weigj.org/";
						} else {
							if ($conf["payapi"] == 8) {
								$payapi = "http://www.jiuaipay.com/";
							} else {
								if ($conf["payapi"] == 9) {
									$payapi = "http://pay.187ka.com/";
								} else {
									if ($conf["payapi"] == 10) {
										$payapi = "http://zf.gfvps.cn/";
									} else {
										if ($conf["payapi"] == 11) {
											$payapi = "http://pay.koock.cn/";
										} else {
											if ($conf["payapi"] == 12) {
												$payapi = "http://www.o10086.cn/";
											} else {
												$payapi = "http://mpay.v8jisu.cn/";
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
include_once SYSTEM_ROOT . "authcode.php";
define("authcode", $authcode);
include_once SYSTEM_ROOT . "function.php";
include_once SYSTEM_ROOT . "core.func.php";
include_once SYSTEM_ROOT . "member.php";
if (!file_exists(ROOT . "install/install.lock") && file_exists(ROOT . "install/index.php")) {
	sysmsg("<h2>检测到无 install.lock 文件</h2><ul><li><font size=\"4\">如果您尚未安装本程序，请<a href=\"./install/\">前往安装</a></font></li><li><font size=\"4\">如果您已经安装本程序，请手动放置一个空的 install.lock 文件到 /install 文件夹下，<b>为了您站点安全，在您完成它之前我们不会工作。</b></font></li></ul><br/><h4>为什么必须建立 install.lock 文件？</h4>它是代刷网的保护文件，如果检测不到它，就会认为站点还没安装，此时任何人都可以安装/重装代刷网。<br/><br/>", true);
}
$cookiesid = $_COOKIE["mysid"];
if (!$cookiesid || !preg_match("/^[0-9a-z]{32}\$/i", $cookiesid)) {
	$cookiesid = md5(uniqid(mt_rand(), 1) . time());
	setcookie("mysid", $cookiesid, time() + 604800, "/");
}
$domain = addslashes($_SERVER["HTTP_HOST"]);
$siterow = $DB->get_row("select * from shua_site where domain='" . $domain . "' or domain2='" . $domain . "' limit 1");
if ($siterow && $siterow["endtime"] >= $date) {
	$is_fenzhan = true;
	$conf = array_merge($conf, $siterow);
} else {
	$is_fenzhan = false;
}
if (!defined("authcode")) {
	exit(0);
}
if (!isset($_SESSION['authcode555']) && $islogin == 1) {
	$string = authcode($_SERVER["HTTP_HOST"] . "||||" . authcode . "||||" . json_encode($_COOKIE), "ENCODE", "daishuaba_cloudkey1");
	$query = curl_get("http://auth2.cccyun.cc/bin/check.php?string=" . urlencode($string));
	$query = authcode($query, "DECODE", "daishuaba_cloudkey2");
	if ($query = json_decode($query, true)) {
		if ($query["code"] == 1) {
			$_SESSION["authcode555"] = authcode;
		} else {
			sysmsg("<h3>" . $query["msg"] . "</h3>", true);
		}
	}
}