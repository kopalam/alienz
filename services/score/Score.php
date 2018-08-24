<?php

	namespace app\services\score;
	use app\models\admin\AdminUser;
    use app\models\AuthRules;
    use app\models\score\ScoreLogs;//积分明细表
    use app\models\score\ScoreSet;//积分设置表
    use app\models\Users;
	use app\models\Cauth;

	// use Phalcon\Crypt; //加密类
	Class Score{

      function CreateScoreSet($inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$cauth_iden)
      {
            /*
             * 创建积分规则
             *写入
             * */
            $model  =   new ScoreSet();
            $model->uid     =   $uid;
            $model->cauth_iden  =   $cauth_iden;
            $model->in_score    =   $inScore;
            $model->in_price    =   $inPrice;
            $model->out_score   =   $outScore;
            $model->sign_in_score   =   $signInScore;
            $model->invite_score    =   $inviteScore;
            $model->out_price   =   $outPrice;
            $model->dates   =   time();
            if($model->insert() == false)
                throw new \Exception('创建积分规则失败',20010);

            return true;
      }

      function EditScoreSet($id,$inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$cauth_iden)
      {
        /*
         * 编辑积分
         * */

            $result   =   ScoreSet::findOne($id);
            if(!$result)
                throw new \Exception('不存在该设置',20011);
          $result->uid     =   $uid;
          $result->cauth_iden  =   $cauth_iden;
          $result->in_score    =   $inScore;
          $result->in_price    =   $inPrice;
          $result->out_score   =   $outScore;
          $result->out_price   =   $outPrice;
          $result->sign_in_score   =   $signInScore;
          $result->invite_score    =   $inviteScore;
          $result->dates   =   time();
          if($result->save() == false)
              throw new \Exception('编辑积分设置失败',20012);

          return true;
      }

    }