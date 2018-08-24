<?php

namespace app\controllers\api;

use Yii;
use yii\filters\AccessControl;
//use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Users;
use app\models\ContactForm;
use app\services\Utils;
use app\services\mini\WXLoginHelper;
//use app\config\PluginManager;
require_once '../config/PluginManager.php';
class Halo
{
    /**
     * 这是一个Hello World简单插件的实现
     *
     * @package        DEMO
     * @subpackage    DEMO
     * @category    Plugins
     * @author        Saturn
     * @link        http://www.cnsaturn.com/
     */
    /**
     *需要注意的几个默认规则：
     *    1. 本插件类的文件名必须是action
     *    2. 插件类的名称必须是{插件名_actions}
     */
    function __construct(&$pluginManager)
    {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $service    =   new \PluginManager();
        $service->register('demo', $this, 'say_hello');
    }

    function say_hello()
    {
        echo 'halo';
    }
}
