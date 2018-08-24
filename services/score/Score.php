<?php

	namespace app\services\score;
	use app\models\admin\AdminUser;
    use app\models\AuthRules;
    use app\models\score\ScoreLogs;//积分明细表
    use app\models\score\ScoreSet;//积分设置表
    use app\models\Users;
	use app\models\Cauth;
    use Yii;

	// use Phalcon\Crypt; //加密类
	Class Score{

	    function __construct()
        {
            $this->model    =   trim('app\models\ ');
        }

        function CreateScoreSet($inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$inviteImage,$cauth_iden)
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
            $model->invite_image    =   $inviteImage;
            $model->dates   =   time();
            if($model->insert() == false)
                throw new \Exception('创建积分规则失败',20010);

            return true;
      }

      function EditScoreSet($id,$inScore,$inPrice,$outScore,$outPrice,$uid,$signInScore,$inviteScore,$inviteImage,$cauth_iden)
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
          $result->invite_image    =   $inviteImage;
          $result->dates   =   time();
          if($result->save() == false)
              throw new \Exception('编辑积分设置失败',20012);

          return true;
      }

      function createInvite($data)
      {
          /*
           * 获取创建邀请人信息
           * 拉取邀请图片
           * */
          $res  =   ScoreSet::findOne(['cauth_iden'=>$data['cauth_iden']]);
          if(!$res)
              throw new \Exception('获取积分设置失败',20014);

          $result   =   ['inviteScore'  => $res->invite_score,'inviteImage'=> $res->invite_image];//邀请所设置积分与图片
          return $result;
      }

      function getInvite($data)
      {
          /*
           * 获取邀请人与被邀请人，并写入对应invite_logs表
           * uid 邀请人
           * inviteUid 被邀请人
           * */


          $inviteModel  =   $this->model.'score\InviteFriendLogs';
          $inviteData   =   $inviteModel::findOne(['uid'=>$data['uid'],'friend_uid'=>$data['inviteUid'],'cauth_iden'=>$data['cauth_iden']]);
          if($inviteData)
              throw new \Exception('已经邀请过了哦',20015);
          $inviteScore  =   ScoreSet::findOne(['cauth_iden'=>$data['cauth_iden']]);
          if(!$inviteScore)
              throw new \Exception('获取积分设置失败',20014);

          $transaction   =   Yii::$app->db->beginTransaction();
          $userScoreData     =   $this->model.'score\UserScore';
          $userScore    =   $userScoreData::findOne(['cauth_iden'=>$data['cauth_iden'],'uid'=>$data['uid']]);
          $connection   =   Yii::$app->db;

              //写入用户积分表userScore

          $params = ['uid'=>$data['uid'],'cauth_iden'=>$data['cauth_iden'],'total_score'=>$inviteScore->invite_score];
//          $param = ['uid'=>$data['inviteUid'],'cauth_iden'=>$data['cauth_iden'],'total_score'=>$inviteScore->invite_score];
            $connection->createCommand()->upsert('user_score',$params,['total_score'=>new \yii\db\Expression('total_score + '.$inviteScore->invite_score)])->execute();
//            $connection->createCommand()->upsert('user_score',$param,['total_score'=>new \yii\db\Expression('total_score + '.$inviteScore->invite_score)],$param)->execute();






          //写入邀请记录表inviteFriendLogs
          $inviteData->uid  =   $data['uid'];
          $inviteData->friend_uid   =   $data['inviteUid'];
          $inviteData->cauth_iden   =   $data['cauth_iden'];
          $inviteData->invite_score =   $inviteScore->invite_score;
          if($userScoreData->insert() == false){
              $transaction->rollBack();
              throw new \Exception('写入邀请记录表失败',20017);
          }

          //写入积分记录表scoreLogs
          $scoreLogsData    =   $this->model.'score\Scorelogs';
          $scoreLogs    =   new $scoreLogsData;
          $scoreLogs->cauth_iden   =   $data['cauth_iden'];
          $scoreLogs->uid  =   $data['uid'];
          $scoreLogs->score     =   $inviteScore->invite_score;
          $scoreLogs->types     =   'invite';//获得积分类型  invite 为邀请好友
          $scoreLogs->dates     =   time();
          if($scoreLogs->insert() == false){
              $transaction->rollBack();
              throw new \Exception('写入积分记录表失败',20018);
          }
          $transaction->commit();

          return true;



      }

    }