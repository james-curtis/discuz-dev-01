<?php
$info=array();
$info['name']='nimba_attachlog';
$info['version']='v1.0.2';
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$info['siteversion']=DISCUZ_VERSION;
$info['siterelease']=DISCUZ_RELEASE;
$info['timestamp']=TIMESTAMP;
$info['nowurl']=$_G['siteurl'];
$info['siteurl']='http://520haian.com/';
$info['clienturl']='http://www.520haian.com/';
$info['siteid']='75575970-1D93-AB50-1E98-D8E4E0A9E6B6';
$info['sn']='2013072501E4e55a6R9E';
$info['adminemail']=$_G['setting']['adminemail'];
$infobase=base64_encode(serialize($info));
?>