<?php

namespace app\models\score;

use Yii;

/**
 * This is the model class for table "user_score".
 *
 * @property int $id
 * @property string $cauth_iden
 * @property int $total_score
 * @property int $uid
 */
class UserScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_score', 'uid'], 'integer'],
            [['cauth_iden'], 'string', 'max' => 33],
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
            'total_score' => 'Total Score',
            'uid' => 'Uid',
        ];
    }
}
