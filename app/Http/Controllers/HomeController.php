<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Script\MultiProcessBase;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function test()
    {
        $scheduler= [1,2,3,4];

        MultiProcessBase::instance()->scheduler(array(1,2,3,4,5,6,7,8,9,20))->processor(function($key, $value, MultiProcessBase $p) {
            sleep(1);
            echo '12';

        })->bootstrap();
    }

    


}
