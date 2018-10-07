<?php

namespace app\controllers\api;

use app\models\AuthRules;
use app\models\UserAuth;
use app\services\Auth;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\score\UserScore;
use app\models\User;
use app\models\ContactForm;
use app\services\Utils;
use app\services\mini\WXLoginHelper;


class LoginController extends Controller
{
   /*
    * 通用登录控制器
    * */
   function actionMinilogin()
   {
       /*
        * 小程序登录
        * */
       $request     =   Yii::$app->request;
       $code = $request->post('code');
       $rawData = $request->post('rawData');
       $signature = $request->post('signature');
       $encryptedData = $request->post('encryptedData');
       $iv = $request->post('iv');
       $cauthIden 	=	$request->post('cauth_iden');//根据appId，传递到微信登录中，查找对应的secrect

       try{
            $service    =   new WXLoginHelper();
           $getUserData   =   $service->checkLogin($code, $rawData, $signature, $encryptedData,$iv,$cauthIden);
           if(isset($result->code))
               Utils::apiDisplay($getUserData);

           $openId  =   $getUserData['openId'];
           $unionId =   isset($getUserData['unionId']) ? $getUserData['unionId']:0;
           $paramers    =   $unionId !==0 ? ['unionId'=>$unionId]:['openId'=>$openId];
           $hasUser     =   User::findOne($paramers);//查找表中是否有该用户
           $info    =   isset($hasUser->openId)?1:0; //存在openid，则代表有数据,1为有数据，0为空
           switch ($info){
               case 0:
                   $user    =   new User;
                   $user->openId = $getUserData['openId'];
                   $user->unionId = $getUserData['openId;unionId'];
                   $user->nickName = preg_replace('/[\x{10000}-\x{10FFFF}]/u','',$getUserData['nickName']);
                   $user->gender = $getUserData['gender'];
                   $user->language = $getUserData['language'];
                   $user->city = $getUserData['city'];
                   $user->province = $getUserData['province'];
                   $user->country = $getUserData['country'];
                   $user->avatarUrl = $getUserData['avatarUrl'];
                   $user->reg_time = time();
                   if($user->insert()==false)
                       throw new \Exception('插入用户资料失败',1001);
                   //将用户uid，cauth_iden,total_score写入user_score表
                    $score  =   new UserScore();
                    $score->cauth_iden  =   $cauthIden;
                    $score->uid     =   $user->id;
                    $score->total_score     =   0;
                   if($score->insert()==false)
                       throw new \Exception('更新积分记录失败',1001);

                   $result = ['user_id'=>$user->id,'openId'=>$user->openId,'unionId'=>$user->unionId];
                   break;
               case 1:
                   $result = ['user_id'=>$hasUser->id,'unionId'=>$hasUser->unionId,'openId'=>$hasUser->openId,'telephone'=>$hasUser->telephone];
                   break;
               default:
                   Utils::jsonError(1187,'登录出错了-_-');
                   break;
           }
           //将token写入redis
           $result['token']     =   $getUserData['session3rd'];
           Yii::$app->redis->set($result['token'],json_encode($getUserData['sessionKey'].'='.$unionId));


           Utils::apiDisplay($result);
       }catch(\Exception $e){
           $result['message']   =   $e->getMessage();
           $result['code']  =   $e->getCode();
           Utils::apiDisplay($result);
       }

   }


}
