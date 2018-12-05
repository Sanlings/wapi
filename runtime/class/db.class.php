<?php
/**
 * @Author: lichao
 * @Date:   2018-02-06 19:34:42
 * @Last Modified by:   IDEACOM
 * @Last Modified time: 2018-03-16 19:41:33
 */

/**
* db基础类
*/
abstract class Db
{
    /**
	 * __construct 执行连接数据库、query操作
	 * @param Object $config    执行MYSQL类必须的配置参数
	 * @param Array $arguement 执行MYSQL类必须的请求参数
	 */
	abstract protected function __construct($arguement);

	/**
	 * __destruct 关闭MySQL数据库连接并初始化参数
	 */
    abstract public function __destruct();
    
	/**
	 * __clone 禁止克隆函数
	 * @return NUll
	 */
	abstract protected function __clone();

	/**
	 * connect  数据库连接方法
	 * @param  Object $config 配置文件对象参数
	 * @return conn属性
	 */
	abstract protected function connect();
	
	/**
	 * getInstence 获取自身实例
	 * @return Object 自身实例
	 */
	abstract public function get($arguement);

	/**
	 * getQuery query操作方法
	 * @return mixed src|bool
	 */
	abstract protected function getQuery();

	/**
	 * getSrcResult 获取资源类型结果，需要用到fetch_解析的
	 * @return src result属性
	 */
	abstract protected function getSrcResult();

	/**
	 * getBoolResult 获取布尔值类型结果，无需解析函数
	 * @return bool result属性
	 */
	abstract protected function getBoolResult();
	
	/**
	 * getNomallTrans($arguement) 执行多值同步对应SQL字段、值的转换
	 * @param  Array $arguement SQL语句分解
	 * @return 抛回修改后的$arguement
	 */
    abstract protected function getNomallTrans($arguement);
    
	/**
	 * getUpdateTrans($arguement) 执行多值单独对应SQL字段、值的转换（用于SET转换）
	 * @param  Array $arguement SQL语句分解
	 * @return 抛回修改后的$arguement
	 */
	abstract protected function getUpdateTrans($arguement);

	/**
	 * getSql 判断参数方法，设置SQL属性，并执行对应query类型
	 * $arguement = array('fields','table','values','where','method')
	 * @param  Array $arguement MYSQL参数
	 * @return mixed （src|bool）返回到result属性
	 */
    abstract protected function getSql($arguement);
    
}

?>