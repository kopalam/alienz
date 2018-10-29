<?php

namespace app\models\score;

use Yii;

/**
 * This is the model class for table "invite_friend_logs".
 *
 * @property int $id
 * @property int $uid 邀请人
 * @property int $friend_uid 被邀请人
 * @property int $invite_score 邀请获得积分
 * @property string $cauth_iden
<<<<<<< HEAD
 * @property string $dates
=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
 */
class InviteFriendLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invite_friend_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
<<<<<<< HEAD
            [['uid', 'friend_uid', 'invite_score','status','dates'], 'integer'],
=======
            [['uid', 'friend_uid', 'invite_score','status'], 'integer'],
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
            [['cauth_iden'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'friend_uid' => 'Friend Uid',
            'invite_score' => 'Invite Score',
            'cauth_iden' => 'Cauth Iden',
<<<<<<< HEAD
            'dates'=>'Dates',
=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
            'status' => 'Status',
        ];
    }
}
