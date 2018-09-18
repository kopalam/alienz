<?php

namespace app\controllers\admin;

use app\models\score\ScoreSet;
use app\services\admin\Adminajax;
use app\services\admin\AuthData;
use app\services\General;
use SebastianBergmann\CodeCoverage\Util;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\services\Utils;
use app\services\score\Score;
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

   function actionAuth()
   {
       /* 未完成
        * 权限管理
        * handle: list create edit list disable delete
        * token 验证
        * 获取当前的users表的用户，新登录用户都是普通会员，根据 后台晋升，充值而改变
        * */
       $request     =   Yii::$app->request;
       $token   =   $request->post('token');
       $handle  =   $request->post('handle');
       $data['title']   =   $request->post('title');
       $data['name']   =   $request->post('name');
       $data['id']   =   $request->post('id');
       $data['vip_about']   =   $request->post('vip_about');


       try{
           $service     =   new AuthData();
           $general     =   new General();
           switch ($handle){
               case 'create':
                   $result =  $service->createAuth($data) ;
                   break;
               case 'edit':
                   $result  =  $service->editAuth($data) ;
                   break;
               case 'disable'  :
                   $result  =  $general->disable('ActivityVipSet',$data['id'])  ;
                   break;
               case 'list':
                   $result  =  $service->authList() ;
                   break;
               default:
                   $result = ['status'=>1,'message'=>'出错了'];
                   break;


           }
       }catch(\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           echo json_encode($result);exit();
       }
       Utils::apiDisplay(['status'=>0,'message'=>$result]);
   }

   function actionScore()
   {
       /*
        * handle invite(邀请)  mark(签到) sale(消费)
        * 积分明细：
        * 好友邀请积分
        * 签到积分
        * 消费积分
        * */

       $request     =   Yii::$app->request;
       $token       =   $request->post('token');
       $handle      =   $request->post('handle');
       $uid     =   $request->post('uid');
       $page    =   $request->post('page');
//       $auth = new Authory($token);
//       $auth->loggingVerify();

       try{
           $service     =   new Score();
           switch ($handle)
           {
               case 'invite':
                    $result     =   $service->inviteList($page);
                   break;
               case 'mark':
                    $result     =   $service->scoreMark($page);
                   break;
               case 'sale':

                   break;
               default:
                   $result  =   ['status'=>1,'message'=>'出错了'];
                break;
           }
       }catch (\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           echo json_encode($result);exit();
       }
        Utils::apiDisplay($result);
   }

}
