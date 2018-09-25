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
            [['uid', 'friend_uid', 'invite_score','status'], 'integer'],
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
            'status' => 'Status',
        ];
    }
}
