<?php

/**
 * @Author: lichao
 * @Date:   2018-02-07 01:17:50
 * @Last Modified by:   IDEACOM
 * @Last Modified time: 2018-02-19 02:00:24
 */

/**
* 日志记录、读取、备份类
*/
class Log
{
	//默认记录中的日志文件名
	const LOGFILE = 'current.log';
	/**
	 * write 写日志内容方法 
	 * @param  mixed $content 日志内容
	 * @return [type]          [description]
	 */
	public static function write($content) {
		//日志标签信息采集
		//缺少判断参数类型并执行转换的方法
		$log = self::backtrace($content);
		//判断日志文件大小
		$logFile = self::isBackup();
		//执行写日志
		$fsrc = fopen($logFile, 'ab');
		fwrite($fsrc, $log . "\r\n");
		fclose($fsrc);
	}
	/**
	 * read 读日志内容方法
	 * @return [type] [description]
	 */
	public static function read() {

	}

	/**
	 * backup 将超过1MB的日志进行备份
	 * @param  String $logFile 旧log文件路径
	 * @return null 无return值
	 */
	public static function backup($logFile) {
		$newLogName = dirname($logFile) . '/LOG_' . date('YmdHis', filectime($logFile)) . '-' . date('YmdHis') . '.logbak';
		rename($logFile,$newLogName);
		touch($logFile);
		clearstatcache();
	}

	/**
	 * isBackup 判断日志文件大小方法
	 * @return String $logFile 日志路径
	 */
	public static function isBackup() {
		$logFile = APPPATH . 'data/log/' . self::LOGFILE;
		//判断日志文件是否存在，用于初次运行或清除过日志文件后
		if (file_exists($logFile)) {
			//清除filesizi()缓存，避免产生日志超过1MB的情况
			clearstatcache(true,$logFile);
			//检测日志文件大小，并备份超过1MB的文件
			if (filesize($logFile) >= 1024 * 1024) {
				self::backup($logFile);
				return $logFile;
			}else {
				return $logFile;
			}
		}else{
			//文件不存在就直接创建默认初始文件
			touch($logFile);
			return $logFile;
		}
	}

	/**
	 * backtrace 回溯方法
	 * @param  String $content 日志内容
	 * @return String 日志标签信息
	 */
	private static function backtrace($content) {
		$logTag = debug_backtrace();
		//print_r($logTag);
		//日志标签
		return LOG_TAG.'[Class:'.$logTag[2]['class'].']'.'[Method:'.$logTag[2]['function'].']'.'[Line:'.$logTag[2]['line'].']'.'[Content:'.$content.']';
	}
}



?>