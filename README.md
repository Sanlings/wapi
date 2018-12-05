# Wapi

#### 项目介绍
Wapi 是一个一个超轻量级别的MVC框架，使用PHP进行开发，该项目可用于轻量级的API编写，目前用于Wblog的开箱即用交互API。


#### 内容说明
虽然有很多轮子可用，但是对于一个轻量级的博客来说，使用`ThinkPHP`等未免显得太过于繁琐。
`typecho`仅有Web端管理，但没有小程序的API，觉得还是写一个比较方便。

目前适配了[Typecho](http://typecho.org/)的数据库字段，配合Typecho进行微信端的数据管理。


#### 安装教程

1. Clone项目到本地 
```
git clone https://github.com/Sanlings/wapi.git
```
2. 打开项目中的`runtime/config/config.inc.php`文件
3. 编辑如下项目，配置数据库信息:
```
/**
 * $_CFG 配置项目二维数组
 * @var array
 */
$_CFG = array();
// 数据库地址
$_CFG['host'] = '';

// 数据库用户名(Typecho建立的数据库用户名)
$_CFG['username'] = '';

// 密码(Typecho建立的数据库对应密码)
$_CFG['password'] = '';

// 数据库(Typecho建立的数据库)
$_CFG['database'] = '';

// 端口
$_CFG['port'] = '3306';
```
4. 上传至服务器的Typecho博客根目录下即可，示例目录如下:
```
Typecho blog dir
    admin
    api  --本项目
    usr
    var
    config.inc.php
    index.php
    LICENSE.txt
    README.md
```

#### 使用说明

1. 需要结合Typecho来进行数据管理
2. 已经实现了[Wblog](https://github.com/Sanlings/wblog)所需API，只需做好相关部署即可
3. 生产环境下服务器务必是HTTPS的
4. 要将文章发布至小程序，只需在Typecho后端-管理-分类-新增：'微信小程序'。
发布文章时，勾选上这个分类即可。
5. 可以进行小程序热更新，比如tab的配置,打开`runtime/module/index_module/controller_class/indexControl.class.php`
对如下字段进行编辑即可配置,但也要在typecho后台‘微信小程序’分类中新建新增的子类
    ```
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
    ``` 
6. 非wblog使用：在`runtime/module`下新增需要的模块接口即可

#### 项目结构
```
api |
    |--data                                         # 数据目录	
    |----log	                                    # 日志目录
    |----markdown	                                # 静态markdown(未使用)
    |--public	                                    # 入口
    |----index.php                                  # 入口文件
    |--runtime                                      # 运行时
    |----class                                      # 核心类
    |--------config.class.php                       # 配置类
    |--------controller.class.php                   # 控制器类
    |--------db.class.php                           # db类
    |--------log.class.php                          # 日志类
    |--------mysql.class.php                        # 单例MySQL类
    |--------mysqld.class.php                       # 正常MySQL类
    |--------view.class.php                         # API视图渲染类
    |----config                                     # 配置
    |--------config.inc.php                         # 配置文件
    |----module                                     # 模块(App)
    |--------index_module                           # 默认App(可删除)
    |------------controller_class                   # App控制器
    |----------------indexController.class.php
    |------------mode                               # App数据模型
    |----------------indexMode.class.php
    |------------view                               # App视图(未使用)
    |----------------index.html
    |------------index_port.php                     # App接口定义
    |----index.html                                 # 跳转文件
    |----initialize.php                             # 初始化文件
    |--LICENSE
    |--README.md
```

#### 常用类说明
1. Mysql类(global)
    getRow    方法	有条件查询
    getAll    方法  无条件查询
    getInsert 方法  插入
    getUpdate 方法  更新
    getDelete 方法  删除
    getOthers 方法  自定义语句

    example:
    ```
    $article_sql_param = array(
        'method' => 'getRow',
        'table'  => 'blog_contents',
        'fields' => 'fields1,fields2...',
        'where'  => "`blog_contents`.`cid` = '{$cid}'"
    );
    $article_sql_result = new mysqld($article_sql_param);
    return $article_sql_result;
    ```
2. Log类(global)
    write  方法  写日志
    example:
    ```
    Log::write('something you want to write');
    ```
    日志内会自动朔源，生成如下：
    ```
    [2018-05-13--18:16:52][Class:train][Method:cal][Line:70][Content:正在计算27区23组串1月31日,请稍后]
    [2018-05-13--18:16:52][Class:Mysqld][Method:getQuery][Line:97][Content:SELECT `year`,`month`,`date`,`range`,`group`,`sum_kwh`,`sum_kwh`,`capacity_kw`,`capacity_mw` FROM inverter 
            WHERE `year`='2016' AND `month`='1' AND `date`='31' AND `range`='27' AND `group`='23']
    ```
3. View类(global)
    json_display  方法  渲染结果到前端
    example:
    ```
    View::json_display('something you want to render');
    ```

4. Config类 (global)
    getIns 方法  获取实例
    example:
    ```
    $this->config = Config::getIns();
    $this->connect($this->config);
    ```

#### 后续优化进度
- 支持前端文章搜索
- 支持匿名评论功能
- 支持添加到具体分类