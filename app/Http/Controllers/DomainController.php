<?php

namespace App\Http\Controllers;

use App\Group;
use App\Pre;
use App\Yuming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DomainController extends Controller
{

    public function index()
    {
        $userid = Auth::user()->id;
        $group = Group::where(['userid'=>$userid])->get();
        return view('yuming.index',compact('group'));
    }


    public function list(Request $request)
    {
        $teamid = $request->input('teamid');
        $teamyuming = Yuming::where(['team'=>$teamid])->get();
        return view('yuming.list',compact('teamyuming','teamid'));
    }

    public function add(Request $request)
    {
        $teamid = $request->input('teamid');
        $teamyuming = Yuming::where(['team'=>$teamid])->get();
        return view('yuming.add',compact('teamyuming','teamid'));
    }

    public function doadd(Request $request)
    {
        $yuming =$request->input('yuming');
        $teamid =$request->input('teamid');
        $yuming = explode("\r\n", $yuming);
        foreach($yuming as $key=>$item){
            $where[$key]['name'] = $item;
            $where[$key]['team'] = $teamid;
            $where[$key]['created_at'] = date('Y-m-d h:i:s');
            $where[$key]['updated_at'] = date('Y-m-d h:i:s');
        }
        if (!empty($teamid)){
            $deletebool =  Yuming::where(['team'=>$teamid])->delete();
            $insertbool =  Yuming::insert($where);
            if ($insertbool){
               echo '域名保存成功，请自行关掉';
            }
        }
    }

    public function update()
    {
        return view('yuming.update');
    }

    public function addteam()
    {
        return view('yuming.addteam');
    }

    public function doaddteam(Request $request)
    {
        $name =$request->input('name');
        $token =$request->input('token');
        $userid = Auth::user()->id;
        $data = ['name'=>$name,'token'=>$token,'created_at'=>date('Y-m-d'),'updated_at'=>date('Y-m-d'),'userid'=>$userid];
        $bool = Group::insert($data);
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'新增成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'新增失败']);
        }
    }

    public function index1(){
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
        $pres = Pre::all();
        for ($i=0;$i<count($domaindata);$i++){
            for ($j=0; $j<$pres->count() ;$j++){
                $ndomain[$i] = $pres->toArray()[$j]['name'].'.'.$this->deletespace($domaindata[$i]).'/'.date('Ymd').rand(1000,9999).'.html';
                echo $ndomain[$i];
                echo '<br>';
                fwrite($newfile, $ndomain[$i]."\r\n");
            }
        }
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
            $data[$i]['team'] = 3;
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
          echo  $data[$i] = $this->deletespace($pre[$i]).date('Ymd').'/'.rand(100,999);
          echo '<br>';
            }
        }
    }

    public function muluurl()
    {

        for ($i=0;$i<2000;$i++){
            echo 'http://d.958shop.com/bbk'.str_random(5);
            echo '<br>';
        }
    }

    public function baoxianurl(){
        $baoxian = file('domain.txt');
        $pre = file('pre.txt');
        for ($i=0; $i <count($baoxian) ; $i++) { 
            # code...
            for ($j=0; $j <count($pre) ; $j++) { 
                # code...
                for ($l=0; $l <20 ; $l++) { 
                      $data[$i] = $this->deletespace($pre[$j]).'.'. $this->deletespace($baoxian[$i]).'/'.$this->deletespace($pre[$j]).'/'.date('Ymd').rand(1000,9999).'.html';
                echo $data[$i];
                echo "<br>";
                }
              
            }
        }
    }

    public function bijiao()
    {
        $haode = file('zz.txt');
        $all = file('ss.txt');

        for ($i=0;$i<count($all);$i++){
            for ($j=0;$j<count($haode);$j++){
                if ($all[$i] == $haode[$j]){
                    $data[$i] = $all[$i];
                }else{
                    $z[$i] = $haode[$j];
                }
            }
        }

        dd($z);
    }

}
