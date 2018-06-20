<?php

namespace App\Http\Controllers;

use App\Pre;
use App\Yuming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;

class ChineseController extends Controller
{
    public function create()  // $num为生成汉字的数量
    {
        $b = [];
        $end = ['娱乐网','fans网','粉丝会','明星网'];
        $num =1000;
        for ($i=0; $i<$num; $i++) {
            for ($k=0;$k<count($end);$k++){
                // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            $b[$i] ='笔'.iconv('GB2312', 'UTF-8', $a).$end[$k];
            echo $b[$i];
            echo '<br>';
            }
        }

//        echo $img;
//        $this->getPic();

    }

    public function getPic(){
        header('Content-Type: text/html; charset=utf-8');
        $text = '中粮屯河（sh600737）';//中粮屯河（sh600737）
        $watermark = '305988103123zczcxzas';
        $len = strlen($text);
        $width = 10.5*(($len-8)/3*2+8);
        $height = 26;
        $imagick = new \Imagick();
        $color_transparent = new \ImagickPixel('#ffffff'); //transparent 透明色
        $imagick->newImage($width, $height, $color_transparent, 'jpg');
        //$imagick->borderimage('#000000', 1, 1);
        $style['font_size'] = 12;
        $style['fill_color'] = '#000000';
        for($num= strlen($watermark); $num>=0; $num--){
            $this->add_text($imagick,substr($watermark, $num,1), 2+($num*8), 30, 1,$style);
            $this->add_text($imagick,substr($watermark, $num,1), 2+($num*8), 5, 1,$style);
        }
        //return;
        $style['font_size'] = 20;
        $style['fill_color'] = '#FF0000';
        $style['font'] = 'fonts/iconfont.ttf'; ///微软雅黑字体 解决中文乱码
        //$text=mb_convert_encoding($text,'UTF-8'); //iconv("GBK","UTF-8//IGNORE",$text);
        $this->add_text($imagick,$text, 2, 20, 0,$style);
        header ( 'Content-type: ' . strtolower ($imagick->getImageFormat ()) );
        echo $imagick->getImagesBlob ();
    }
// 添加水印文字
    public function add_text(& $imagick, $text, $x = 0, $y = 0, $angle = 0, $style = array()) {
        $draw = new \ImagickDraw ();
        if (isset ( $style ['font'] ))
            $draw->setFont ( $style ['font'] );
        if (isset ( $style ['font_size'] ))
            $draw->setFontSize ( $style ['font_size'] );
        if (isset ( $style ['fill_color'] ))
            $draw->setFillColor ( $style ['fill_color'] );
        if (isset ( $style ['under_color'] ))
            $draw->setTextUnderColor ( $style ['under_color'] );
        if (isset ( $style ['font_family'] ))
            $draw->setfontfamily( $style ['font_family'] );
        if (isset ( $style ['font'] ))
            $draw->setfont($style ['font'] );
        $draw->settextencoding('UTF-8');
        if (strtolower ($imagick->getImageFormat ()) == 'gif') {
            foreach ( $imagick as $frame ) {
                $frame->annotateImage ( $draw, $x, $y, $angle, $text );
            }
        } else {
            $imagick->annotateImage ( $draw, $x, $y, $angle, $text );
        }
    }
}
