<?php

//	namespace app\config;
	use app\services\Utils;
    use Yii;
	
	Class PluginManager{
	    protected $config = [
	        'Halo'=>'plugins/halo' // 键名为插件名称，键值为其配置文件路径
        ];

	  private $listeners    =   [];

	  /*
	   * 构造函数
	   * */
	  public function __construct()
      {
          //这里的plugin数组包含我们获取已经由用户激活的插件信息
          /*
           * $plugin    =   ['name'=>'插件名称','directory'=>'安装目录'];
           *
           * */
          $basePath     =   Yii::getAlias('@plugins');
          $plugins    =   $this->config; //获取已插件数组
          if($plugins)
          {
              foreach($plugins as $name=>$path)
              {
                  //假设每个插件文件夹包含一个叫start.php文件
                  if(@file_exists($basePath.$path.'/start.php')){
                      require_once ($basePath.'plugins/'.$path.'/'.$name.'.php');//引入文件
                      $class    =   $name;
                      if(class_exists($class))
                      {
                          //初始化所有插件
                          new $class($this);
                      }
                  }
              }
          }
          #此处做日志记录

      }

      function register($hook,&$reference,$method)
      {
          /*
           * 注册需要监听的插件方法（钩子）
           * */
          //获取插件实现方式
          $key  =   get_class($reference).'->'.$method;
          //将插件的引用连同方法push进监听数组中
          $this->listeners[$hook][$key]=[$reference,$method];
      }

      function trigger($hook,$data='')
      {
          /*
           * 触发一个钩子
           * $hook 钩子名称
           * $data  钩子的入参
           * */
          $result   =   '';
          //查看要实现的钩子，是否在监听的数组之中
          if(isset($this->listeners[$hook])&&is_array($this->listeners[$hook]))
          {
              //循环调用开始
              foreach($this->listeners[$hook] as $listener)
              {
                  //取出插件对象引用和方法
                  $class    =&$listener[0];
                  $method   =   $listener[1];
                  if(method_exists($class,$method)){
                      //动态调用插件的方法
                      $result .=$class->$method($data);
                  }
              }
          }
          return $result;
      }
    }