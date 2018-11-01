<?php

	namespace app\services\score;
	use app\models\ActivityVipSet;
    use app\models\admin\AdminUser;
    use app\models\AuthRules;
    use app\models\score\InviteFriendLogs;
    use app\models\score\ScoreLog;//积分明细表
    use app\models\score\ScoreSet;//积分设置表
    use app\models\score\UserScore;//积分设置表
    use app\models\User;
	use app\models\Cauth;
    use Yii;

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
          $transaction = Yii::$app->db->beginTransaction();
          $userScore  =   $this->model.'score\UserScore';
          $set   =   $this->model.'score\ScoreSet';
          $scoreSet  =   $set::findOne(['cauth_iden'=>$data['cauth_iden']]);
          if(!$scoreSet)
              throw new \Exception('不存在积分设置',1);

          $friend   = InviteFriendLogs::findOne(['uid'=>$data['uid'],'friend_uid'=>$data['inviteUid']]);
            if($friend)
                throw new \Exception('每个用户只能邀请/被邀请一次哦',1);


              $friendInsert     =   new InviteFriendLogs();
              $friendInsert->uid =    $data['uid'];
              $friendInsert->friend_uid   =   $data['inviteUid'];
              $friendInsert->invite_score     =   $scoreSet->invite_score;
              $friendInsert->cauth_iden   =   $data['cauth_iden'];
              $friendInsert->dates  =   time();
              if($friendInsert->save()==false){
                  $transaction->rollBack();
                  throw new \Exception('写入邀请好友表失败',1);
              }

          $user       =  UserScore::findOne(['uid'=>$data['uid']]);
          if(!$user)
              throw new \Exception('不存在该用户记录',1);

          $user->total_score    =   $user->total_score+$scoreSet->invite_score;
          if($user->save() == false){
              $transaction->rollBack();
              throw new \Exception('写入记录失败',1);
          }


          $inviteUser   =   UserScore::findOne(['uid'=>$data['inviteUid']]);
          if(!$inviteUser)
              throw new \Exception('不存在被邀用户记录',1);

          $inviteUser->total_score    =   $inviteUser->total_score+$scoreSet->invite_score;
          if($inviteUser->save() == false){
              $transaction->rollBack();
              throw new \Exception('写入记录失败',1);
          }

          $scoreLog     =   new ScoreLog();
          $scoreLog->uid    =   $data['uid'];
          $scoreLog->cauth_iden     =   $data['cauth_iden'];
          $scoreLog->type  =   'invite';
          $scoreLog->dates  =   time();
          $scoreLog->kinds  =   0; //0为增加
          $scoreLog->score  =   $scoreSet->invite_score;

          if($scoreLog->save() == false){
              $transaction->rollBack();
              throw new \Exception('写入记录失败',1);
          }else{
              $scoreLog     =   new ScoreLog();
              $scoreLog->uid    =   $data['inviteUid'];
              $scoreLog->cauth_iden     =   $data['cauth_iden'];
              $scoreLog->type  =   'invite';
              $scoreLog->dates  =   time();
              $scoreLog->kinds  =   0; //0为增加
              $scoreLog->score  =   $scoreSet->invite_score;
              if($scoreLog->save() == false) {
                  $transaction->rollBack();
                  throw new \Exception('写入记录失败', 1);
              }
          }

              $transaction->commit();
            return ['message'=>'成功获得邀请积分啦!','data'=>true];
      }

      function inviteList($page)
      {
          /*
           * 邀请好友列表查询
           * InviteFriendLogs
           * */
//          $inviteModel = $this->model.'score\InviteFriendLogs';
          if(empty($page))
              $page     =   1;

          $size = 8;//一次读取20条信息
          $skip = (intval($page)-1)*$size;

          $inviteData   =   InviteFriendLogs::find()
                            ->where(['status'=>0])
                            ->limit($size)
                            ->offset($skip)
                            ->orderBy('id desc')
                            ->asArray()
                            ->all();
          $result  = array();
//          echo InviteFriendLogs::find()->where([])->count();exit();
//          print_r($inviteData);exit();
          foreach ($inviteData as $key =>$value)
          {
              $user     =    User::find()->where('id = '.$value['uid'])->asArray()->one();
              $friend   =       User::find()->where('id = '.$value['friend_uid'])->asArray()->one();

              $result[$key]['id']   =   $value['id'];
              $result[$key]['uid']  =   $value['uid'];
              $result[$key]['user']     =   $user['nickName'];
              $result[$key]['friend_uid'] = $value['friend_uid'];
              $result[$key]['friend']   =  $friend['nickName'];
              $result[$key]['invite_score']  =   $value['invite_score'];
              $result[$key]['types']  =   '邀请好友';
              $result[$key]['dates']    =   date('Y-m-d',$value['dates']);
              $result[$key]['status']  =   $value['status'];
          }
          return $result;
      }

      function scoreMark($page)
      {
          /*
           * 签到明细
           * */
          if(empty($page))
              $page     =   1;

          $size = 8;//一次读取20条信息
          $skip = (intval($page)-1)*$size;

          //查询score_log表，types = mark
          $scoreMark    =   ScoreLog::find()->where(['type'=>'mark'])
                            ->limit($size)
                            ->offset($skip)
                            ->orderBy('id desc')
                            ->asArray()
                            ->all();
        $result     =   [];
//        print_r($scoreMark);exit();
          foreach ($scoreMark as $key =>$value)
        {
            $user     =    User::find()->where('id = '.$value['uid'])->asArray()->one();
            $result[$key]['id']     =   $value['id'];
            $result[$key]['nickName']   =   $user['nickName'];
            $result[$key]['score']  =   $value['score'];
            $result[$key]['dates']  =   date('Y-m-d H:i:s',$value['dates']);
            $result[$key]['types']  =   '签到';
            $result[$key]['kinds']  =   $value['kinds']=='0'?'增加积分':'消费积分';

        }
        return $result;
      }


    }