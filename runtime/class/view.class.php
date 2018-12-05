<?php

/**
 * @Author: IDEACOM
 * @Date:   2018-03-03 21:43:36
 * @Last Modified by:   IDEACOM
 * @Last Modified time: 2018-03-21 19:57:37
 */

/**
* 
*/
class view
{
	public static $data;
	public static $return;
	//public static $data = array();

	/**
	 * model 错误回调
	 * @return [type] [description]
	 */
	public static function errorModel() {

	}

	/**
	 * json_display 以JSON格式渲染输出（微信端）
	 * @return NULL
	 */
	public static function json_display($return) {
		echo json_encode(
			$return,
			JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
		); 
	}

	/**
	 * display 普通渲染方法（web端）
	 * @return NULL
	 */
	public static function display($data,$module,$view) {
		//$data = self::$data;
		//print_r($data);
		extract($data);
		$filePath = APPPATH . 'runtime/module/' . $module . '_module/view/' . $view . '.html';
		require_once($filePath);
	}
}








?>