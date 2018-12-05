<?php

/**
 * @Author: lichao
 * @Date:   2018-02-06 19:34:42
 * @Last Modified by:   IDEACOM
 * @Last Modified time: 2018-03-16 19:41:33
 */

/**
* Mysql基础类
*/
class Mysql extends Db
{
	protected $conn;
	protected $sql;
	protected $config;
	public $result = Array();
	public $status;
	public static $instence = NUll;
	/**
	 * __construct 执行连接数据库、query操作
	 * @param Object $config    执行MYSQL类必须的配置参数
	 * @param Array $arguement 执行MYSQL类必须的请求参数
	 */
	final protected function __construct($arguement) {
		$this->config = Config::getIns();
		$this->connect($this->config);
		$this->getSql($arguement);
	}

	/**
	 * __destruct 关闭MySQL数据库连接并初始化参数
	 */
	public function __destruct() {
		mysqli_close($this->conn);
		unset($this->conn);
		unset($this->ressult);
		unset($this->sql);
		unset($this->status);
	}

	/**
	 * __clone 禁止克隆函数
	 * @return NUll
	 */
	final protected function __clone() {

	}

	/**
	 * connect  数据库连接方法
	 * @param  Object $config 配置文件对象参数
	 * @return conn属性
	 */
	final protected function connect() {
		$conn = mysqli_connect(
			$this->config->host,
			$this->config->username,
			$this->config->password,
			$this->config->database,
			$this->config->port
		) or die(
			'Database connect fail'
		);
		$this->conn = $conn;
		$this->sql = 'SET NAMES UTF8';
		$this->getQuery();
	}
	
	/**
	 * getInstence 获取自身实例
	 * @return Object 自身实例
	 */
	public static function get($arguement) {
		if (self::$instence == NULL) {
			self::$instence = new self($arguement); 
		}
		return self::$instence;
	}

	/**
	 * getQuery query操作方法
	 * @return mixed src|bool
	 */
	final protected function getQuery() {
		Log::write($this->sql);
		return mysqli_query($this->conn,$this->sql);
	}

	/**
	 * getSrcResult 获取资源类型结果，需要用到fetch_解析的
	 * @return src result属性
	 */
	final protected function getSrcResult() {
		$getQuery = $this->getQuery();
		while ($result = mysqli_fetch_assoc($getQuery)) {
			$this->result[] = $result;
		}

	}

	/**
	 * getBoolResult 获取布尔值类型结果，无需解析函数
	 * @return bool result属性
	 */
	final protected function getBoolResult() {
		if ($this->getQuery()) {
		 	return $this->result = true;
		 }else{
		 	return $this->result = false;
		 }
	}
	
	/**
	 * getNomallTrans($arguement) 执行多值同步对应SQL字段、值的转换
	 * @param  Array $arguement SQL语句分解
	 * @return 抛回修改后的$arguement
	 */
	final protected function getNomallTrans($arguement) {
		//判断$arguement参数内容是否有fields
		if ($arguement['fields'] == '*') {
			return $arguement;
		}
		if (array_key_exists('fields',$arguement)) {
			//转换列名字符串为数组$fieldArray，从而可以过滤参数为MySQL标准语法字段
			$fieldTemp = explode(',', $arguement['fields']);
			$fieldArgTemp = array();
			foreach ($fieldTemp as $key => $value) {
				array_push($fieldArgTemp, '`' . $value . '`');
			}
			//拆分过滤完成后的数组为字符串，并重新入栈得到$arguement[0]参数
			$arguement['fields'] = implode(',', $fieldArgTemp);
		}
		//本段内容用来过滤values参数，原理同上
		if (array_key_exists('values',$arguement)) {
			$valueTemp = explode(',', $arguement['values']);
			$valueArgTemp = array();
			foreach ($valueTemp as $key => $value) {
				array_push($valueArgTemp, "'" . $value . "'");
			}
			$arguement['values'] = implode(',', $valueArgTemp);
		}
		return $arguement;
	}

	/**
	 * getUpdateTrans($arguement) 执行多值单独对应SQL字段、值的转换（用于SET转换）
	 * @param  Array $arguement SQL语句分解
	 * @return 抛回修改后的$arguement
	 */
	final protected function getUpdateTrans($arguement) {
		//同属过滤fields和values
		$updateSql = array();
		$fieldTemp = explode(',', $arguement['fields']);
		$valueTemp = explode(',', $arguement['values']);
		//拼接成UPDATE语句可用语法字符串
		for ($i = 0; $i < count($fieldTemp); $i++) { 
			$temp = '`' . $fieldTemp[$i] . '`' . '=' . "'" . $valueTemp[$i] . "'";
			array_push($updateSql, $temp);
		}
		//重新入栈arguement并返回
		$arguement['update'] = implode(',', $updateSql);
		//print_r($arguement);
		//Log::write($arguement);
		return $arguement;
	}

	/**
	 * getSql 判断参数方法，设置SQL属性，并执行对应query类型
	 * $arguement = array('fields','table','values','where','method')
	 * @param  Array $arguement MYSQL参数
	 * @return mixed （src|bool）返回到result属性
	 */
	final protected function getSql($arguement) {
		//$arguement = array('fields','table','values','where','method');
		switch ($arguement['method']) {
			//无条件select
			case 'getAll':
				$arguement = $this->getNomallTrans($arguement);
				$this->sql = "SELECT {$arguement['fields']} FROM {$arguement['table']}";
				$this->getSrcResult();
				break;

			//有条件select
			case 'getRow':
				$arguement = $this->getNomallTrans($arguement);
				$this->sql = "SELECT {$arguement['fields']} FROM {$arguement['table']} WHERE {$arguement['where']}";
				$this->getSrcResult();
				break;
			//有条件select bool
			case 'getRowBool':
				$arguement = $this->getNomallTrans($arguement);
				$this->sql = "SELECT {$arguement['fields']} FROM {$arguement['table']} WHERE {$arguement['where']}";
				$this->getBoolResult();
				break;
			//insert插入
			case 'getInsert':
				$arguement = $this->getNomallTrans($arguement);
				$this->getNomallTrans($arguement);
				$this->sql = "INSERT INTO {$arguement['table']}({$arguement['fields']}) VALUES ({$arguement['values']})";
				$this->getBoolResult();
				break;
			//update更新
			case 'getUpdate':
				$arguement = $this->getUpdateTrans($arguement);
				print_r($arguement);
				$this->sql = "UPDATE {$arguement['table']} SET {$arguement['update']} WHERE {$arguement['where']}";
				$this->getBoolResult();
				break;
			//delete删除
			case 'getDelete':
				$this->sql = "DELETE FROM {$arguement['table']} WHERE {$arguement['where']}";
				$this->getBoolResult();
				break;

			//自定义类型（常用于多表连接或子查询等，预计升级为视图查询）
			case 'getOther':
				$this->sql = $arguement['sql'];
				stripos($this->sql,'SELECT') == 0 ? $this->getSrcResult() : $this->getBoolResult();
				break;
			
			default:
				echo 'Mysql class expect one arguement for operating database at this [getAll][getRow][getOne][getInsert][getUpdate][getDelete]';
				break;
		}
	}
}

?>