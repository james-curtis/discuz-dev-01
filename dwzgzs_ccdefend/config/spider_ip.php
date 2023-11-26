<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 19:59
 */



function get_spider_ip()
{
    $baidu = explode("\n",'/123\.125\.68\.\d+/
/220\.181\.68\.\d+/
/220\.181\.7\.\d+/
/123\.125\.66\.\d+/
/121\.14\.89\.\d+/
/203\.208\.60\.\d+/
/210\.72\.225\.\d+/
/125\.90\.88\.\d+/
/220\.181\.108\.95/
/220\.181\.108\.92/
/123\.125\.71\.106/
/220\.181\.108\.91/
/220\.181\.108\.75/
/220\.181\.108\.86/
/123\.125\.71\.95/
/123\.125\.71\.97/
/220\.181\.108\.89/
/220\.181\.108\.94/
/220\.181\.108\.97/
/220\.181\.108\.80/
/220\.181\.108\.77/
/123\.125\.71\.117/
/220\.181\.108\.83/
/220\.181\.108\.\d+/');

    $google = explode("\n",'/216\.239\.33\.\d+/
/216\.239\.35\.\d+/
/216\.239\.37\.\d+/
/216\.239\.39\.\d+/
/216\.239\.51\.\d+/
/216\.239\.53\.\d+/
/216\.239\.55\.\d+/
/216\.239\.57\.\d+/
/216\.239\.59\.\d+/
/64\.233\.161\.\d+/
/64\.233\.189\.\d+/
/66\.102\.11\.\d+/
/66\.102\.7\.\d+/
/66\.102\.9\.\d+/
/66\.249\.64\.\d+/
/66\.249\.65\.\d+/
/66\.249\.66\.\d+/
/66\.249\.71\.\d+/
/66\.249\.72\.\d+/
/72\.14\.207\.\d+/
/202\.101\.43\.\d+/
/222\.73\.247\.\d+/
/66\.249\.65\.\d+/
/66\.249\.16\.\d+/
/210\.72\.225\.\d+/
/203\.208\.60\.\d+/');

    $safe360 = explode("\n",'/101\.226\.166\.\d+/
/101\.226\.167\.\d+/
/101\.226\.168\.\d+/
/101\.226\.169\.\d+/
/180\.153\.236\.\d+/
/182\.118\.20\.\d+/
/182\.118\.21\.\d+/
/182\.118\.22\.\d+/
/182\.118\.25\.\d+/
/182\.118\.28\.\d+/
/61\.55\.185\.\d+/
/220\.181\.126\.\d+/
/182\.118\.26\.110/
/182\.118\.26\.239/');

    $sougou = explode("\n",'/123\.126\.113\.79/
/123\.126\.113\.191/
/220\.181\.89\.190/
/220\.181\.89\.189/
/218\.30\.103\.155/
/61\.135\.189\.75/
/220\.181\.94\.228/
/61\.135\.189\.74/
/220\.181\.89\.157/
/220\.181\.89\.165/
/220\.181\.89\.183/
/220\.181\.89\.194/
/218\.30\.103\.80/');

    $what = explode("\n",'/42\.156\.136\.\d+/
/42\.156\.137\.\d+/
/42\.156\.138\.\d+/
/42\.156\.139\.\d+/
/42\.120\.160\.\d+/
/42\.120\.161\.\d+/');

    $serach_163 = explode("\n",'/202\.106\.186\.\d+/
/202\.108\.36\.\d+/
/202\.108\.44\.\d+/
/202\.108\.45\.\d+/
/202\.108\.5\.\d+/
/202\.108\.9\.\d+/
/220\.181\.12\.\d+/
/220\.181\.13\.\d+/
/220\.181\.14\.\d+/
/220\.181\.15\.\d+/
/220\.181\.28\.\d+/
/220\.181\.31\.\d+/
/222\.185\.245\.\d+/');

    $srearch_iask = '/61\.135\.152\.\d+/';

    $search_msn = explode("\n",'/65\.54\.188\.\d+/
/65\.54\.225\.\d+/
/65\.54\.226\.\d+/
/65\.54\.228\.\d+/
/65\.54\.229\.\d+/
/207\.46\.98\.\d+/
/207\.68\.157\.\d+/');

    $sousou = explode("\n",'/219\.133\.40\.\d+/
/202\.96\.170\.\d+/
/202\.104\.129\.\d+/
/61\.135\.157\.\d+/');

    $sina = explode("\n",'/219\.142\.118\.\d+/
/219\.142\.78\.\d+/');

    $souhu = explode("\n",'/61\.135\.132\.\d+/
/220\.181\.26\.\d+/
/220\.181\.19\.\d+/');

    $yahoo = explode("\n",'/66\.196\.90\.\d+/
/66\.196\.91\.\d+/
/68\.142\.249\.\d+/
/68\.142\.250\.\d+/
/68\.142\.251\.\d+/
/72\.30\.101\.\d+/
/72\.30\.102\.\d+/
/72\.30\.103\.\d+/
/72\.30\.104\.\d+/
/72\.30\.107\.\d+/
/72\.30\.110\.\d+/
/72\.30\.111\.\d+/
/72\.30\.128\.\d+/
/72\.30\.129\.\d+/
/72\.30\.131\.\d+/
/72\.30\.133\.\d+/
/72\.30\.134\.\d+/
/72\.30\.135\.\d+/
/72\.30\.216\.\d+/
/72\.30\.226\.\d+/
/72\.30\.252\.\d+/
/72\.30\.97\.\d+/
/72\.30\.98\.\d+/
/72\.30\.99\.\d+/
/74\.6\.74\.\d+/
/202\.165\.102\.\d+/
/202\.160\.178\.\d+/
/202\.160\.179\.\d+/
/202\.160\.180\.\d+/
/202\.160\.181\.\d+/
/202\.160\.183\.\d+/');

    $bing = explode("\n",'/103\.25\.156\.\d+/
/157.55.39\.\d+/
/207.46.13\.\d+/');

    return array(
        'baidu' => $baidu,
        'google' => $google,
        'safe360' => $safe360,
        'sougou' => $sougou,
        'what' => $what,
        'serach_163' => $serach_163,
        'srearch_iask' => $srearch_iask,
        'search_msn' => $search_msn,
        'sousou' => $sousou,
        'sina' => $sina,
        'souhu' => $souhu,
        'yahoo' => $yahoo
    );
}
