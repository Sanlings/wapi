<?php

/**
 * @Author: lichao
 * @Date:   2018-06-14 16:45:03
 * @Last Modified by:   lichao
 * @Last Modified time: 2018-06-26 16:50:26
 */
class indexMode
{
	/**
     * [query_index 首页列表查询]
     * 
     * 用于第一次加载时，$tab设置为所有栏目，则可以加载所有栏
     * 目的内容，分页加载更多时，$tab设置为需要显示更多的栏目
     * 换句话说，$tab决定了加载的内容类型
     * 
     * @param  [String] $tab 		 [Tab列表,逗号分隔]
     * @param  [Int] 	$range_start [分页起点]
     * @param  [Int]	$length 	 [分页长度]
     * @return [Array]        		 [查询结果]
     */
    public function query_index($tab,&$range_start,&$length)
    {
    	if ($range_start < 0) {
			$index_sql_param = array(
    		'method' => 'getOther',
    		'sql' => "SELECT
						`blog_contents`.`cid`,
						`blog_contents`.`title`,
						`blog_contents`.`order`,
						`blog_contents`.`type`,
						`blog_contents`.`text`,
						`blog_contents`.`modified`,
						`blog_users`.`screenName`,
						`blog_fields`.`str_value`,
						`blog_metas`.`name`,
						`blog_metas`.`slug`
					FROM
						`blog_contents`
						LEFT JOIN `blog_fields` ON `blog_contents`.`cid` = `blog_fields`.`cid`
						JOIN `blog_users` ON `blog_contents`.`authorId` = `blog_users`.`uid` 
						JOIN `blog_relationships` ON `blog_contents`.`cid` = `blog_relationships`.`cid`
						JOIN `blog_metas` ON `blog_relationships`.`mid` = `blog_metas`.`mid`
					WHERE
						`blog_contents`.`status` = 'publish'
						AND `blog_contents`.`type` = 'post'
						AND `blog_metas`.`slug` IN ({$tab})
					ORDER BY modified DESC"
    		);	
		}else{
			$index_sql_param = array(
    		'method' => 'getOther',
    		'sql' => "SELECT
						`blog_contents`.`cid`,
						`blog_contents`.`title`,
						`blog_contents`.`order`,
						`blog_contents`.`type`,
						`blog_contents`.`text`,
						`blog_contents`.`modified`,
						`blog_users`.`screenName`,
						`blog_fields`.`str_value`,
						`blog_metas`.`name`,
						`blog_metas`.`slug`
					FROM
						`blog_contents`
						LEFT JOIN `blog_fields` ON `blog_contents`.`cid` = `blog_fields`.`cid`
						JOIN `blog_users` ON `blog_contents`.`authorId` = `blog_users`.`uid` 
						JOIN `blog_relationships` ON `blog_contents`.`cid` = `blog_relationships`.`cid`
						JOIN `blog_metas` ON `blog_relationships`.`mid` = `blog_metas`.`mid`
					WHERE
						`blog_contents`.`status` = 'publish'
						AND `blog_contents`.`type` = 'post'
						AND `blog_metas`.`slug` IN ({$tab})
					ORDER BY modified DESC
					LIMIT {$range_start},{$length}"
    		);
		}
		$index_sql_result = new mysqld($index_sql_param);
		return $index_sql_result;
    }

    /**
     * [query_index 首页列表作品查询]
     * 暂时精简掉
     * @return [Array]   [查询结果]
     */
    public function query_page()
    {
    	$page_sql_param = array(
    		'method' => 'getOther',
    		'sql' => "SELECT
						`blog_contents`.`cid`,
						`blog_contents`.`title`,
						`blog_contents`.`order`,
						`blog_contents`.`type`,
						`blog_contents`.`text`,
						`blog_contents`.`modified`,
						`blog_users`.`screenName`,
						`blog_fields`.`str_value`
					FROM
						`blog_contents`
						LEFT JOIN `blog_fields` ON `blog_contents`.`cid` = `blog_fields`.`cid`
						JOIN `blog_users` ON `blog_contents`.`authorId` = `blog_users`.`uid` 
					WHERE
						`blog_contents`.`status` = 'publish'
						AND `blog_contents`.`type` = 'page'
					ORDER BY modified DESC"
    	);
    	$page_sql_result = new mysqld($page_sql_param);
    	return $page_sql_result;
    }

    /**
	 * [get_detail 获取详情]
	 * @return [Array] [查询结果]
	 */
	public function get_detail($cid)
	{
		$article_sql_param = array(
			'method' => 'getRow',
			'table'  => 'blog_contents',
			'fields' => 'text',
			'where'  => "`blog_contents`.`cid` = '{$cid}'"
		);
		$article_sql_result = new mysqld($article_sql_param);
		return $article_sql_result;
	}


}

?>