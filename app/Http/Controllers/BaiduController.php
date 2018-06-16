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
        $token = 'JNhSyUDGYC4ak7xW'; //我的token
        $yuming = Yuming::where(['team'=>4])->get();
        $pre = Pre::all();
        $nav = Nav::all();
        foreach ($yuming as $key=>$item){
//            foreach ($pre as $key1=>$item1){
                foreach ($nav as $key2=>$item2){
                    for($i=0;$i<200;$i++){
                        $url[$key][$key2][$i]='http://www.'.$item->name.'/'.$item2->name.'/z'.date('Ymd').rand(1000,9999).'.html';
                    }
                    $posturl = 'http://data.zz.baidu.com/urls?site=www.'.$item->name.'&token='.$token;
                    $ch = curl_init();
                    $options =  array(
                        CURLOPT_URL => $posturl,
                        CURLOPT_POST => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS => implode("\n", $url[$key][$key2]),
                        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                    );
                    curl_setopt_array($ch, $options);
                    $result = curl_exec($ch);
                    echo '当前第'.($key+1).'条域名。前缀www。域名'.$item->name.'.栏目:'.$item2->name.'.'.count($url[$key][$key2]).'条url正在推送';
//                    for ($i=0;$i<count($url[$key][$key2]);$i++){
//                        echo $url[$key][$key2][$i];
//                        echo '<br>';
//                    }
                    echo '<br>';
                    echo '返回结果:'.$result;
                    echo '<br>';
                    ob_flush();
                    flush();
                }
            echo '<br>'.'休息半小时接着推送';
//            sleep(1800);

        }
        dd('推送结束.欢迎使用George牌推送工具');
    }


    public function pingBaidu($url)
    {
        $pingRpc  = 'http://ping.baidu.com/ping/RPC2';
        $baiduXML = '<?xmlversion="1.0"?>';
        $baiduXML .= '<methodCall>';
        $baiduXML .= '<methodName>weblogUpdates.ping</methodName>';
        $baiduXML .= '<params>';
        $baiduXML .= '<param><value><string>' . $url . '</string></value></param>';
        $baiduXML .= '<param><value><string>' . $url . '</string></value></param>';
        $baiduXML .= '</params>' . "\n";
        $baiduXML .= '</methodCall>';
        $header   = array(
            'Accept: */*',
            'Referer: http://ping.baidu.com/ping.html',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36',
            'Host:ping.baidu.com',
            'Content-Type:text/xml',
        );
        $curl     = curl_init();
        curl_setopt($curl, CURLOPT_URL, $pingRpc);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $baiduXML);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function ping()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($userAgent, 'Baiduspider') === FALSE) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $protocol = '';
            if ($_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
                $protocol = 'http://';
            } else if ($_SERVER['SERVER_PROTOCOL'] === 'HTTP/2.0') {
                $protocol = 'https://';
            }
            if (stripos($protocol, 'https://') !== FALSE || stripos($protocol, 'http://') !== FALSE) {
                $host = $protocol . $_SERVER['HTTP_HOST'];
                $url = $host . $requestUri;
                $result = $this->pingBaidu($url);
                if ($result == 0) {
                    echo 'ping success';
                } else {
                    echo 'ping fail';
                }
            }
        }
    }

}
