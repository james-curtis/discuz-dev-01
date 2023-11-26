<?php


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_dwzgzs_ccdefend {
    function common() {
        global $_G;
        require_once DISCUZ_ROOT.'/source/plugin/dwzgzs_ccdefend/function/common.php';
        $set = getSetting();
        //打开CC防护
        if ($set['on_open']) {

            $set_time = $set['set_time'];
            $set_count = $set['set_count'];
            $ip = getip($set['on_cdn']);
            $url = base64_decode($_G['currenturl_encode']);

            //刷新次数设置为0
            if ($set_count == 0)
            {
                httpStatus(503);
            }

            $time = gettime();
            $last = C::t('#dwzgzs_ccdefend#ccdefend')->select($ip);

            //如果没有找到，即新访客访问，就插入一条数据
            if (!$last)
            {
                C::t('#dwzgzs_ccdefend#ccdefend')->insert($ip,$ua,$time,1);
                //exit;
                return;
                //exit;
            }
            //如果超过单位时间就在数据库归一
            if ($time - $last['time'] > $set_time)
            {
                C::t('#dwzgzs_ccdefend#ccdefend')->setOne($ip,$time);
                return;


            }
            //在单位时间内
            elseif ($time - $last['time'] <= $set_time) {
                //没有超过规定的刷新次数就增加一次
                if ($last['count'] <= $set_count) {
                    C::t('#dwzgzs_ccdefend#ccdefend')->addOne($ip);
                    return;


                }
                //超过规定的刷新次数
                else {
                    httpStatus(503);
                }
            }

        }




    }
}

?>