<?php
defined('PAO') || die('The PaoCMS Load Error');

$config['log']          = true; //是否生成日志总开关
$config['debug']        = true; //debug模式
$config['token']        = 'pao_'; //网站标识
$config['timezone']     = 'PRC'; //系统时区
$config['charset']      = 'utf-8'; //系统编码
$config['language']     = 'zh-CN'; //默认语言包
$config['session']      = 'files'; //Session存储方式

$config['dir']['pao']   = PAO;//系统根目录
$config['dir']['web']   = PAO.DIRECTORY_SEPARATOR.'web'; //公共资源目录
$config['dir']['log']   = PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Logs'; //日志存放目录
$config['dir']['file']  = 'archives'; //文件上传目录
$config['dir']['cache'] = PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache'; //缓存存放目录