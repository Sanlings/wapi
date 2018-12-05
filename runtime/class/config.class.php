<?php

/**
 * @file:	config.class.php
 * @Author: lichao
 * @Date:   2018-02-06 11:47:37
 * @Last Modified by:   IDEACOM
 * @Last Modified time: 2018-03-04 01:10:39
 */
/**
 * config类，单例配置类
 */
class Config {
	protected static $ins = null;
	protected $data = array();

	/**
	 * __construct 载入配置文件，并读取配置总数组到data属性
	 */
	final protected function __construct() {
		require_once(APPPATH . 'runtime/config/config.inc.php');
		$this->data = $_CFG;
	}
	/**
	 * __clone 暂时用来占位
	 * @return [type] [description]
	 */
	final protected function __clone() {

	}

	/**
	 * getIns 实例化config类
	 * @return Object 自身对象
	 */
	public static function getIns() {
		if(self::$ins == null) {
			self::$ins = new self();
		}
		return self::$ins;
	}

	/**
	 * __get 调用config参数方法
	 * @param  String $configKey 外界调用config参数key
	 * @return mixed config项结果
	 */
	public function __get($configKey) {
		if (array_key_exists($configKey,$this->data)) {
			return $this->data[$configKey];
		}else{
			return null;
		}
		
	}

	/**
	 * __set 临时设置config方法
	 * @param String $configKey 需要设置的config参数key
	 * @param String $configValue 需要设置的config参数value
	 */
	public function __set($configKey,$configValue) {
		$this->data[$configKey] = $configValue;
	}
}



?>