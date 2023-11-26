<?php
error_reporting(0);
// 引入类
require_once('inc/require.php');

if (isset($_GET['id'])) {

    $url_c = new url();

    // 获取目标网址
    $url = $url_c->get_url($_GET['id']);
    // 重定向至目标网址
    if ($url) {
        if (!is_mobile()) {
            header('location: ' . $url);
        } else {
            preg_match('/QQ\/\d+\.\d+/', $_SERVER['HTTP_USER_AGENT'], $isQQ);
            if ($_GET['open'] == 1 && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                header("Content-Disposition: attachment; filename=\"load.doc\"");
                header("Content-Type: application/vnd.ms-word;charset=utf-8");
                echo_jump($url);
            } elseif (!empty($isQQ)) {
                echo_jump($url);
            } else {
                header('location: ' . $url);
            }
        }
    }
    exit;
}

function echo_jump($url)
{
    echo <<<EOF
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Welcome</title><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/><meta content="yes" name="apple-mobile-web-app-capable"/><meta content="black" name="apple-mobile-web-app-status-bar-style"/><meta name="format-detection" content="telephone=no"/><meta content="false" name="twcClient" id="twcClient"/><script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script><style>body,html,#body{width:100%;height:100%}*{margin:0;padding:0}body{background-color:#fff}.top-bar-guidance{font-size:15px;color:#fff;height:40%;line-height:1.8;padding-left:20px;padding-top:20px;background:url(//gw.alicdn.com/tfs/TB1eSZaNFXXXXb.XXXXXXXXXXXX-750-234.png) center top/contain no-repeat}.top-bar-guidance .icon-safari{width:25px;height:25px;vertical-align:middle;margin:0 .2em}.app-download-btn{display:block;width:214px;height:40px;line-height:40px;margin:18px auto 0 auto;text-align:center;font-size:18px;color:#2466f4;border-radius:20px;border:.5px #2466f4 solid;text-decoration:none}</style></head><body><div id="body"><div class="top-bar-guidance"><p>点击右上角<img src="//gw.alicdn.com/tfs/TB1xwiUNpXXXXaIXXXXXXXXXXXX-55-55.png" class="icon-safari" /> Safari打开</p><p>可以继续访问本站哦~</p></div><a class="app-download-btn" id="BtnClick" href="javascript:;">点此继续访问</a></div><script>

    var url = '$url'; //填写要跳转到的网址

    document.querySelector('body').addEventListener('touchmove', function (event) {
        event.preventDefault();
    });
    window.mobileUtil = (function(win, doc) {
        var UA = navigator.userAgent,
            isAndroid = /android|adr/gi.test(UA),
            isIOS = /iphone|ipod|ipad/gi.test(UA) && !isAndroid,
            isBlackBerry = /BlackBerry/i.test(UA),
            isWindowPhone = /IEMobile/i.test(UA),
            isMobile = isAndroid || isIOS || isBlackBerry || isWindowPhone;
        return {
            isAndroid: isAndroid,
            isIOS: isIOS,
            isMobile: isMobile,
            isWeixin: /MicroMessenger/gi.test(UA),
            isQQ: /QQ\/\d+\.\d+/gi.test(UA)
        };
    })(window, document);

    if(mobileUtil.isWeixin){
        if(mobileUtil.isIOS){
            url = "https://t.asczwa.com/taobao?backurl=" + encodeURIComponent(url);
            document.getElementById('BtnClick').href=url;
        }else if(mobileUtil.isAndroid){
            url = '?open=1';
            document.getElementById('BtnClick').href=url;
            var iframe = document.createElement("iframe");
            iframe.style.display = "none";
            iframe.src = url;
            document.body.appendChild(iframe);
        }
    }else if(mobileUtil.isQQ){
        $('#body').html('<iframe width="100%" id="preview" src="'+url+'" frameborder="0" ></iframe>');
        $(document).ready(function(){
            function fix_height(){  $("#preview").attr("height", (($(window).height())-5) + "px");}
            $(window).resize(function(){ fix_height(); }).resize();
            $("#preview").contentWindow.focus();
        });
    } else {
        document.getElementsByName('BtnClick').href=url;
        window.location.replace(url);
    }
</script>
</body>
</html>
EOF;
}

// 查看是否为手机端的方法
//判断是手机登录还是电脑登录
function is_mobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo get_title() . ' - ' . get_description(); ?></title>
    <meta name="description" content="">
    <meta name="keyword" content="">
    <link type="text/css" rel="stylesheet" href="asset/css/main.css">
    <style type="text/css">@font-face {
            font-family: Qiu;
            src: url(http://oss.xhw6.cn/static/style/ttf/qiuyan.ttf)
        }</style>
</head>
<body>
<div class="wrap">
    <div class="meta"><h2 class="title"><span style="font-family:Qiu"><?php echo get_title(); ?></span></h2>
        <h3 class="description"><span style="font-family:Qiu">网址防红转发、超长链接缩短</span></h3></div>
    <div class="link-area"><input id="url" type="text" placeholder="https://"> <input id="submit" type="button"
                                                                                      value="立即生成"
                                                                                      onclick="APP.fn.setUrl(this)">
    </div>
    <div class="footer"><span style="font-family:Qiu">&copy; 2016-2018 <a href="http://www.duoweizhe.top"
                                                                          target="__blank">多维者工作室</a> All Rights Reserved.</span>
    </div>
</div>
</body>
<script type="text/javascript" src="asset/js/app.js"></script>
<script type="text/javascript" src="https://js.users.51.la/19557635.js"></script>
</html>