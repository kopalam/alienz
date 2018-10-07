<?php
/**
 * Created by PhpStorm.
 * User: linyh
 * Date: 2018/9/25
 * Time: 11:34
 */
namespace app\controllers\api;

use app\models\score\ScoreLog;
use app\models\SignSet;
use app\models\SignUp;

use app\services\Utils;
use Yii;
use yii\web\Controller;

class SignController extends Controller
{
    public function actionSign()
    {
        $request     =   Yii::$app->request;
        $uid = $request->post('uid');
        $token = $request->post('token');
        $cauth_iden = $request->post('cauth_iden');
        date_default_timezone_set("Asia/Shanghai");
        $timestamp = time();
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        $yesterday = date('d') - 1;
        $yesterday_start =mktime(0, 0, 0, date('m'), $yesterday, date('Y'));
        $yesterday_end =mktime(23, 59, 59, date('m'), $yesterday, date('Y'));
        try{
            $cond = ['and',"type='sign'", 'status=0',"uid='$uid'","cauth_iden='$cauth_iden'"] ;
            $score_log = ScoreLog::find()->where($cond)->orderBy('dates desc')->one();
            $sign_set = SignSet::findOne(1);
            if($score_log){
                if ($score_log->dates>=$beginToday&&$score_log->dates<=$endToday)
                    throw new \Exception("今天已经签到");

                $da = date("w");

                $sign = new ScoreLog();
                $sign->cauth_iden = $cauth_iden;
                $sign->uid = $uid;
                $sign->type = 'mark';
                $sign->dates = $timestamp;
                $sign->status = 0;
                $sign->kinds = 0;
                //星期一从最初分数开始
                if($da == "1" ) {
                    $sign->score =$sign_set->firstscore;
                    //如果昨天有签到，今天就加分
                }elseif($score_log->dates>=$yesterday_start&&$score_log->dates<=$yesterday_end){
                    $sign->score = $score_log->score+$sign_set->addscore;
                }else{
                    //找不到昨天的就是断签，从最初开始
                    $sign->score =$sign_set->firstscore;
                }
            }else{
                $sign = new ScoreLog();
                $sign->cauth_iden = $cauth_iden;
                $sign->uid = $uid;
                $sign->type = 'sign';
                $sign->dates = $timestamp;
                $sign->status = 0;
                $sign->kinds = 0;
                $sign->score =$sign_set->firstscore;
            }


            if ($sign->save()==false)
                throw new \Exception("发生错误，请重新签到");

            $result['status'] = 0;
            $result['score'] = $sign->score;
        }catch (\Exception $e){
            $result['status']=1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }
}