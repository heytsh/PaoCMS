<?php

namespace Manage\Controller;




use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Response;
use Manage\Model\Config;
use Manage\Model\Member;
use Manage\Model\Admin;
use Manage\Model\AdminGroup;


class Index extends Controller
{


    public function index()
    {

/*        Admin::down();
        Admin::up();

        AdminGroup::down();
        AdminGroup::up();

        Member::down();
        Member::up();*/

       // Session::set('11','2l2l22222222222');

        Cookie::set('ddd',date('Y-m-d H:i:s'));

        Cookie::del('ddd');
//        print_r(Cookie::all());

        //$redis = $this->container->make('redis');


        //$d= $this->container->make('config')->get('cache');


        /**
         * @var Illuminate\Database\Eloquent\Model $config
         */

        //Config::create(['key' => uniqid(),'value'=>uniqid()]);
        //Config::create(['key' => uniqid(),'value'=>uniqid()]);
        print_r(Config::all()->toArray());
        //print_r((new Admin)->getTable());
        //print_r(Admin::table());

      //  echo class_basename($this); //str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));

        $d = \Config::get('database');



        //$redis = \Redis::info();

        //print_r($redis);


        //('response')->show('ddd');

        //$this->container->make('response')->show('dwww');

        $this->assign('class', __CLASS__);
        echo '<hr />';

        Response::view('index');
    }

}