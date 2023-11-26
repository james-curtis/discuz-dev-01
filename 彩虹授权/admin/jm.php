<?php
/**
 * 源码加密
**/
$mod='blank';
include("../api.inc.php");
$title='源码加密';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
require_once('./lib/encipher.min.php');

echo "<pre>\n本加密为难逆加密，请勿多次加密导致源码损坏\n以下为加密文件列表\n\n升级源码:\n";
$dir1 = ROOT.PACKAGE_DIR. '/update'; //加密的文件目录
$encipher = new Encipher($dir1, $dir1);
/**
 * 设置加密模式 false = 低级模式; true = 高级模式
 * 低级模式不使用eval函数
 * 高级模式使用了eval函数
 */
$encipher->advancedEncryption = true;
//设置注释内容
$encipher->comments = array(
    '欢迎使用白云授权系列程序',
    '本源码已经自动加密了',
    '请勿尝试破解，或者泄露，否则取消授权',
    '如有问题请联系qq: 760611885'
);
$encipher->encode();
echo "\n完整源码\n";
$dir2 = ROOT.PACKAGE_DIR. '/release'; //加密的文件目录
$encipher = new Encipher($dir2, $dir2);
/**
 * 设置加密模式 false = 低级模式; true = 高级模式
 * 低级模式不使用eval函数
 * 高级模式使用了eval函数
 */
$encipher->advancedEncryption = true;
//设置注释内容
$encipher->comments = array(
    '欢迎使用白云授权系列程序',
    '本源码已经自动加密了',
    '请勿尝试破解，或者泄露，否则取消授权',
    '如有问题请联系qq: 760611885'
);
$encipher->encode();
echo "</pre>\n";
?>
</div>
  </div>