<?php
defined('PAO') || die('The PaoCMS Load Error');


$session['storage']['files']['save_path'] = PAO.'/RunTime/Session';


$session['storage']['db']['name'] = ''; //保存到据据库名称参见database配置文件
$session['storage']['db']['table'] = 'pao_session'; //保存表名

$session['storage']['redis']['host'] = '127.1';
$session['storage']['redis']['port'] = '6379';

/**
 * the options configure @see http://php.net/session.configuration
 * cache_limiter, "" (use "0" to prevent headers from being sent entirely).
 * cookie_domain, ""
 * cookie_httponly, ""
 * cookie_lifetime, "0"
 * cookie_path, "/"
 * cookie_secure, ""
 * entropy_file, ""
 * entropy_length, "0"
 * gc_divisor, "100"
 * gc_maxlifetime, "1440"
 * gc_probability, "1"
 * hash_bits_per_character, "4"
 * hash_function, "0"
 * name, "PHPSESSID"
 * referer_check, ""
 * serialize_handler, "php"
 * use_cookies, "1"
 * use_only_cookies, "1"
 * use_trans_sid, "0"
 * upload_progress.enabled, "1"
 * upload_progress.cleanup, "1"
 * upload_progress.prefix, "upload_progress_"
 * upload_progress.name, "PHP_SESSION_UPLOAD_PROGRESS"
 * upload_progress.freq, "1%"
 * upload_progress.min-freq, "1"
 * url_rewriter.tags, "a=href,area=href,frame=src,form=,fieldset="
 */