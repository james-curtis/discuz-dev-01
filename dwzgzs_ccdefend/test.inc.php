<?php
$url = 'uc_server/avatar.php?uid=12562&size=small';
$more = explode('&',explode('?',$url)[1]);
$param = array();
$param['uid'] = explode('=',$more[0])[1];
$param['size'] = explode('=',$more[1])[1];
print_r($param);