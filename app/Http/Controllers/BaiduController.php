<?php

namespace App\Http\Controllers;

use App\Nav;
use App\Pre;
use App\Yuming;
use Illuminate\Http\Request;

class BaiduController extends Controller
{

    public function pushtoken()
    {
//      phpinfo();
        $token = 'mlkC9Yzjf8o2KEBf'; //我的token
        $yuming = Yuming::all();
        $pre = Pre::all();
        $nav = Nav::all();
        foreach ($yuming as $key=>$item){
            foreach ($pre as $key1=>$item1){
                foreach ($nav as $key2=>$item2){
                    for($i=0;$i<2000;$i++){
                        $url[$key][$key1][$key2][$i]='http://'.$item1->name.'.'.$item->name.'/'.$item2->name.'/'.str_random(9).'.html';
                    }
                    $posturl = 'http://data.zz.baidu.com/urls?site='.$item1->name.'.'.$item->name.'&token='.$token;
                    $ch = curl_init();
                    $options =  array(
                        CURLOPT_URL => $posturl,
                        CURLOPT_POST => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS => implode("\n", $url[$key][$key1][$key2]),
                        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                    );
                    curl_setopt_array($ch, $options);
                    $result = curl_exec($ch);
                    echo '当前第'.($key+1).'条域名。当前前缀'.$item1->name.'。域名:'.$item->name.'站点生成的'.count($url[$key][$key1][$key2]).'条url正在推送';
                    echo '<br>';
                    echo '返回结果:'.$result;
                    echo '<br>';
                    ob_flush();
                    flush();
                }
            }

        }
        dd('推送结束.欢迎使用George牌推送工具');
    }
}
