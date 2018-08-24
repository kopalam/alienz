<?php

namespace app\controllers\admin;

use app\services\admin\Adminajax;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\services\Utils;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\rest\ActiveController;
use app\models\admin;
class AdminController extends Controller
{
   /*
    * 进入之前需要检测是否已经登录
    *
    * */

   function actionLoginhandle()
   {
       $request     =   Yii::$app->request;
//       $name    =   $request->post('name');
       $passwd  =   $request->post('passwd');
       $telephone   =   $request->post('telephone');
       $uid  =   $request->post('uid');
       $handle  =   $request->post('handle');

       try{
           //使用password_hash加密密码
           $service     =   new Adminajax();
           switch ($handle)
           {
               case 'create':
                   $passwd  =   password_hash($passwd,PASSWORD_DEFAULT);
                   $result  =   $service->Create($telephone,$passwd);
                   break;
               case 'login':
                   $result  =   $service->Login($telephone,$passwd);
                   break;
               case 'edit':
                   $result  =   $service->Edit($uid,$telephone,$passwd);
                   break;
               default : $result    =   '出错了';
               break;
           }

       }catch(\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           echo json_encode($result);exit();
       }
        Utils::apiDisplay($result);

   }

}
