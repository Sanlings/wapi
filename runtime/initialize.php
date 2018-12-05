<?php
/**
 * init初始化
 * @Author: lichao
 * @Date:   2018-02-06 19:29:11
 * @Last Modified by:   lichao
 * @Last Modified time: 2018-06-16 21:12:33
 */
//定义程序根目录绝对路径
define('APPPATH', str_replace('\\','/',dirname(dirname(__FILE__)) . '/'));
//定义调试选项
define('DEBUG', false);
//定义报错级别
defined('DEBUG') ? error_reporting(E_ALL) : error_reporting(0);
//定义全局时区
date_default_timezone_set('PRC');
//定义日志标签时间戳
define('LOG_TAG', date('[Y-m-d--H:i:s]'));
//定义访问权限
define('AUTHORITY',true);
//定义页面错误信息（1）
define('E_PATH', '请求的页面不存在!');
define('E_FUNCTION', '请求的信息不存在!');
define('E_NOAUTH', '无权限访问该页面');

//自动加载
spl_autoload_register('autoload');

function autoload($className) {
	$tmpPath = APPPATH . 'runtime/class/' . strtolower($className) . '.class.php';
	if (file_exists($tmpPath)) {
		require_once($tmpPath);
	}
}

?>