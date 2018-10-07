<?php

namespace app\controllers\mine;

use app\models\score\ScoreSet;
use app\services\admin\Adminajax;
use app\services\admin\AuthData;
use app\services\General;
use app\services\Mine;
use SebastianBergmann\CodeCoverage\Util;
use Yii;
use Carbon\Carbon;
use yii\web\Controller;
use app\services\Utils;
use app\services\score\Score;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\rest\ActiveController;
use app\models\admin;
class MineController extends Controller
{
   /*
    * 个人中心
    *
    * */

   function actionMinehandle()
   {
       $request     =   Yii::$app->request;
       $uid  =   $request->post('uid');
       $course_id   =   $request->post('course_id');
       $handle  =   $request->post('handle');
       $w=date('w',time());




       try{
           //使用password_hash加密密码
           $service     =   new Mine();
           switch ($handle)
           {
               case 'score':
                   $result  =   $service->myScore($uid);
                   break;
               case 'scoreList':
                   $result  =   $service->myScoreList($uid);
                   break;
               case 'course':
                   $result  =   $service->courseSearch($uid);
                   break;
               case 'detail':
                   $result  =   $service->courseDetail($course_id);
                   break;
               default : $result    =   '出错了';
               break;
           }
           Utils::apiDisplay($result);
       }catch(\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
         Utils::apiDisplay($result);
       }
   }




}
