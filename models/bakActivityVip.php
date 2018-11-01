<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_vip".
 *
 * @property int $id
 * @property int $uid
 * @property int $vip
 * @property int $vip_id
 * @property int $vip_stime
 * @property int $vip_etime
 * @property int $status
 */
class ActivityVip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_vip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'vip', 'vip_id', 'vip_stime', 'vip_etime', 'status'], 'integer'],
            [['vip_stime'], 'required'],
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
            'vip' => 'Vip',
            'vip_id' => 'Vip ID',
            'vip_stime' => 'Vip Stime',
            'vip_etime' => 'Vip Etime',
            'status' => 'Status',
        ];
    }
}
