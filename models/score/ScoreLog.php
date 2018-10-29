<?php

namespace app\models\score;

use Yii;

/**
 * This is the model class for table "score_log".
 *
 * @property int $id
 * @property string $cauth_iden
 * @property int $uid
 * @property int $score
 * @property string $types 类型 如 签到 邀请 购买
 * @property int $dates
 * @property int $status
 * @property int $kinds
<<<<<<< HEAD
 * @property string $type
=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
 */
class ScoreLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'score_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'score', 'dates', 'status', 'kinds'], 'integer'],
            [['cauth_iden', 'type'], 'string', 'max' => 33],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cauth_iden' => 'Cauth Iden',
            'uid' => 'Uid',
            'score' => 'Score',
            'type' => 'Type',
            'dates' => 'Dates',
            'status' => 'Status',
            'kinds' => 'Kinds',
        ];
    }
}
