<?php

namespace app\controllers\addons\score;

use app\services\Utils;
use SebastianBergmann\CodeCoverage\Util;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\services\Authory;
use app\services\score\Score;
use app\services\General;

class ScoreController extends Controller
{
    function actionScoreset()
    {
        /*
         * 积分设置
         * */
        $request    =   Yii::$app->request;
        $token  =   $request->post('token');
        $handle     =   $request->post('handle');
        $uid    =   $request->post('uid');
        $auth = new Authory($token);
            $auth->loggingVerify();
        $cauth_iden     =   $request->post('cauth_iden');
        $inPrice  =   $request->post('inPrice');
        $inScore  =   $request->post('inScore');
        $outPrice  =   $request->post('outPrice');
        $outScore  =   $request->post('outScore');
        $inviteScore    =   $request->post('inviteScore');
        $signInScore    =   $request->post('signInScore');
        $inviteImage    =   $request->post('inviteImage');
        $id     =   $request->post('id');

        try{
            $inPrice    =   $inPrice*100;
            $inScore    =   $inScore*100;
            $outPrice   =   $outPrice*100;
            $outScore   =   $outScore*100;
            //积分设置
            $service    =   new Score();
            $general    =   new General();
            $result     =   '';
            switch ($handle)
            {
                case 'create':
                    $result     =   $service->CreateScoreSet($inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$inviteImage,$cauth_iden);
                    break;
                case 'edit':
                    $result     =   $service->EditScoreSet($id,$inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$inviteImage,$cauth_iden);
                    break;

                case 'disable':
                    $result     =   $general->disable('score\ScoreSet',$id);
                    break;

                case 'delete':
                    $result     =   $general->delete('score\ScoreSet',$id);
                    break;

                default:
                    $result     =   ['message'=>'出错了','status'=>41007];
                    break;
            }
            Utils::apiDisplay($result);
        }catch(\Exception $e)
        {
            $result     =   ['message'=>$e->getMessage(),'status'=>$e->getCode()];
            Utils::apiDisplay($result);
        }
    }

    function actionInvite()
    {
        /*
         * 邀请好友
         * uid 邀请人
         * invite_uid 被邀请人
         * handle create 创建一个邀请
         * handle getInvite 有uid 与 inviteUid，代表邀请了一个人
         *
         * */
        $request    =   Yii::$app->request;
        $data['uid']    =   $request->post('uid');
        $data['inviteUid']     =   $request->post('inviteUid');
        $handle     =   $request->post('handle');
        $data['cauth_iden']     =   $request->post('cauth_iden');

        try{
            $service    =   new Score();
            switch($handle)
            {
                case 'create':
                        $result     =   $service->createInvite($data);
                    break;

                case 'getInvite':
                        $result     =   $service->getInvite($data);
                    break;

                default:

                    $result     =   ['status'=>20013,'message'=>'邀请出错了'];

                    break;
            }
            Utils::apiDisplay($result);
        }catch(\Exception $e)
        {
            $result     =   ['message'=>$e->getMessage(),'status'=>$e->getCode()];
            Utils::apiDisplay($result);
        }


    }
}
