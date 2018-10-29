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
<<<<<<< HEAD
       $name   =   $request->post('name');
=======
       $telephone   =   $request->post('telephone');
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
       $uid  =   $request->post('uid');
       $handle  =   $request->post('handle');

       try{
           //使用password_hash加密密码
           $service     =   new Adminajax();
           switch ($handle)
           {
               case 'create':
                   $passwd  =   password_hash($passwd,PASSWORD_DEFAULT);
<<<<<<< HEAD
                   $result  =   $service->Create($name,$passwd);
                   break;
               case 'login':
                   $result  =   $service->Login($name,$passwd);
                   break;
               case 'edit':
                   $result  =   $service->Edit($uid,$name,$passwd);
                   break;
               default : $result    =   ['status'=>1,'message'=>$handle];
=======
                   $result  =   $service->Create($telephone,$passwd);
                   break;
               case 'login':
                   $result  =   $service->Login($telephone,$passwd);
                   break;
               case 'edit':
                   $result  =   $service->Edit($uid,$telephone,$passwd);
                   break;
               default : $result    =   '出错了';
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
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
<<<<<<< HEAD
       $result =[];
=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
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
<<<<<<< HEAD
//                   $result  =   ['status'=>1,'message'=>'出错了'];
                   throw new \Exception('出错了',1);
=======
                   $result  =   ['status'=>1,'message'=>'出错了'];
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
                break;
           }
       }catch (\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           echo json_encode($result);exit();
       }
<<<<<<< HEAD
        Utils::apiDisplay(['status'=>0,'data'=>$result]);
   }

//   function actionArticlehandle()
//   {
//       /*
//        * 文章控制器
//        * handle    list kid edit create disable
//        * articleId 文章id
//        * kid 分类id
//        * page 分页
//        * */
//       $request     =   Yii::$app->request;
//       $handle  =   $request->post('handle');
//       $uid     =   $request->post('uid');
//       $page    =   $request->post('page');
//       $articleId   =   $request->post('article_id');
//       $kid     =   $request->post('kid');
//
//       try{
//           $service     =   new General();
//           switch ($handle)
//           {
//
//               case 'kid':
//                   $result  =   $service->articleList($kid,$page);
//                   break;
//
//               case 'edit':
//                   $result  =   $service->articleEdit($page);
//                   break;
//
//               case 'create':
//                   $result  =   $service->articleCreate($page);
//                   break;
//
//               case 'disable':
//                   $result  =   $service->articleDisable($page);
//                   break;
//           }
//       }catch (\Exception $e){
//           $result['message']   =   $e->getMessage();
//           $result['code']  =   $e->getCode();
//           echo json_encode($result);exit();
//       }
//
//   }

   function actionTeacher()
   {
       /*
        * 选择老师
        * */
       $teacher     =   admin\AdminUser::find()->where(['status'=>0])->asArray()->all();
        $result = array();
       foreach ($teacher as $key =>$value)
       {
            $result[$key]['name']     =   $value['name'];
            $result[$key]['avatar']     =   $value['avatar'];
           $result[$key]['content']     =   $value['content'];
           $result[$key]['cauth_iden']     =   $value['cauth_iden'];
           $result[$key]['teacher_id']     =   $value['id'];
       }
       Utils::apiDisplay(['status'=>0,'data'=>$result]);
=======
        Utils::apiDisplay($result);
   }

   function actionArticlehandle()
   {
       /*
        * 文章控制器
        * handle    list kid edit create disable
        * articleId 文章id
        * kid 分类id
        * page 分页
        * */
       $request     =   Yii::$app->request;
       $handle  =   $request->post('handle');
       $uid     =   $request->post('uid');
       $page    =   $request->post('page');
       $articleId   =   $request->post('article_id');
       $kid     =   $request->post('kid');

       try{
           $service     =   new General();
           switch ($handle)
           {

               case 'kid':
                   $result  =   $service->articleList($kid,$page);
                   break;

               case 'edit':
                   $result  =   $service->articleEdit($page);
                   break;

               case 'create':
                   $result  =   $service->articleCreate($page);
                   break;

               case 'disable':
                   $result  =   $service->articleDisable($page);
                   break;
           }
       }catch (\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           echo json_encode($result);exit();
       }

   }

   function actionGetkid()
   {
       /*
        * 获取分类
        * */
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
   }

}
