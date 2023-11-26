<?php
//file_put_contents('0','1');
define('IN_API', true);
define('CURSCRIPT', 'api');
$filename = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
preg_match("/(\w+)\_(\w+)\.php/i", $filename, $m);
define('PAYTYPE', $m[1]);
require '../api_return.php';
?>