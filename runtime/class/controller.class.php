<?php

/**
 * 基础控制器类
 * 加载对应控制器
 * @Author: IDEACOM
 * @Date:   2018-02-27 14:21:42
 * @Last Modified by:   lichao
 * @Last Modified time: 2018-06-14 21:29:45
 * @param Array $arguement 地址栏参数数组
 * @param String $controller 请求的控制器路径
 * @param Bool 加载状态
 */
class controller
{
	public $arguement;
	public $controller;
	public $port;
	public $mode;
	public $status = FALSE;
	public $return = NULL;

	/**
	 * __construct 调用参数验证方法
	 */
	public function __construct() {
		$this->getVerify();
	}

	/**
	 * getArguement 获取请求参数，过滤参数
	 * @return NULL
	 */
	final protected function getArguement() {
		$this->arguement = trim(
			str_replace($_SERVER['SCRIPT_NAME'], '', 
						$_SERVER['REQUEST_URI']),'/'
			);
		$this->arguement = explode('/', $this->arguement);
		//print_r($this->arguement);
		if (count($this->arguement) < 3) {
			$this->arguement[0] = 'default';
			$this->arguement[1] = 'default';
			$this->arguement[2] = 'default';
		}
		$this->controller = APPPATH . 'runtime/module/' . $this->arguement[0] . '_module/controller_class/' . $this->arguement[1] . 'Control.class.php';
		$this->port = APPPATH . 'runtime/module/' . $this->arguement[0] . '_module/' . $this->arguement[0] . '_port.php';
		$this->mode = APPPATH . 'runtime/module/' . $this->arguement[0] . '_module/' . 'mode/' . $this->arguement[0] . 'Mode.class.php';
		//echo $this->mode;
		return;
	}

	/**
	 * getVerify 验证参数、权限、防止非法访问方法
	 * @return NULL
	 */
	final protected function getVerify() {
		$this->getArguement();
		if (AUTHORITY !== true) {
			log::write($this->arguement . 'error_AUTHORITY');
			exit(E_PATH);
		}
		if (!file_exists($this->controller)) {
			log::write($this->arguement[0] . '/' . $this->arguement[1] . ' error_file not exists');
			exit(E_PATH);
		}
		$this->getController();
	}

	/**
	 * getController 获取对应控制器、对应方法名方法
	 * @return Bool Status属性 执行状态  
	 */
	final protected function getController() {
		$router = $this->arguement;
		$funName = $this->arguement[2];

		#存在接口则直接引用
		if (file_exists($this->port)) {
			require_once($this->port);
		}
		
		#存在模型则直接引用
		if (file_exists($this->mode)) {
			require_once($this->mode);
			require_once($this->controller);
			$mode = new indexMode(); 
			$object = new $this->arguement[1]($router,$mode);
		}else{
			require_once($this->controller);
			$object = new $this->arguement[1]($router);
		}

		
		#判断方法是否存在
		if (!method_exists($object, $funName)) {
			log::write($this->arguement[2] . ' error_function is not callable');
			exit(E_FUNCTION);
		}
		$object->$funName();
	}

	/**
	 * [getMode 如果有mode，可以简单调用此方法进行引用]
	 * [暂时弃用]
	 * @return [NULL]
	 */
	final protected function getMode($modeName) {
		
		if (!file_exists($mode_filepath)) {
			log::write($mode_filepath . ':required modefile note exists');
			exit(E_PATH);
		}else{
			$modeClass = require_once($mode_filepath);
		}
		return $modeClass;
	}

}



?>