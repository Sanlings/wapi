<?php

/**
 * IndexController
 * @param $[router] [路由数组]
 * @Author: IDEACOM
 * @Date:   2018-02-23 00:17:52
 * @Last Modified by:   lichao
 * @Last Modified time: 2018-06-26 16:51:36
 */
class index implements index_port
{
	/**
	 * [$trans_result Tab列表]
	 * @var array
	 */
	private $trans_result = array('index' => [],'reading' => [],'art' => [],'my' => []);

	/**
	 * [$trans_result Tab列表]
	 * @var array
	 */
	private $ch_tab = array('首页','阅读','作品','我');

	/**
	 * [$tab 配置的需要查询的Tab列表]
	 * @var string
	 */
	private $tab = "'my','reading','art','index'";

	/**
	 * [$mode 数据模型]
	 * @var [Class]
	 */
	private $mode;

	public function __construct($router,$mode)
	{
		$this->mode = $mode;
	}
	/**
	 * [index index接口方法]
	 * @return [JSON] [直接返回JSON对象格式]
	 */
	public function get_index()
	{
		if (!empty($_POST['cid'])) {
			$is_get_more = $_POST['getmore'];
			$range_start = $_POST['range_start'];
			$cid 	 	 = $_POST['cid'];
			$num 		 = $_POST['num'];
			$getmore  	 = false;
			if ($_POST['getmore'] && !empty($_POST['tab'])) {
				$this->tab = $_POST['tab'];
				$getmore = true;
			}
			$this->get_option($cid,$range_start,$num,$getmore);
		}else{
			$range_start = 0;
			$cid 	 = -1;
			$num 		 = 0;
			$getmore  	 = true;
			$this->get_option($cid,$range_start,$num,$getmore);
		}
	}

	/**
	 * @param String cid 文章id:小于0 = 获取列表;其余获取对应id详情;
	 * @param String range_start 加载范围开始:小于0 = 不开启;
	 */
	private function get_option($cid,&$range_start,&$num,$getmore)
	{
		//初次获取列表
		if ($cid < 0 && !$getmore) {
			$article_post = $this->mode->query_index($this->tab,$range_start,$num);
			$article_page = $this->mode->query_page($range_start,$num);
			$article_post = $article_post->result;
			$article_page = $article_page->result;
			$article_post = $this->data_trans($article_post);
			$article_page = $this->data_trans($article_page);
			$this->data_format($article_post);
			$this->data_format($article_page);
			$article['content'] = $this->trans_result;
			$article['tab'] 	= $this->ch_tab;
		}
		//获取列表（分页）
		if ($cid < 0 && $getmore) {
			$article_post = $this->mode->query_index($this->tab,$range_start,$num);
			$article_post = $article_post->result;
			$article = $this->data_trans($article_post);
			if (count($article) == 0) {
				$article['status'] = 'null';
			}
		}
		//获取详情
		if ($cid >= 0) {
			$article = $this->mode->get_detail($cid)->result;
		}
		//输出JSON至客户端
		view::json_display($article);
	}
	
	/**
	 * [data_trans 数据格式转换]
	 * @param  [Array] $article [查询结果]
	 * @return [Array]          [处理后的结果]
	 */
	private function data_trans($article)
    {
		$result = [];
        foreach ($article as $key => $value) {
			//摘要生成处理
        	$start  = stripos($value['text'],'<!--markdown-->') + strlen('<!--markdown-->');
        	$length = stripos($value['text'],'<!--more-->');
        	$text 	=  substr(substr($value['text'], 0,$length), $start);
        	//格式化时间
            $value['modified'] = date('Y-m-d',$value['modified']);
            //封面生成处理
            $value['cover'] 	 = $value['str_value'];
            //摘要生成处理
            $value['intro'] 	 = $text; 
            unset($value['str_value']);
			unset($value['text']);
			// print_r($value)."\n";
			array_push($result,$value);
		}
		//print_r($result);
        return $result;
    }
    
    /**
	 * [data_format 结构转化列表]
	 * @param  [Int] $article [转换的内容]
	 * @return [Array]        [结果]
	 */
	private function data_format($article)
	{
		foreach ($article as $key => $value) {
			if ($value['type'] == 'post' && $value['slug'] == 'index') {
				array_push($this->trans_result['index'], $value);	
			}
			if ($value['type'] == 'post' && $value['slug'] == 'reading') {
				array_push($this->trans_result['reading'], $value);	
			}
			if ($value['type'] == 'page') {
				array_push($this->trans_result['art'], $value);
			}
			if ($value['type'] == 'post' && $value['slug'] == 'my') {
				array_push($this->trans_result['my'], $value);	
			}
		}

	}

}


?>
