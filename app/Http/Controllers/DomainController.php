<?php

namespace App\Http\Controllers;

use App\Pre;
use App\Yuming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DomainController extends Controller
{
    public function index(){
        $domaindata = file('domain.txt');
        $newfile = fopen('newdomain.txt','w');
        for ($i=0;$i<count($domaindata);$i++){
            $ndomain[$i] = $domaindata[$i];
            $wwwdata[$i]= 'www.'.$domaindata[$i];
            $fandomain[$i]= '*.'.$domaindata[$i];
            fwrite($newfile, $wwwdata[$i]);
            fwrite($newfile, $fandomain[$i]);
            fwrite($newfile, $ndomain[$i]);
        }
        fclose($newfile);
        $newdata = file('newdomain.txt');

        for ($j=0;$j<count($newdata);$j++){
            echo $this->deletespace($newdata[$j]);
            echo "<br>";
        }
    }

    public function createfandomain()
    {
        $domaindata = file('domain.txt');
        $newfile = fopen('fanyuming.txt','w');

        for ($i=0;$i<count($domaindata);$i++){
            $randworld = str_random(4);
            $ndomain[$i] = $randworld.'.'.$domaindata[$i];
            echo $ndomain[$i];
            fwrite($newfile, $ndomain[$i]."\r\n");
        }
        return $domaindata;
    }

    public function createwww()
    {
        $domaindata = file('domain.txt');
        $newfile = fopen('www.txt','w');
        for ($i=0;$i<count($domaindata);$i++){
            $ndomain[$i] = 'www.'.$domaindata[$i];
            $no_rn[$i] = $this->deletespace($ndomain[$i]);
            echo $ndomain[$i];
            echo "<br>";
            fwrite($newfile, $ndomain[$i]);
        }
    }

    public function creatpushurl()
    {
        $domaindata = file('domain.txt');
        $newfile = fopen('pushurl.txt',"w");
        for ($i=0;$i<count($domaindata);$i++){
            $ndomain[$i] = 'www.'.$domaindata[$i]."/show/".str_random(8).'.html';
            $no_rn[$i] = $this->deletespace($ndomain[$i]);
            echo $no_rn[$i];
            echo "<br>";
            fwrite($newfile, $no_rn[$i]."\r"); 
        }
    }

    public function deletespace($url)
    {
      return  str_replace(array("\r\n", "\r", "\n" ,"\t"), "", $url);
    }

    public function addsonsite()
    {

        $domaindata = file('domain.txt');
        $newfile = fopen('sonsite.txt',"w");
        for ($i=0;$i<count($domaindata);$i++){
            $ndomain[$i] = 'www.'.$domaindata[$i]."/show/".str_random(8).'.html';
            $no_rn[$i] = $this->deletespace($ndomain[$i]);
            echo $no_rn[$i];
            echo "<br>";
            fwrite($newfile, $no_rn[$i]."\r");
            fwrite($newfile, $no_rn[$i]."\r");
            fwrite($newfile, $no_rn[$i]."\r");
            fwrite($newfile, $no_rn[$i]."\r");
            fwrite($newfile, $no_rn[$i]."\r\n");
        }
    }


    public function addurl()
    {
        $domaindata = file('domain.txt');

        for ($i=0;$i<count($domaindata);$i++){
            $data[$i]['name'] = $this->deletespace($domaindata[$i]);
            $data[$i]['team'] = 2;
            $data[$i]['status'] = 1;
            $data[$i]['created_at'] = date('Y-m-d h:i:s');
            $data[$i]['updated_at'] = date('Y-m-d h:i:s');
        }
        $bool=Yuming::insert($data);
        dd($bool);
    }

    public function addpres()
    {
        $pre = file('pre.txt');
        for ($i=0;$i<count($pre);$i++){
            $data[$i]['name'] = $this->deletespace($pre[$i]);
            $data[$i]['team'] = 1;
            $data[$i]['status'] = 0;
            $data[$i]['created_at'] = date('Y-m-d h:i:s');
            $data[$i]['updated_at'] = date('Y-m-d h:i:s');
        }
        $bool = Pre::insert($data);
        dd($bool);
    }

    public function createmuluurl()
    {
        $pre = file('pre.txt');
        for ($i=0;$i<count($pre);$i++){
        for ($k=0;$k<5;$k++){
          echo  $data[$i] = $this->deletespace($pre[$i]).date('Ymd').'/'.rand(100,999).'.html';
          echo '<br>';
        }
        }
    }

}
