<?php

namespace app\controllers\api;

use SebastianBergmann\CodeCoverage\Util;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Users;
use app\models\ContactForm;
use app\services\Utils;
use app\services\Auth;
use app\models\UserAuth;
use app\models\Sitemode;
use app\services\admin\Adminajax;


class ModeController extends Controller
{
   /*
    * 模式选择
    * 1.新闻管理系统模式
    * 2.小程序模式
    * 3.H5营销模式
    * 4.全部都是
    * */

   function actionGetmode()
   {
       //获取用户选择模式
       $request     =   Yii::$app->request;
       $mode    =   $request->post('mode');
       $siteName    =   $request->post('siteName');
       $cauthIden   =   $request->post('cauthIden');
       $telephone    =   $request->post('telephone');
       $passwd  =   $request->post('passwd');
       if(!$cauthIden)
       {
           $cauthIden   =   uniqid().rand(111,999); //随机生成一个唯一的标识符
       }

       try{
           if(empty($mode) || !intval($mode))
               throw new \Exception('请选择你需要的框架模式',10121);
           $findData    =   Sitemode::findOne(['cauth_iden'=>$cauthIden]);
           if($findData)
               throw new \Exception('不能重复注册噢',10004);

           $service    =   new Adminajax();
           $passwd     =   password_hash($passwd,PASSWORD_DEFAULT);
           $result     =   $service->Create($telephone,$passwd);
//           print_r($result);exit();
           if($result['status']==true){
               $model   =   new Sitemode();
               $model->mode     =   $mode;
               $model->site_name   =    $siteName;
               $model->cauth_iden   =   $cauthIden;
               $model->uid  =   $result['uid'];
               if($model->insert()==false)
                   throw new \Exception('创建框架模式失败',10011);
           }else{
               throw new \Exception('创建框架模式失败',10011);
           }
            Utils::apiDisplay(['status'=>true,'message'=>'选择框架模式成功啦']);

       }catch(\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']      =   $e->getCode();
           Utils::apiDisplay($result);
       }
//       return true; //返回结果为1，代表已经成功。成功后应跳转到登录页面

   }

    function actionSetredis()
    {
        $uid    =   1;
        $authGroup = [];
        $auth = new Auth();
        $rules = UserAuth::find()->asArray();
//        print_r($rules);exit();
        foreach ($rules as $rule){
            if($auth->check($rule['name'],$uid))
                $authGroup[] = $rule->name ;
        }

        print_r($authGroup);
    }


}
