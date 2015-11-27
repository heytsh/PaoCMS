<?php
defined('PAO') || die('The PaoCMS Load Error');

define('DS', DIRECTORY_SEPARATOR);


return array(

        'debug'=>true, //debug模式

        'log'=>true, //是否生成日志总开关

        //系统相关目录
        'dir'=>array(
            'pao'=> dirname(__DIR__) , //系统根目录
            'web'=> dirname(__DIR__).DS.'web', //公共资源目录
            'logs'=> dirname(__DIR__).DS.'RunTime'.DS.'logs', //日志存放目录
            'cache'=> dirname(__DIR__).DS.'RunTime'.DS.'Cache', //缓存存放目录

        ),

        //系统相关设置
        'system'=>array(
            'timezone'=>'PRC', //系统时区
            'charset'=>'utf-8', //系统编码
        ),


);