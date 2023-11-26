<?php
$ua = 'Mozilla/5.0 (Linux; Android 8.1; MI 8 Build/OPM1.171019.026; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/6.2 TBS/044204 Mobile Safari/537.36 V1_AND_SQ_7.7.0_882_YYB_D QQ/7.7.0.3640 NetType/WIFI WebP/0.3.0 Pixel/1080';
preg_match('/QQ\/\d+\.\d+/',$ua,$isQQ);
print_r($isQQ);